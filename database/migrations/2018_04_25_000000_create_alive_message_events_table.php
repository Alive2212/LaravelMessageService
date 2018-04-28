<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAliveMessageEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alive_message_events', function (Blueprint $table) {
            $table->increments('id');

            $table->text('model');

            $table->enum('type', [
                'Saving', 'Creating', 'Updating', 'Deleting', 'Restoring',
                'Saved', 'Created', 'Updated', 'Deleted', 'Restored',
                'pivotAttaching', 'pivotDetaching', 'pivotUpdating',
                'pivotAttached', 'pivotDetached', 'pivotUpdated',
            ]);

            $table->text('rules');

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
        Schema::dropIfExists('alive_message_events');
    }
}
