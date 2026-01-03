<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Cache;

class AdminProfileController extends Controller
{
    /**
     * Update user's name
     */
    public function updateName(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->update([
            'name' => $request->name,
        ]);

        return back()->with('status', 'name-updated');
    }

    /**
     * Request email change - send OTP
     */
    public function requestEmailChange(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
        ]);

        // Generate OTP
        $otp = rand(111111, 999999);
        
        // Store OTP and new email in cache for 10 minutes
        $cacheKey = 'email_change_otp_' . $request->user()->id;
        Cache::put($cacheKey, [
            'otp' => $otp,
            'new_email' => $request->email,
            'current_password' => null, // Not needed for email change
        ], now()->addMinutes(10));

        // Send OTP email
        Mail::send('emails.admin_email_change_otp', [
            'user' => $request->user(),
            'otp' => $otp,
            'newEmail' => $request->email,
        ], function ($message) use ($request) {
            $message->to($request->user()->email)
                    ->subject('Email Change Verification - MBSTU Science Club');
        });

        return back()->with('status', 'email-otp-sent');
    }

    /**
     * Verify OTP and change email
     */
    public function verifyEmailOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $cacheKey = 'email_change_otp_' . $request->user()->id;
        $cachedData = Cache::get($cacheKey);

        if (!$cachedData) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        if ($cachedData['otp'] != $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        // Update email
        $request->user()->update([
            'email' => $cachedData['new_email'],
            'email_verified_at' => now(), // Auto-verify the new email
        ]);

        // Clear cache
        Cache::forget($cacheKey);

        return back()->with('status', 'email-updated');
    }

    /**
     * Request password change - send OTP
     */
    public function requestPasswordChange(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // Generate OTP
        $otp = rand(111111, 999999);
        
        // Store OTP and new password in cache for 10 minutes
        $cacheKey = 'password_change_otp_' . $request->user()->id;
        Cache::put($cacheKey, [
            'otp' => $otp,
            'new_password' => Hash::make($request->password),
        ], now()->addMinutes(10));

        // Send OTP email
        Mail::send('emails.admin_pwd_change_otp', [
            'user' => $request->user(),
            'otp' => $otp,
        ], function ($message) use ($request) {
            $message->to($request->user()->email)
                    ->subject('Password Change Verification - MBSTU Science Club');
        });

        return back()->with('status', 'password-otp-sent');
    }

    /**
     * Verify OTP and change password
     */
    public function verifyPasswordOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $cacheKey = 'password_change_otp_' . $request->user()->id;
        $cachedData = Cache::get($cacheKey);

        if (!$cachedData) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        if ($cachedData['otp'] != $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        // Update password
        $request->user()->update([
            'password' => $cachedData['new_password'],
        ]);

        // Clear cache
        Cache::forget($cacheKey);

        return back()->with('status', 'password-updated');
    }
}