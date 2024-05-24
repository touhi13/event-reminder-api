<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'event_date', 'reminder_recipients'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->event_id = 'EVT-' . strtoupper(Str::random(8));
        });
    }

    public function getReminderRecipientsAttribute($value)
    {
        return json_decode($value);
    }

    public function setReminderRecipientsAttribute($value)
    {
        $this->attributes['reminder_recipients'] = json_encode($value);
    }
}
