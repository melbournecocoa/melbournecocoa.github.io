<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('title');
            $table->text('subtitle');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->string('location');
            $table->string('location_link');
            $table->string('address_display');
            $table->string('address');
            $table->float('lat');
            $table->float('lng');
            $table->string('tickets')->nullable();
            $table->string('contact');
            $table->string('contact_name');
            $table->timestamps();

            $table->index('type');
        });

        Schema::create('events_sponsors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('event_id');
            $table->unsignedInteger('sponsor_id');

            $table->index('event_id');
            $table->index('sponsor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('events');
        Schema::drop('events_sponsors');
    }
}
