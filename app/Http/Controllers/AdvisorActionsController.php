<?php

namespace App\Http\Controllers;

use App\Models\Advisor;
use App\Models\Apply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdvisorActionsController extends Controller
{
    public function advisorLogin()
    {
        // Redirect if already logged in
        if (Auth::guard('advisor')->check()) {
            return redirect()->route('advisor_panel.dahsboard');
        }

        return Inertia::render('advisor_panel/Login');
    }





    public function verifyAndLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::guard('advisor')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->route('advisor_panel.dahsboard')
                ->with('success', 'Welcome Back! Wish You A Good Day!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }





    public function advisorDashboard()
    {
        $id = Auth::guard('advisor')->user()->id;
        $applications = Apply::where('issued_by', $id)->orderBy('id', 'DESC')->get();

        return Inertia::render('advisor_panel/Index', [
            'applications' => $applications,
            'advisor' => Auth::guard('advisor')->user(),
        ]);
    }










    public function approveApplication(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:applies,id',
        ]);

        $id = $request['id'];
        $application = Apply::findOrFail($id);
        $application->certificate_status = 'approved';
        $application->issued_by = Auth::guard('advisor')->id();
        $application->issue_date = now();
        $application->save();



        $applicant_email = $application->email;
        $certificate_file = url('/storage/' . $application->certificate_file);
        Mail::send('emails.applicant_on_approve', ['certificate_file' => $certificate_file, 'applicant_name' => $application->applicant_name], function ($message) use ($applicant_email) {
            $message->to($applicant_email)
                ->subject('Certificate Approval Request From MBSTUSC');
        });


        return redirect()->route('advisor_panel.dahsboard')
            ->with('success', 'Application approved successfully!');
    }











    public function advisorProfile()
    {
        $advisor = Auth::guard('advisor')->user();
        return Inertia::render('advisor_panel/Profile', [
            'advisor' => $advisor,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $logger = Auth::guard('advisor')->user();
        $id = $logger->id;
        $advisor = Advisor::findOrFail($id);

        $validated = $request->validate([
            'advisor_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:advisors,email,' . $advisor->id],
            'department' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],

            'facebook' => ['nullable', 'string', 'max:255', 'url'],
            'twitter' => ['nullable', 'string', 'max:255', 'url'],
            'linkedin' => ['nullable', 'string', 'max:255', 'url'],
            'google_scholar' => ['nullable', 'string', 'max:255', 'url'],
            'research_gate' => ['nullable', 'string', 'max:255', 'url'],
            'portfolio' => ['nullable', 'string', 'max:255', 'url'],

            'password' => ['nullable', 'string', 'min:8', 'max:255', 'confirmed'],

            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:20480'],
            'signature' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:20480'],
        ]);

        try {
            if ($request->hasFile('profile_image')) {
                if ($advisor->profile_image) {
                    Storage::disk('public')->delete($advisor->profile_image);
                }

                $profileImage = $request->file('profile_image');
                $profileImageName = 'profile_' . time() . '.' . $profileImage->getClientOriginalExtension();
                $profileImagePath = $profileImage->storeAs('advisors', $profileImageName, 'public');
                $validated['profile_image'] = $profileImagePath;
            } else {
                $validated['profile_image'] = $request->input('current_profile_image', $advisor->profile_image);
            }

            if ($request->hasFile('signature')) {
                if ($advisor->signature) {
                    Storage::disk('public')->delete($advisor->signature);
                }

                $signature = $request->file('signature');
                $signatureName = 'signature_' . time() . '.' . $signature->getClientOriginalExtension();
                $signaturePath = $signature->storeAs('advisor_sign', $signatureName, 'public');
                $validated['signature'] = $signaturePath;
            } else {
                $validated['signature'] = $request->input('current_signature', $advisor->signature);
            }

            if ($request->filled('password')) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $advisor->update($validated);

            return redirect()->route('advisor_panel.profile')
                ->with('success', 'Profile Updated Successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('advisor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('advisor_panel.login_page')
            ->with('success', 'Logged out successfully!');
    }
}
