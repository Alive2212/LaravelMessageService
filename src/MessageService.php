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
use Carbon\Carbon;

class MessageService
{
    public function handle($model, $eventType)
    {
        // get model class name
        $modelClassName = get_class($model);
        $event = new AliveMessageEvent();
        $event = $event->where([
            ['model', '=', $modelClassName],
        ])->with('messageProcesses');
        $eventParams = $event->get()->toArray();
        if (count($eventParams)) {
            foreach ($eventParams as $eventParam) {
                // handle available_at update and delete record
                $dirty = $model->getDirty();
                if (isset($dirty['available_at'])) {
                    switch ($eventType) {
                        // TODO more performance need

                        case 'Updating':
                            $this->deleteJobByModel($model);
                            break;
                        case 'Deleting':
                            $this->deleteJobByModel($model);
                            break;
                    }
                }


                if ($eventParam['type'] == $eventType) {
                    $eventRules = json_decode($eventParam['rules'], true);
                    $whereParams = $this->eventRulesParser($eventRules, $model->id);
                    if (count($whereParams)) {
                        $currentModel = (new $modelClassName())
                            ->where($whereParams);
                        $currentModelParams = $currentModel->get()->toArray()[0];
                    } else {
                        $currentModelParams = $model->toArray();
                    }
                    if (count($currentModelParams)) {
                        // this place is where process must be done
                        $processes = $eventParam['message_processes'];
                        $this->jobDispatcher($processes, $currentModelParams);
                    }
                }
            }
        }
    }

    /**
     * @param $processes
     * @param $currentModelParams
     */
    public function jobDispatcher($processes, $currentModelParams)
    {
        foreach ($processes as $process) {
            switch ($process['type']) {
                case "Sms":
                    dispatch(new MessageServiceSmsJob($process, $currentModelParams));
                    break;

                case "Notification":
                    $launchTimeKey = $process['launch_time'];
                    if (!is_null($launchTimeKey)) {
                        $launchDateTimeTimeStamp = $currentModelParams[$launchTimeKey];
                        $launchDateTime = Carbon::createFromTimestamp($launchDateTimeTimeStamp);
                        dispatch((new MessageServiceNotificationJob($process, $currentModelParams))->delay($launchDateTime));
                    } else {
                        dispatch(new MessageServiceNotificationJob($process, $currentModelParams));
                    }
                    break;

                case "Social":
                    dispatch(new MessageServiceSocialJob($process, $currentModelParams));
                    break;

                case "Email":
                    dispatch(new MessageServiceEmailJob($process, $currentModelParams));
                    break;

                default:
                    dispatch(new MessageServiceScopeJob($process, $currentModelParams));
            }
        }
    }

    /**
     * @param $eventRules
     * @param $modelId
     * @return mixed
     */
    public function eventRulesParser($eventRules, $modelId)
    {
        $whereParams = [];

        // add id after created variable
        if (!is_null($modelId)) {
            array_push($whereParams, ['id', '=', $modelId]);
        }


        if (is_array($eventRules)) {
            foreach ($eventRules as $eventRule) {
                // TODO create multiple deep where
                array_push($whereParams, [$eventRule[0], $eventRule[1], $eventRule[2]]);
            }
        }
        return $whereParams;
    }

    /**
     * @param $model
     */
    public function deleteJobByModel($model)
    {
        $job = \DB::table('jobs')->where('available_at', $model->getOriginal('available_at'))->delete();
    }
}