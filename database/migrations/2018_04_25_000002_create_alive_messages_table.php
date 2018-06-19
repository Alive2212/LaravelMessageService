<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAliveMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alive_messages', function (Blueprint $table) {
            $table->increments('id');

            // From field
            $table->unsignedInteger('from')
                ->nullable()
                ->index();

            $table->foreign('from')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // To field
            $table->unsignedInteger('to')
                ->nullable()
                ->index();

            $table->foreign('to')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('body')
                ->nullable();

            // to show message or not
            $table->boolean('invisible')
                ->default(false);

            // Process field
            $table->unsignedInteger('process_id')
                ->index();

            $table->foreign('process_id')
                ->references('id')
                ->on('alive_message_processes')
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
        Schema::dropIfExists('alive_messages');
    }
}
