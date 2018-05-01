<?php
/**
 * Created by PhpStorm.
 * User: alive
 * Date: 4/28/18
 * Time: 7:17 AM
 */

namespace Alive2212\LaravelMessageService;


use App\Jobs\MessageServiceEmailJob;
use App\Jobs\MessageServiceNotificationJob;
use App\Jobs\MessageServiceScopeJob;
use App\Jobs\MessageServiceSmsJob;
use App\Jobs\MessageServiceSocialJob;

class MessageService
{
    public function handle($model, $eventType)
    {
        // get model class name
        $modelClassName = get_class($model);

        $event = new AliveMessageEvent();
        $event = $event->where([
            ['model', '=', $modelClassName],
            ['type', '=', $eventType],
        ])->with('messageProcesses');
        $eventParams = $event->get()->toArray();
        if (count($eventParams)) {
            foreach ($eventParams as $eventParam) {
                $whereParams = [['id', '=', $model->id]];
                $eventRules = json_decode($eventParam['rules'], true);
                foreach ($eventRules as $eventRule) {
                    // TODO create multiple deep where
                    array_push($whereParams, [$eventRule[0], $eventRule[1], $eventRule[2]]);
                }

                $currentModel = (new $modelClassName())
                    ->where($whereParams);

                $currentModelParams = $currentModel->get()->toArray();
                if (count($currentModelParams)){
                    // this place is where process must be done
                    $processes = $eventParam['message_processes'];
                    foreach ($processes as $process) {
                        switch ($process['type']){
                            case "Sms":
                                dispatch(new MessageServiceSmsJob($process,$currentModelParams));
                                break;

                            case "Notification":
                                dispatch(new MessageServiceNotificationJob($process, $currentModelParams));
                                break;

                            case "Social":
                                dispatch(new MessageServiceSocialJob($process, $currentModelParams));
                                break;

                            case "Email":
                                dispatch(new MessageServiceEmailJob($process, $currentModelParams));
                                break;

                            default:
                                dispatch(new MessageServiceScopeJob($process,$currentModelParams));
                        }
                    }
                }
            }
        }
    }
}