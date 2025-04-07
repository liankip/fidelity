<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MinutesOfMeeting extends Model
{
    use HasFactory;

    protected $table = 'minutes_of_meeting';

    protected $fillable = [
        'id',
        'id_project',
        'date',
        'meeting_title',
        'upload_file',
        'status',
        'slug',
        'comment',
        'comment_by',
        'approved_by',
        'approved_at',
        'approved_by_2',
        'approved_at_2',
        'rejected_by',
        'rejected_at',
    ];

    public function points(): HasMany
    {
        return $this->hasMany(Poin::class, 'id_minute_of_meeting', 'id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class, 'id_minute_of_meeting', 'id');
    }

    public function approved()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function approved_2()
    {
        return $this->belongsTo(User::class, 'approved_by_2', 'id');
    }

    public function rejected()
    {
        return $this->belongsTo(User::class, 'rejected_by', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project', 'id');
    }
}
