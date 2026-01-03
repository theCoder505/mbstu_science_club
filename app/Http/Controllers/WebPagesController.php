<?php

namespace App\Http\Controllers;

use App\Models\Advisor;
use App\Models\Apply;
use App\Models\Committee;
use App\Models\FAQ;
use App\Models\Gallery;
use App\Models\Statistic;
use App\Models\Team;
use App\Models\WebSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class WebPagesController extends Controller
{
    public function homePage()
    {
        $advisors = Advisor::orderBy('id', 'DESC')->get();
        $team = Team::orderBy('id', 'DESC')->get();
        $committees = Committee::orderBy('committee_period', 'DESC')->get();
        $faqs = FAQ::orderBy('id', 'DESC')->get();
        $gallery = Gallery::where('status', 'show')->orderBy('position', 'ASC')->get();
        $website = WebSettings::where('id', 1)->get();

        $active_members = '250';
        $event_organised = '100';
        $research_projects = '35';
        $awards_won = '10';

        $join_club_form = WebSettings::find(1)->join_form;
        $facebookPageUrl = WebSettings::find(1)->facebook_link;

        return Inertia::render('Home', [
            'advisors' => $advisors,
            'team' => $team,
            'committees' => $committees,
            'faqs' => $faqs,
            'gallery' => $gallery,
            'website' => $website,
            'active_members' => $active_members,
            'event_organised' => $event_organised,
            'research_projects' => $research_projects,
            'awards_won' => $awards_won,

            'join_club_form' => $join_club_form,
            'facebookPageUrl' => $facebookPageUrl,
        ]);
    }

    public function contactOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email address'
            ], 422);
        }

        $email = $request->input('email');
        $otp = rand(100000, 999999);

        // Store OTP in session with 10 minute expiration
        Session::put('contact_otp', $otp);
        Session::put('contact_otp_email', $email);
        Session::put('contact_otp_expires', now()->addMinutes(10));

        try {
            // Send OTP via email
            Mail::send('emails.contact-otp', ['otp' => $otp], function ($message) use ($email) {
                $message->to($email)
                    ->subject('MBSTUSC - Contact Form OTP Verification');
            });

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to your email'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.'
            ], 500);
        }
    }

    public function verifyAndSendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message_text' => 'required|string',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please fill all required fields'
            ], 422);
        }

        $email = $request->input('email');
        $subject = $request->input('subject');
        $message_text = $request->input('message_text');
        $otp = $request->input('otp');

        // Verify OTP
        $storedOtp = Session::get('contact_otp');
        $storedEmail = Session::get('contact_otp_email');
        $otpExpires = Session::get('contact_otp_expires');

        if (!$storedOtp || !$storedEmail || !$otpExpires) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired. Please request a new one.'
            ], 422);
        }

        if (now()->greaterThan($otpExpires)) {
            Session::forget(['contact_otp', 'contact_otp_email', 'contact_otp_expires']);
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.'
            ], 422);
        }

        if ($otp != $storedOtp || $email !== $storedEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please check and try again.'
            ], 422);
        }

        try {
            // Get website settings for recipient email
            $websiteSettings = WebSettings::first();
            $recipientEmail = $websiteSettings->contact_email ?? 'admin@mbstusc.edu.bd';

            // Send contact message to admin
            Mail::send('emails.contact-message', [
                'sender_email' => $email,
                'subject' => $subject,
                'message_body' => $message_text
            ], function ($message) use ($recipientEmail, $subject) {
                $message->to($recipientEmail)
                    ->subject('MBSTUSC Contact Form: ' . $subject);
            });

            // Send confirmation to sender
            Mail::send('emails.contact-confirmation', [
                'subject' => $subject
            ], function ($message) use ($email) {
                $message->to($email)
                    ->subject('MBSTUSC - We received your message');
            });

            // Clear OTP from session
            Session::forget(['contact_otp', 'contact_otp_email', 'contact_otp_expires']);

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again later.'
            ], 500);
        }
    }














    public function teammateProfile($id, $name)
    {
        $teammate = Team::where('id', $id)->where('teammate_name', $name)->first();
        return Inertia::render('Teammate', [
            'teammate' => $teammate,
        ]);
    }






    public function aboutUs()
    {
        $advisors = Advisor::orderBy('id', 'DESC')->get();
        $team = Team::orderBy('id', 'DESC')->get();
        $committees = Committee::orderBy('committee_period', 'DESC')->get();
        $data = WebSettings::find(1)->about_us;
        return Inertia::render('About', [
            'advisors' => $advisors,
            'team' => $team,
            'committees' => $committees,
            'data' => $data,
        ]);
    }



    public function privacy()
    {
        $data = WebSettings::find(1)->privacy_policy;
        return Inertia::render('Privacy', [
            'data' => $data,
        ]);
    }


    public function terms_conditions()
    {
        $data = WebSettings::find(1)->terms_conditions;
        return Inertia::render('Terms', [
            'data' => $data,
        ]);
    }


    public function code_of_conduct()
    {
        $data = WebSettings::find(1)->code_of_conduct;
        return Inertia::render('COC', [
            'data' => $data,
        ]);
    }








    public function adminDashboard()
    {
        $advisors = Advisor::orderBy('id', 'DESC')->get();
        $applications = Apply::orderBy('id', 'DESC')->get();
        $team = Team::orderBy('id', 'DESC')->get();
        $committees = Committee::orderBy('committee_period', 'DESC')->get();
        $faqs = FAQ::orderBy('id', 'DESC')->get();
        $gallery = Gallery::where('status', 'show')->orderBy('position', 'ASC')->get();

        $statistics = Statistic::orderBy('id', 'DESC')->get();

        return Inertia::render('Dashboard', [
            'advisors' => $advisors,
            'applications' => $applications,
            'team' => $team,
            'committees' => $committees,
            'faqs' => $faqs,
            'gallery' => $gallery,
            'statistics' => $statistics,
        ]);
    }
}
