<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'teammate_image',
        'teammate_name',
        'department',
        'designation',
        'membership_period',
        'small_desc',
        'details',
        'facebook_link',
        'linkedin_link',
    ];
}
