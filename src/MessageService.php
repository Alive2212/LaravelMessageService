<?php
/**
 * Created by PhpStorm.
 * User: alive
 * Date: 4/28/18
 * Time: 7:17 AM
 */

namespace Alive2212\LaravelMessageService;



class MessageService
{
    public function handle($model,$eventType)
    {
        // get model class name
        $modelClassName = get_class($model);

        $event = new AliveMessageEvent();
        $event= $event->where([
            ['model', '=', $modelClassName],
            ['type', '=', $eventType],
        ])->with('messageProcesses');
        $eventParams = $event->get()->toArray();

        if(count($eventParams)){
            dd ($eventParams);
        }
    }
}