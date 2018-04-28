<?php

namespace Alive2212\LaravelMessageService;

use Illuminate\Database\Eloquent\Model;

class AliveMessage extends Model
{
    public function messageProcess()
    {
        return $this->belongsTo(AliveMessageProcess::class);
    }
}
