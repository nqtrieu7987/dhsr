<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clocking;
use App\Models\Job;
use App\Models\AllJob;
use App\Models\JobType;
use App\Models\Hotel;
use App\Models\User;
use App\Models\Admin;
use App\Exports\JobExport;
use Illuminate\Support\Facades\Log;
use Auth;
use Session, Excel;
use Carbon\Carbon;
use DB;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $status = config('app.job_status');
        $view_type = config('app.view_type');
        $color_status = config('app.color_status');
        $site = 'Admin';
        $hotel = null;
        $data = null;
        if(Auth::user()->hotel_id > 0){
            $hotel = Hotel::findOrFail(Auth::user()->hotel_id);
            $data = AllJob::whereIn('job_id', function($query)
                        {
                            $query->select(DB::raw('id'))
                                  ->from('job')
                                  ->where('hotel_id', Auth::user()->hotel_id);
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
        }


        $link_url = ['url' => route('job.index'), 'title' => 'Back', 'icon' =>'fa fa-reply'];
        if($hotel){
            $site = 'Hotel '.$hotel->name;
        }

        return view('admin.home', compact('status','view_type','data','link_url','color_status'))->with('site', $site);
    }

    public function reportJob(Request $request){
        $check_search = false;
        $jobs = Job::select('id');
        $status = config('app.job_status');
        $jobType = JobType::where('is_active', 1)->pluck('name', 'id')->toArray();
        $jobType[0] ='Select Job';
        ksort($jobType);

        $hotels = Hotel::where('is_active', 1)->pluck('name', 'id')->toArray();
        $hotels[0] ='Select Hotel';
        ksort($hotels);
        $view_type = config('app.view_type');
        $color_status = config('app.color_status');

        $datas = null;

        if(Auth::user()->hotel_id > 0){
            $hotel = Hotel::findOrFail(Auth::user()->hotel_id);
            //search
            $jobs = Job::select('*');
            $jobs = $jobs->where('hotel_id', Auth::user()->hotel_id);

            if($request->start_date != ''){
                $start_date = Carbon::createFromFormat('d/m/Y', $request->get('start_date'))->startOfDay();
                $start_date = date_format($start_date, "Y-m-d");
                $jobs = $jobs->where('start_date', $start_date);
            }
            $listIds = $jobs->get();
            $ids =[];
            if(count($listIds) > 0){
                foreach ($listIds as $key => $value) {
                    $ids[] = $value['id'];
                }
            }

            $datas = null;
            if(count($ids) > 0){
                //$datas = AllJob::whereIn('job_id', $ids)->paginate(30);
                $datas = AllJob::leftJoin('job', function($join) {
                    $join->on('all_jobs.job_id', '=', 'job.id');
                })
                ->select('all_jobs.*', 'job.job_type_id', 'job.hotel_id')
                ->whereIn('all_jobs.job_id', $ids)
                ->orderBy('updated_at', 'DESC')
                ->paginate(20);
            }
            $attentions = [];
            $pendings = [];
            $approveds = [];
            if($datas){
                foreach ($datas as $key => $value) {
                    if($value->rwsConfirmed != null){
                        array_push($approveds, $value);
                    }else{
                        if($value->real_start != '' && $value->real_end != ''){
                            array_push($pendings, $value);
                        }else{
                            array_push($attentions, $value);
                        }
                    }
                }
            }

            $check_search = true;
            
            if($request->submit == 'export' && $datas != null){
                foreach ($datas as $k => $v) {
                    $data_export[] = [
                        'No' => $k+1,
                        'Date Of Work' => date('Y-m-d H:i:s', $v['timestamp']/1000),
                        'Name' => $v->users->name,
                        'Exp' => $v->users->jobsDone > 0 ? 'Yes' : 'No',
                        'Sex' => $v->users->gender = 1 ? 'M' : 'F',
                        'Feedback' => $v->users->feedback,
                        'IC/FIN No.' => $v->users->userNRIC,
                        'Shift' => $v->jobs->start_time.' - '.$v->jobs->end_time,
                        'Actual Time In' => $v['real_start'],
                        'Signature' => '',
                        'Meal Break' => $v['breakTime'],
                        'Actual Time Out' => $v['real_end'],
                        'Signature ' => '',
                        'Start Time' => $v['paidTimeIn'],
                        'End Time' => $v['paidTimeOut'],
                        'Meal Break ' => $v['breakTime'],
                        'Total Hours' => $v['totalHours'],
                        'In-Charge Signature' => '',
                    ];
                }
                $timestamp = date('Y') . date('m') . date('d');

                if($request->job > 0){
                    $job = JobType::findOrFail($request->job);
                }else{
                    return redirect()->back()->withErrors(['job'=> 'You must select job.']);
                }

                return Excel::download(new JobExport($data_export, $hotel, $job), $hotel->name.' ('.$job->name.') '.date('Y-m-d', time()).'.xlsx');
            }
        }

        return view('job.hotel-attendance',compact('datas','status','jobType','hotels','check_search','attentions','pendings','approveds'))
                ->with('site','Hotel Attendance');
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
                        $body = array('email' => $data->users->email,'status' => 5,'job_name' => $data->jobs->types->name,'hotel_name' => $data->jobs->hotels->name);
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
            /*if(file_exists(public_path().$data->userPants) && public_path().$data->userPants != public_path()){
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
            ]);*/
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

        $details = [
            'status' => $status,
            'body' => 'Job: '.$data->jobs->types->name.', Hotel: '.$data->jobs->hotels->name. '<br> Start time: '.$data->paidTimeIn. ', End time: '.$data->paidTimeOut. ', Break time: '.$data->breakTime. 'hours, Total: '.$data->totalHours.' hours, Remarks'.$data->remarks
        ];
        \Mail::to($user->email)->queue(new \App\Mail\SendMail($details));
        
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

    public function clocking(Request $request){
        $datas = Clocking::whereIn('job_id', function($query)
                    {
                        $query->select(DB::raw('id'))
                              ->from('job')
                              ->where('hotel_id', Auth::user()->hotel_id);
                    })
        ->orderBy('created_at', 'DESC')->orderBy('user_id', 'DESC')->paginate(50);

        $jobType = JobType::pluck('name', 'id')->toArray();
        $hotels = Hotel::pluck('name', 'id')->toArray();
        return view('admin.clocking', compact('datas','jobType','hotels'))
                    ->with('site', 'Clocking');
    }

    public function changeUpdateStatus(Request $request){
        $data = AllJob::findOrFail($request->id);
        if($request->type == 'approve'){
            $status = 1;
            Log::info($data->jobs->slot.' - '.$data->jobs->current_slot);
            if($data->jobs->current_slot >= $data->jobs->slot){
                return response()->json([
                    'msg' => 'Job full slot',
                    'status' => 201
                ]);
            }else{
                // Cập nhật current_slot của job lên 1 đơn vị
                if(in_array($data->status, [0,4,5])){
                    $data->status = $status;
                    $data->jobs->update(['current_slot' => $data->jobs->current_slot + 1]);
                    $msg = 'Approve Successfully!';
                    $stt = 200;

                    //Push notify approved job
                    $body = array('email' => $data->users->email,'status' => 1,'job_name' => $data->jobs->types->name,'hotel_name' => $data->jobs->hotels->name);
                    try {
                        $res = config('app.service')->post('user/notify_job_status', [
                            'form_params' => $body
                        ]);
                    } catch (\GuzzleHttp\Exception\ClientException $e) {}
                }else{
                    $msg = 'Failed!';
                    $stt = 203;
                }
            }
        }else{
            $status = 4;
            if(in_array($data->status, [1,2,3]) && $data->jobs->current_slot > 0){
                $data->jobs->update(['current_slot' => $data->jobs->current_slot - 1]);
            }
            $data->status = $status;
            $msg = 'Cancel Successfully!';
            $stt = 202;

            /*$data->userPants = null;
            $data->userShoes = null;
            $data->userPantsApproved = 0;
            $data->userShoesApproved = 0;*/

            //Push notify cancel job
            if($data->status != 4){
                $body = array('email' => $data->users->email,'status' => 4,'job_name' => $data->jobs->types->name,'hotel_name' => $data->jobs->hotels->name);
                try {
                    $res = config('app.service')->post('user/notify_job_status', [
                        'form_params' => $body
                    ]);
                } catch (\GuzzleHttp\Exception\ClientException $e) {}
            }
        }
        $data->save();
        return response()->json([
            'msg' => $msg,
            'status' => $stt
        ]);
    }
}
