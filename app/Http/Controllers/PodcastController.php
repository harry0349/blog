<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    /**
     * 保存新的播客.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // 创建新的播客
        // dispatch(new ProcessPodcast($podcast));
        
        $job = (new ProcessPodcast($pocast))
        			->delay(Carbon::now()->addMinutes(10));
        dispatch($job);
    }
}
