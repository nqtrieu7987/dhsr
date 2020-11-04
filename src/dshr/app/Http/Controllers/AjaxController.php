<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\AllJob;
use App\Models\Job;
use App\Models\User;
use App\Models\JobType;
use Auth;
use Session;
use Carbon\Carbon;
use Log;

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
	        	$data = AllJob::whereIn('job_id', $idPrev)->orderBy('status', 'ASC')->orderBy('id', 'DESC')->paginate(20);
        	}elseif($request->type =='user'){
        		$data = AllJob::leftJoin('job', function($join) {
	                $join->on('all_jobs.job_id', '=', 'job.id');
	            })
	            ->where('all_jobs.user_id', $request->id)
	            ->where('job.start_date','<',date('Y-m-d'))
	            ->paginate(20);
        	}elseif($request->type =='job'){
        		$data = AllJob::where('job_id', $request->id)->orderBy('status', 'ASC')->orderBy('id', 'DESC')->paginate(20);
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
	        	$data = AllJob::whereIn('job_id', $idPrev)->orderBy('id', 'DESC')->paginate(20);
        	}elseif($request->type =='user'){
        		$data = AllJob::leftJoin('job', function($join) {
	                $join->on('all_jobs.job_id', '=', 'job.id');
	            })
	            ->where('all_jobs.user_id', $request->id)
	            ->where('job.start_date','>=',date('Y-m-d'))
	            ->paginate(20);
        	}elseif($request->type =='job'){
        		$data = AllJob::where('job_id', $request->id)->orderBy('id', 'DESC')->paginate(20);
        	}
	        $site = $request->site;
			return view('partials/pagination_ongoing', compact('data','jobType','status','color_status','site'))->render();
		}
	}

	public function inOutPost(Request $request){
        $real_start = date("H:i", strtotime($request->get('real_start')));
        $real_end = date("H:i", strtotime($request->get('real_end')));
        $paidTimeIn = $request->get('paidTimeIn');
        $paidTimeOut = $request->get('paidTimeOut');
        if($paidTimeIn == ""){
            $paidTimeIn = $real_start;
        }else{
            $paidTimeIn = date("H:i", strtotime($request->get('paidTimeIn')));
        }
        if($paidTimeOut == ""){
            $paidTimeOut = $real_end;
        }else{
            $paidTimeOut = date("H:i", strtotime($request->get('paidTimeOut')));
        }

        $timeDiff = $this->timeDiff($paidTimeIn, $paidTimeOut);
        if($timeDiff < 0){
            $time1 = $this->timeDiff($paidTimeIn, '24:00');
            $time2 = $this->timeDiff('00:00', $paidTimeOut);
            $totalHours = round(($time1 + $time2)/3600, 2) - $request->get('breakTime');
        }else{
            $totalHours = round($timeDiff/3600, 2) - $request->get('breakTime');
        }

        $data = AllJob::findOrFail($request->get('id'));
        //Neu bam approved => type = 2
        $status = null;
        if($request->type == 2){
            $user = User::findOrFail($data->user_id);
            // Chỉ khi chưa confirm lần nào và status = 1 mới tăng jobsDone trong user lên 1 đơn vị
            if($data->rwsConfirmed != 1){
                if($request->status == 1){
                    $user->update(['jobsDone' => $user->jobsDone + 1]);
                    // Cập nhật trạng thái cho job: 3: Complete, 5 Fail
                    $data->update(['status' => 3]);
                }else{
                    //Push notify fail job
                    if($data->status != 5){
                        $data->update(['status' => 5]);
                        $body = array('email' => $data->Users()->email,'status' => 5,'job_name' => $data->Jobs()->Types()->name,'hotel_name' => $data->Jobs()->Hotels()->name);
                        try {
                            $res = config('app.service')->post('user/notify_job_status', [
                                'form_params' => $body
                            ]);
                        } catch (\GuzzleHttp\Exception\ClientException $e) {}
                    }
                }
            }
            // Khi commit job done, failure, cancel => set 2 thuộc tính userPantsApproved, userShoesApproved về false 
            // Nếu user đã được phê duyệt cả Pants và Shoes thì set userPants, userShoes = null
            if(file_exists(public_path().$data->userPants) && public_path().$data->userPants != public_path()){
                unlink(public_path().$data->userPants);
                $thumb = str_replace('.png', '_thumb.png', $data->userPants);
                if(file_exists(public_path().$thumb)){
                    unlink(public_path().$thumb);
                }
            }
            if(file_exists(public_path().$data->userShoes) && public_path().$data->userShoes != public_path()){
                unlink(public_path().$data->userShoes);
                $thumb = str_replace('.png', '_thumb.png', $data->userShoes);
                if(file_exists(public_path().$thumb)){
                    unlink(public_path().$thumb);
                }
            }
            $user->update([
                'userPants' => null,
                'userShoes' => null,
                'userPantsApproved' => 0,
                'userShoesApproved' => 0,
            ]);
            $status = $request->status;
        }else{
            $real_start = $data->real_start != '' ? $data->real_start : $paidTimeIn;
            $real_end = $data->real_end != '' ? $data->real_end : $paidTimeOut;
        }
        $data->update([
            'real_start' => $real_start,
            'real_end' => $real_end,
            'paidTimeIn' => $paidTimeIn,
            'paidTimeOut' => $paidTimeOut,
            'breakTime' => $request->get('breakTime'),
            'totalHours' => $totalHours,
            'timestamp' => time()*1000,
            'workTime_confirmed' => 1,
            'rwsConfirmed' => $status,
            'remarks' => $request->get('remarks'),
        ]);
        
        //Push notify job
        $body = array('email' => $data->Users()->email,'status' => $data->status,'job_name' => $data->Jobs()->Types()->name,'hotel_name' => $data->Jobs()->Hotels()->name);
        try {
            $res = config('app.service')->post('user/notify_job_status', [
                'form_params' => $body
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {}

        \Log::channel('inOut')->info("User: ".Auth::user()->id. " data=". json_encode($data));

        Session::flash('success', 'Confirm success!');
        return response()->json([
            'msg' => 'Update success',
            'id' => $data->id,
            'totalHours' => $totalHours,
            'type' => $request->type,
        ]);
    }

    public function timeDiff($firstTime,$lastTime) {
        $firstTime=strtotime($firstTime);
        $lastTime=strtotime($lastTime);
        $timeDiff=$lastTime-$firstTime;
        return $timeDiff;
    }
}
