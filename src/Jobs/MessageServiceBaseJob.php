<?php

namespace Alive2212\LaravelMessageService\Jobs;

use Alive2212\LaravelMessageService\AliveMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MessageServiceBaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    protected $processParams;

    /**
     * @var array
     */
    protected $modelParams;

    /**
     * To user who receive message
     *
     * @var
     */
    protected $to;

    /**
     * From user who send message
     *
     * @var
     */
    protected $from;

    /**
     * Body of message
     *
     * @var
     */
    protected $body;

    /**
     * Visibility of message when user watch to message box
     *
     * @var
     */
    protected $invisible;

    /**
     * Id of process that call this event
     *
     * @var
     */
    protected $processId;

    /**
     * This Job will be fired by a precess
     * @param array $processParams
     * @param array $modelParams
     */
    public function __construct(array $processParams, array $modelParams)
    {
        $this->processParams = $processParams;
        $this->modelParams = $modelParams;

        $this->initValues($processParams, $modelParams);

        $this->storeMessage();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }

    /**
     * @param array $processParams
     * @param array $modelParam
     */
    public function initValues(array $processParams, array $modelParam)
    {
        // get process id
        $this->processId = $processParams['id'];

        // filling data
        $this->invisible = $processParams['invisible'];

        $processFrom = $processParams['from'];
        if (isset($modelParam[$processFrom])) {
            $this->from = $modelParam[$processFrom];
        }

        $processTo = $processParams['to'];
        if (isset($modelParam[$processTo])) {
            $this->to = $modelParam[$processTo];
        }

        $this->initBody($processParams, $modelParam);
    }

    /**
     * @param array $processParams
     * @param $modelParam
     */
    public function initBody(array $processParams, $modelParam)
    {
        $processBody = $processParams['body'];
        $processBodyParams = explode('{', $processBody);
        foreach ($processBodyParams as $processBodyParam) {
            $processBodyTag = explode('}', $processBodyParam);
            if (count($processBodyTag) > 1) {
                if (isset($modelParam[$processBodyTag[0]])) {
                    // TODO it should get related parameters
                    $this->body .= $modelParam[$processBodyTag[0]];
                }
                array_shift($processBodyTag);
            }
            $this->body .= $processBodyTag[0];
        }
    }

    /**
     * Store message into Alive Message table
     */
    public function storeMessage()
    {
        // store message to Alive Message model
        $message = new AliveMessage();
        $message->create([
            'to' => $this->to == '' ? null : $this->to,
            'from' => $this->from,
            'body' => $this->body,
            'invisible' => $this->invisible,
            'process_id' => $this->processId,
        ]);
    }

}
