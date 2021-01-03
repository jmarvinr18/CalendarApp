<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EventDays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->date('event_date');
            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('calendar_events');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_days');
    }
}
