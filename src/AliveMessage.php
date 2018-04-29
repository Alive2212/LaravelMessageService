<?php

namespace Alive2212\LaravelMessageService;

use Illuminate\Database\Eloquent\Model;

class AliveMessage extends Model
{
    protected $fillable = [
        'from',
        'to',
        'body',
        'invisible',
        'process_id',
    ];

    public function messageProcess()
    {
        return $this->belongsTo(AliveMessageProcess::class);
    }
}
