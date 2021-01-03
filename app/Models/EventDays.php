<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDays extends Model
{
    use HasFactory;


    public function event()
    {
        return $this->belongsTo('App\Models\CalendarEvents', 'event_id');
    }

    protected $table = 'event_days';
}
