<?php

namespace App\Http\Controllers;

use App\Models\WebSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminWebTasksController extends Controller
{






    public function websiteSettings()
    {
        $web_settings = WebSettings::find(1);
        return Inertia::render('settings/Websettings', [
            'settings' => $web_settings
        ]);
    }




    public function updateWebSettings(Request $request)
    {
        $web_settings = WebSettings::find(1);
        $validated = $request->validate([
            'president_name' => ['required', 'max:255'],
            'president_sign' => ['nullable', 'max:20480'],
            'facebook_link' => ['required', 'max:255'],
            'twitter_link' => ['required', 'max:255'],
            'instagram_link' => ['required', 'max:255'],
            'linkedin_link' => ['required', 'max:255'],
            'contact_email' => ['required', 'max:255'],
            'phone_number' => ['required', 'max:255'],
            'join_form' => ['required', 'max:255'],
            'about_us' => ['required'],
            'privacy_policy' => ['required'],
            'terms_conditions' => ['required'],
            'code_of_conduct' => ['required'],
        ]);


        if ($request->hasFile('president_sign')) {
            if ($web_settings->president_sign) {
                Storage::disk('public')->delete($web_settings->president_sign);
            }

            $signature = $request->file('president_sign');
            $signatureName = 'president_' . time() . '.' . $signature->getClientOriginalExtension();
            $signaturePath = $signature->storeAs('president_', $signatureName, 'public');
            $validated['president_sign'] = $signaturePath;
        } else {
            $validated['president_sign'] = $request->input('president_sign', $web_settings->president_sign);
        }

        $web_settings->update($validated);
        return redirect()->route('web_settings.index')->with('success', 'Changes Updated Successfully!');
    }














    //
}
