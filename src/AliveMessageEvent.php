<?php

namespace Alive2212\LaravelMessageService;

use Illuminate\Database\Eloquent\Model;

class AliveMessageEvent extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messageProcesses()
    {
        return $this->hasMany(AliveMessageProcess::class,'event_id');
    }
}