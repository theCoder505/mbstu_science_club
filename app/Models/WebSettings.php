<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebSettings extends Model
{
    protected $fillable = [
        'president_name',
        'president_sign',
        'facebook_link',
        'twitter_link',
        'instagram_link',
        'linkedin_link',
        'contact_email',
        'phone_number',
        'join_form',
        'facebook_page_iframe',
        'about_us',
        'privacy_policy',
        'terms_conditions',
        'code_of_conduct',
    ];
}
