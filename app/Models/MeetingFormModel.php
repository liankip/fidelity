<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingFormModel extends Model
{
    use HasFactory;

    protected $table='meeting_form';

    protected $fillable= [
        'meeting_date',
        'meeting_location',
        'meeting_attendant',
        'meeting_notulen',
        'notulensi'
    ];

}
