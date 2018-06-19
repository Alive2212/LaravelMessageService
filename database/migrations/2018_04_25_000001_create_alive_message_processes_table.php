<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAliveMessageProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alive_message_processes', function (Blueprint $table) {
            $table->increments('id');

            $table->enum('type', ['Sms', 'Notification', 'Social','Email'])
                ->nullable();

            $table->unsignedInteger('scope')
                ->nullable();

            $table->text('from')
                ->nullable();

            $table->text('to')
                ->nullable();

            $table->text('body');

            // to show message or not
            $table->boolean('invisible')
                ->dafault(false);

            $table->text('launch_time'); // it can a field of model if string and if data time

            // Event field
            $table->unsignedInteger('event_id')
                ->index();

            $table->foreign('event_id')
                ->references('id')
                ->on('alive_message_events')
                ->onDelete('cascade')
                ->onUpdate('cascade');

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
        Schema::dropIfExists('alive_message_processes');
    }
}
