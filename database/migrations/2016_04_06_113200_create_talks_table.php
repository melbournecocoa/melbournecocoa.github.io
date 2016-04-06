<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTalksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('event_id')->nullable();
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->string('slack')->nullable();
            $table->string('twitter')->nullable();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->text('extra')->nullable();
            $table->text('type')->nullable();
            $table->text('format')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('talks');
    }
}
