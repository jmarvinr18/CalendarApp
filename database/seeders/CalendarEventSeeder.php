<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
// use DB;

class CalendarEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('calendar_events')->insert([
            'event_name' => Str::random(10),
            'event_date' => Carbon::create('2000', '01', '01')
        ]);
    }
}
