<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CalendarEvents extends Model
{
    use HasFactory;

    public function eventdays()
    {
        return $this->hasMany('App\Models\EventDays', 'event_id');
    }

    protected $table = 'calendar_events';
}
