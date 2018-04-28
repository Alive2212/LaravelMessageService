<?php

namespace Alive2212\LaravelMessageService;

use Illuminate\Database\Eloquent\Model;

class AliveMessageProcess extends Model
{
    public function messageEvent()
    {
        return $this->belongsTo(AliveMessageEvent::class);
    }

    public function messages()
    {
        return $this->hasMany(AliveMessageEvent::class, 'process_id');
    }
}
