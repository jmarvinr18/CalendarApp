<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarEvents;
use Carbon\Carbon;
use App\Http\Controllers\EventDaysController;
use Illuminate\Support\Facades\DB;

class CalendarEventController extends Controller
{

    public function index()
    {
        $calendar = DB::table('calendar_events')
        ->join('event_days', 'calendar_events.id', '=', 'event_days.event_id')
        ->select('calendar_events.*', 'event_days.event_date')
        ->get();
        return $calendar;
    } 
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $calendarEvent = CalendarEvents::with('eventdays')->find($id);
        return response()->json(array('event_details' => $calendarEvent), 200);
    }
    public function store(Request $request)
    {

        $eventDates = $request->event_dates;
        $calendarEvents = new CalendarEvents;
        $calendarEvents->event_name = $request->event_overview['event_name'];
        $calendarEvents->from = $request->event_overview['from_date'];
        $calendarEvents->to = $request->event_overview['to_date'];
        $calendarEvents->save();
        $calendarEventId = $calendarEvents->id;

        $eventDays = new EventDaysController();
        $eventDays->store($calendarEventId, $eventDates);

       return response()->json(array('success' => true, 'event_id' => $calendarEventId), 200);
    }

    public function update(Request $request, $id)
    {
        $eventDates = $request->event_dates;
        $calendarEvents =  CalendarEvents::find($id);
        $calendarEvents->event_name = $request->event_overview['event_name'];
        $calendarEvents->from = $request->event_overview['from_date'];
        $calendarEvents->to = $request->event_overview['to_date'];
        $calendarEvents->save();

        $eventDays = new EventDaysController();
        $eventDays->store($id, $eventDates);
    }
}
