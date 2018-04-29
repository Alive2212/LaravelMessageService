<?php

namespace App\Jobs;

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

    protected $to;

    protected $from;

    protected $body;

    protected $invisible;

    /**
     * This Job will be fired by a precess
     * @param array $processParams
     * @param array $modelParams
     */
    public function __construct(array $processParams, array $modelParams)
    {
        $this->processParams = $processParams;
        $this->modelParams = $modelParams;


        // filling data
        $processFrom = $processParams['from'];
        if (isset($modelParams[$processFrom])) {
            $this->from = $modelParams[$processFrom];
        }

        $processTo = $processParams['to'];
        if (isset($modelParams[$processTo])) {
            $this->to = $modelParams[$processTo];
        }

        $processBody = $processParams['body'];
        $processBodyParams = explode('{', $processBody);
        foreach ($processBodyParams as $processBodyParam) {
            $processBodyTag = explode('}', $processBodyParam);
            if (count($processBodyTag) > 1) {
                if (isset($modelParams[$processBodyTag[0]])) {
                    $this->body .= $modelParams[$processBodyTag[0]];
                }
                unset($processBodyTag[0]);
            }
            $this->body .= $processBodyTag[0];
        }
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

    public function smartExpression(String $string, array $params)
    {

    }

}
