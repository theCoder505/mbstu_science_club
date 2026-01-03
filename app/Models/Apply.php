<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{
    protected $fillable = [
        'applicant_name',
        'email',
        'designation',
        'member_since',
        'member_till',
        'impact',
        
        'certificate_text',
        'certificate_status',
        'template_id',
        'issued_by',
        'note',
        'issue_date',

        'certificate_positions',
        'certificate_file',
    ];
}
