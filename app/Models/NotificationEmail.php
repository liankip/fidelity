<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class NotificationEmail extends Model
{
    protected $fillable = [
        'name',
        'email',
        'type_id'
    ];

    public function types()
    {
        return $this->belongsToMany(NotificationEmailType::class, 'notification_email_has_types', 'notification_email_id', 'notification_email_type_id');
    }
}
