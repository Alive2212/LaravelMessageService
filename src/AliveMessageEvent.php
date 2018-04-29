<?php

namespace Alive2212\LaravelMessageService;

use Illuminate\Database\Eloquent\Model;

class AliveMessageEvent extends Model
{
    protected $fillable = [
        'model',
        'type',
        'rules',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messageProcesses()
    {
        return $this->hasMany(AliveMessageProcess::class,'event_id');
    }
}