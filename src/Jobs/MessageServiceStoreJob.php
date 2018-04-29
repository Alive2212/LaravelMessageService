<?php

namespace App\Jobs;

use Alive2212\LaravelMessageService\AliveMessage;

class MessageServiceNotificationJob extends MessageServiceBaseJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = new AliveMessage();


    }
}
