<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Advisor extends Authenticatable
{
    protected $fillable = [
        'advisor_name',
        'email',
        'department',
        'designation',
        'club_role',
        'facebook',
        'twitter',
        'linkedin',
        'google_scholar',
        'research_gate',
        'portfolio',
        'profile_image',
        'signature',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];
}