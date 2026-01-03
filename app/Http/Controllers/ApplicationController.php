<?php

namespace App\Http\Controllers;

use App\Models\Advisor;
use App\Models\Apply;
use App\Models\Template;
use App\Models\WebSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{




    public function application()
    {
        return Inertia::render('Applications/ApplyPage');
    }




    public function apply_certificate(Request $request)
    {
        $vallidate = $request->validate([
            'applicant_name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:applies'],
            'designation' => ['required', 'max:255'],
            'member_since' => ['required', 'max:255'],
            'member_till' => ['required', 'max:255'],
            'impact' => ['max:500'],
        ]);

        $application = Apply::create($vallidate);
        return redirect()->route('track_page')->with('success', 'Your application has been submitted successfully!')->with('applicant_mail', $request->email);
    }




    public function track_application(Request $request)
    {
        $record = null;
        $email = $request->get('email') ?? session('applicant_mail');

        if ($email) {
            $record = Apply::where('email', $email)->first();
            $record = $record ? $record->only('certificate_status') : null;
        }

        return Inertia::render('Applications/TrackApplication', [
            'applicant_mail' => $email,
            'record' => $record
        ]);
    }


    public function track_result(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);
        $track_record = Apply::where('email', $validated['email'])->first();

        if ($track_record) {
            return Inertia::render("Applications/TrackApplication", [
                'record' => $track_record->only('certificate_status'),
                'applicant_mail' => $validated['email'] // This stores the email
            ]);
        }

        return Inertia::render("Applications/TrackApplication", [
            'record' => null,
            'applicant_mail' => $validated['email']
        ]);
    }


    public function download_certificate(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);
        $track_record = Apply::where('email', $validated['email'])->first();
        return response()->json(['application' => $track_record]);
    }















    public function applications(Request $request)
    {
        return Inertia::render('Applications/AllApplications', [
            'applications' => Apply::when($request->search, function ($query) use ($request) {
                $query
                    ->where('applicant_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('designation', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('certificate_status', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->search . '%');
            })->orderBy('id', 'DESC')->paginate(5)->withQueryString(),

            'searchTerm' => $request->search
        ]);
    }



    public function delete_application(Request $request)
    {
        $find = Apply::find($request->itemID);
        $find->delete();

        return Inertia::render('Applications/AllApplications', [
            'applications' => Apply::orderBy('id', 'DESC')->paginate(5)->withQueryString(),

            'searchTerm' => $request->search
        ])->with('success', 'Application deleted successsfully!');
    }






    public function validate_application($id, $applicant_name)
    {
        $application = Apply::where('id', $id)
            ->where('applicant_name', $applicant_name)
            ->first();

        if (!$application) {
            abort(404, 'Application not found');
        }

        $advisors = Advisor::orderBy('id', 'DESC')->get();
        $templates = Template::orderBy('id', 'DESC')->get();

        if ($application->issued_by) {
            $selectedAdvisor = Advisor::find($application->issued_by);
            if ($selectedAdvisor) {
                $advisor_name = $selectedAdvisor->advisor_name;
                $advisor_sign = '/storage/' . $selectedAdvisor->signature;
            } else {
                $defaultAdvisor = Advisor::first();
                $advisor_name = $defaultAdvisor->advisor_name;
                $advisor_sign = '/storage/' . $defaultAdvisor->signature;
            }
        } else {
            $defaultAdvisor = Advisor::first();
            $advisor_name = $defaultAdvisor->advisor_name;
            $advisor_sign = '/storage/' . $defaultAdvisor->signature;
        }

        if ($application->template_id) {
            $selectedTemplate = Template::find($application->template_id);
            if ($selectedTemplate) {
                $certificate_template = '/storage/' . $selectedTemplate->template_image;
            } else {
                $certificate_template = '/storage/' . Template::first()->template_image;
            }
        } else {
            $certificate_template = '/storage/' . Template::first()->template_image;
        }

        $president_name = WebSettings::find(1)->president_name;
        $president_sign = '/storage/' . WebSettings::find(1)->president_sign;

        return Inertia::render('Applications/SpecificApplication', [
            'application' => $application,
            'advisor_name' => $advisor_name,
            'advisor_sign' => $advisor_sign,
            'president_name' => $president_name,
            'president_sign' => $president_sign,
            'advisors' => $advisors,
            'templates' => $templates,
            'certificate_template' => $certificate_template,
        ]);
    }






    public function updateApplication(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:applies,id',
            'note' => 'nullable|string|max:300',
            'certificate_text' => 'nullable|string',
            'certificate_status' => 'required|in:pending,verified,revision,approved,declined',
            'certificate_template' => 'nullable|string',
            'certificate_issued_by' => 'nullable|string',
            'certificate_positions' => 'nullable|string',
            'certificate_file' => 'nullable|string',
        ]);

        $application = Apply::findOrFail($request->application_id);

        // Update basic fields
        $application->note = $request->note;
        $application->certificate_text = $request->certificate_text;
        $application->certificate_status = $request->certificate_status;
        $application->template_id = $request->certificate_template;
        $application->issued_by = $request->certificate_issued_by;
        $application->certificate_positions = $request->certificate_positions;

        // Handle certificate file upload
        if ($request->certificate_file) {
            try {
                // Delete old certificate if exists
                if ($application->certificate_file && Storage::disk('public')->exists($application->certificate_file)) {
                    Storage::disk('public')->delete($application->certificate_file);
                }

                // Decode base64 image
                $imageData = $request->certificate_file;

                // Remove data:image/png;base64, prefix if present
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $type = strtolower($type[1]); // jpg, png, gif
                } else {
                    throw new \Exception('Invalid image format');
                }

                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed');
                }

                // Generate unique filename
                $applicantNameSlug = Str::slug($application->applicant_name);
                $randomNumber = rand(11111, 99999);
                $filename = "certificates/{$applicantNameSlug}_{$randomNumber}.png";

                // Ensure certificates directory exists
                if (!Storage::disk('public')->exists('certificates')) {
                    Storage::disk('public')->makeDirectory('certificates');
                }

                // Store the file
                Storage::disk('public')->put($filename, $imageData);

                // Update application with new certificate path
                $application->certificate_file = $filename;
            } catch (\Exception $e) {
                Log::error('Certificate upload failed: ' . $e->getMessage());
                return back()->withErrors(['certificate_file' => 'Failed to upload certificate image.']);
            }
        }

        $application->save();

        if ($request->certificate_status == 'verified') {
            $advisor_uid = $request->certificate_issued_by;
            $advisor_email = Advisor::find($advisor_uid)->email;
            $club_role = Advisor::find($advisor_uid)->club_role;

            Mail::send('emails.advisor_on_verification', ['application_id' => $request->application_id, 'club_role' => $club_role], function ($message) use ($advisor_email) {
                $message->to($advisor_email)
                    ->subject('Certificate Approval Request From MBSTUSC');
            });
        }

        return redirect()->back()->with('success', 'Application updated successfully!');
    }
}
