<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TestController extends CommonController
{
    public function test1()
    {
    	Redis::set('hello', 'world and harry');
    	echo Redis::get('hello');
    }
}
