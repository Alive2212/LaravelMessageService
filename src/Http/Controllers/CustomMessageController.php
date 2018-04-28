<?php

namespace Alive2212\LaravelMessageService\Http\Controllers;

use Alive2212\LaravelMessageService\Http\Controllers\Controller;
use Alive2212\LaravelMessageService\Message;
use Illuminate\Http\Request;

class CustomMessageController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new Message();
        $this->middleware([
//            'auth:api',
        ]);
    }

    public function index(Request $request)
    {
        return "I am most powerful man in Dokhan";
//        $userId = auth()->id();
    }

    public function store(Request $request)
    {
//        $userId = auth()->id();
    }
}