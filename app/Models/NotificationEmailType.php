<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationEmailType extends Model
{
    protected $fillable = [
        'notification_email_id',
        'name',
    ];

    public function emails()
    {
        return $this->belongsToMany(NotificationEmail::class, 'notification_email_has_types', 'notification_email_type_id', 'notification_email_id');
    }

    public static function getEmails($type)
    {
        $emailType = self::where('name', $type)->first();

        if ($emailType) {
            return $emailType->emails;
        }

        return [];
    }
}
