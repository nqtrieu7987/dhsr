<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\AllJob;
use App\Models\Job;
use App\Models\JobType;
use Auth;
use Session;
use Carbon\Carbon;

class AjaxController extends Controller
{
    public function fetch_data(Request $request)
	{
		if($request->ajax())
		{
			$jobType = JobType::pluck('name', 'id')->toArray();
        	$status = config('app.job_status');
        	$color_status = config('app.color_status');

        	if($request->type == 'hotel'){
        		$idPrev = Job::where('hotel_id', $request->id)->where('start_date', '<', now())->pluck('id')->toArray();
	        	$data = AllJob::whereIn('job_id', $idPrev)->orderBy('timestamp', 'DESC')->paginate(20);
        	}elseif($request->type =='user'){
        		$data = AllJob::leftJoin('job', function($join) {
	                $join->on('all_jobs.job_id', '=', 'job.id');
	            })
	            ->where('all_jobs.user_id', $request->id)
	            ->where('job.start_date','<',date('Y-m-d'))
	            ->paginate(20);
        	}elseif($request->type =='job'){
        		$data = AllJob::where('job_id', $request->id)->orderBy('timestamp', 'DESC')->paginate(20);
        	}
			
	        //$idOn = Job::where('hotel_id', $request->id)->where('start_date', '>=', now())->pluck('id')->toArray();
	        //$jobsOngoing = AllJob::whereIn('job_id', $idOn)->orderBy('timestamp', 'DESC')->paginate(20);
	        $site = $request->site;
	        if($request->jobStatus == 1){
				return view('partials/pagination_status', compact('data','jobType','status','color_status','site'))->render();
	        }
	        return view('partials/pagination_data', compact('data','jobType','status','color_status','site'))->render();
		}
	}

	public function fetch_ongoing(Request $request)
	{
		if($request->ajax())
		{
			$jobType = JobType::pluck('name', 'id')->toArray();
        	$status = config('app.job_status');
        	$color_status = config('app.color_status');

        	if($request->type == 'hotel'){
        		$idPrev = Job::where('hotel_id', $request->id)->where('start_date', '>', now())->pluck('id')->toArray();
	        	$data = AllJob::whereIn('job_id', $idPrev)->orderBy('timestamp', 'DESC')->paginate(20);
        	}elseif($request->type =='user'){
        		$data = AllJob::leftJoin('job', function($join) {
	                $join->on('all_jobs.job_id', '=', 'job.id');
	            })
	            ->where('all_jobs.user_id', $request->id)
	            ->where('job.start_date','>=',date('Y-m-d'))
	            ->paginate(20);
        	}elseif($request->type =='job'){
        		$data = AllJob::where('job_id', $request->id)->orderBy('timestamp', 'DESC')->paginate(20);
        	}
	        $site = $request->site;
			return view('partials/pagination_ongoing', compact('data','jobType','status','color_status','site'))->render();
		}
	}
}
