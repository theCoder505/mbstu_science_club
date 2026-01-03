<?php

namespace App\Providers;

use App\Models\Advisor;
use App\Models\WebSettings;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $contact_email = WebSettings::find(1)->contact_email;
        $phone_number = WebSettings::find(1)->phone_number;
        $facebook_link = WebSettings::find(1)->facebook_link;
        $twitter_link = WebSettings::find(1)->twitter_link;
        $instagram_link = WebSettings::find(1)->instagram_link;
        $linkedin_link = WebSettings::find(1)->linkedin_link;


        View::share('contact_email', $contact_email);
        View::share('phone_number', $phone_number);
        View::share('facebook_link', $facebook_link);
        View::share('twitter_link', $twitter_link);
        View::share('instagram_link', $instagram_link);
        View::share('linkedin_link', $linkedin_link);


        Inertia::share([
            'webSettings' => [
                'contact_email' => $contact_email,
                'phone_number' => $phone_number,
                'facebook_link' => $facebook_link,
                'twitter_link' => $twitter_link,
                'instagram_link' => $instagram_link,
                'linkedin_link' => $linkedin_link,
            ]
        ]);
    }
}
