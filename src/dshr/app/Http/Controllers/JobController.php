<?php

namespace App\Http\Controllers;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\AllJob;
use App\Models\JobType;
use App\Models\Hotel;
use App\Models\User;
use App\Models\Adminusers;
use App\Exports\JobExport;
use Auth;
use Session, Excel;
use Carbon\Carbon;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jobs = Job::with('hotels', 'types');
        if($request->hotel_id > 0){
            $jobs = $jobs->where('hotel_id', $request->hotel_id);
            $check_search = true;
        }
        if($request->job > 0){
            $jobs = $jobs->where('job_type_id', $request->job);
            $check_search = true;
        }
        if($request->slot > 0){
            $jobs = $jobs->where('slot', $request->slot);
            $check_search = true;
        }
        $datas= $jobs->orderBy('created_at','DESC')->paginate(50);

        $types = ['Select Job'] + JobType::pluck('name', 'id')->toArray();
        
        $hotels = ['Select Hotel'] + Hotel::pluck('name', 'id')->toArray();

        $slots = ['Slot'] + Job::pluck('slot', 'slot')->toArray();
        
        $view_type = config('app.view_type');

        $link_url = ['url' => route('job.createMulti'), 'title' => 'Adds', 'icon' =>'fa fa-plus-circle'];

        return view('job.index',compact('datas','types','hotels','view_type','slots','link_url'))
                ->with('site','Job');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = JobType::active()->pluck('name', 'id')->toArray();
        $hotels = Hotel::active()->pluck('name', 'id')->toArray();
        $view_type = config('app.view_type');

        $link_url = ['url' => route('job.index'), 'title' => 'Back', 'icon' =>'fa fa-reply'];
        return view('job.create', compact('types','hotels','view_type','link_url'))->with('site','Job');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'slot' => 'required|numeric',
            'start_time' => 'required',
            'end_time' => 'required',
            'start_date' => 'required'
        ]);
        
        $active = isset($request->is_active) ? "1" : "0";
        $data = Job::create([
            'hotel_id' => $request->hotel_id,
            'job_type_id' => $request->job_type_id,
            'slot' => $request->slot,
            'start_time' => date("H:i", strtotime($request->start_time)),
            'end_time' => date("H:i", strtotime($request->end_time)),
            'start_date' => date('Y-m-d', strtotime($request->start_date)),
            'view_type' => $request->view_type,
            'is_active'  => $active,
            ]);

        Session::flash('success', 'Create successfully!');
        return redirect()->route('job.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=Job::findOrFail($id);
        $jobType = JobType::active()->pluck('name', 'id')->toArray();
        $status = config('app.job_status');
        $hotels = Hotel::active()->pluck('name', 'id')->toArray();
        $view_type = config('app.view_type');
        $color_status = config('app.color_status');

        $jobsPrev = AllJob::with('jobs', 'users')->where('job_id', $id)->orderBy('status', 'ASC')->orderBy('timestamp', 'DESC')->paginate(20);

        $link_url = ['url' => route('job.index'), 'title' => 'Back', 'icon' =>'fa fa-reply'];
        return view('job.edit',compact('data','jobType','status','hotels','view_type','jobsPrev','link_url','color_status'))->with('site','Job: '.$id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'slot' => 'required|numeric',
            'start_time' => 'required',
            'end_time' => 'required',
            'start_date' => 'required'
        ]);

        $data = Job::findOrFail($id);

        $active = $request->is_active;
        $data->update([
            'hotel_id' => $request->hotel_id,
            'job_type_id' => $request->job_type_id,
            'slot' => $request->slot,
            'start_time' => date("H:i", strtotime($request->start_time)),
            'end_time' => date("H:i", strtotime($request->end_time)),
            'start_date' => date('Y-m-d', strtotime($request->start_date)),
            'view_type' => $request->view_type,
            'is_active'  => $active,
        ]);

        Session::flash('success', 'Update success!');
        return back()->with('success', 'Update success!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /*Job::findOrFail($id)->delete();
        Session::flash('success', 'Delete successfully!');
        return redirect()->route('job.index');*/
    }

    public function AllJobs(Request $request){
        $jobType = JobType::pluck('name', 'id')->toArray();
        $status = config('app.job_status');
        $color_status = config('app.color_status');

        $hotels = ['Select Hotel'] + Hotel::pluck('name', 'id')->toArray();

        //search
        $jobs = Job::select('*');
        if($request->hotel_id > 0){
            $jobs = $jobs->where('hotel_id', $request->hotel_id);
        }
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
            ->with(['users', 'jobs' => function($jobs){$jobs->with('hotels','types');}])
            ->whereIn('all_jobs.job_id', $ids)
            ->orderBy('status', 'ASC')
            ->orderBy('id', 'DESC')
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

        $view_type = config('app.view_type');

        return view('job.all-jobs-news',compact('datas','status','color_status','jobType','hotels','view_type','attentions','pendings','approveds'))
                ->with('site','All Job');
    }

    public function approved(Request $request, $id){
        $data = AllJob::findOrFail($id);
        $data->status = 1;
        $data->remarks = $request->remarks;
        $data->save();
        Session::flash('success', 'Confirm success!');
        return redirect()->route('job.all-jobs');
    }

    public function reportJob(Request $request){
        $check_search = false;
        $jobs = Job::select('id');
        if($request->hotel_id > 0){
            $jobs = $jobs->where('hotel_id', $request->hotel_id);
            $check_search = true;
            $hotel = Hotel::findOrFail($request->hotel_id);
        }
        if($request->job > 0){
            $jobs = $jobs->where('job_type_id', $request->job);
            $check_search = true;
            $job = JobType::findOrFail($request->job);
        }
        if($request->start_date != ''){
            $start_date = Carbon::createFromFormat('d/m/Y', $request->start_date)->toDateString();
            $jobs = $jobs->where('start_date', $start_date);
            $check_search = true;
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
            $datas = AllJob::with('jobs', 'users')->whereIn('job_id', $ids)->get();
        }

        $jobType = ['Select Job'] + JobType::active()->pluck('name', 'id')->toArray();

        $hotels = ['Select Hotel'] + Hotel::active()->pluck('name', 'id')->toArray();

        $status = config('app.job_status');
        $colors = config('app.color_status');

        if($request->submit == 'export' && $datas != null){
            $data_export = [];
            foreach ($datas as $k => $v) {
                $data_export[] = [
                    'No' => $k+1,
                    'Date Of Work' => date('Y-m-d H:i:s', $v['timestamp']/1000),
                    'Name' => $v->users->userName,
                    'Exp' => $v['status'] == 3 ? 'Yes' : 'No',
                    'Sex' => $v->users->userGender == 1 ? 'M' : 'F',
                    'Feedback' => $v['remarks'],
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

            return Excel::download(new JobExport($data_export, $hotel, $job), $hotel->name.' ('.$job->name.') '.date('Y-m-d', time()).'.xlsx');
        }

        return view('job.report-job',compact('datas','status','colors','jobType','hotels','check_search'))
                ->with('site','Hotel Attendance');
    }

    public function exportExcel(Request $request){
        $hotels = null;
        $jobs = Job::select('*');
        if($request->hotel_id > 0){
            $jobs = $jobs->where('hotel_id', $request->hotel_id);
            $hotels = Hotel::findOrFail($request->hotel_id);
        }
        if($request->job > 0){
            $jobs = $jobs->where('job_type_id', $request->job);
            $job = JobType::findOrFail($request->job);
        }else{
            return redirect()->route('report.job');
        }
        if($request->start_date != ''){
            $start_date = Carbon::createFromFormat('d/m/Y', $request->start_date)->toDateString();
            $jobs = $jobs->where('start_date', $start_date);
        }
        $listIds = $jobs->get();
        $ids =[];
        if(count($listIds) > 0){
            foreach ($listIds as $key => $value) {
                $ids[] = $value['id'];
            }
        }else{
            return redirect()->route('report.job');
        }

        $datas = null;
        if(count($ids) > 0){
            $datas = AllJob::whereIn('job_id', $ids)->get();
        }

        if ($datas != null) {
            foreach ($datas as $k => $v) {
                $data_export[] = [
                    'No' => $k+1,
                    'Date Of Work' => date('Y-m-d H:i:s', $v['timestamp']/1000),
                    'Name' => $v->users->userName,
                    'Exp' => $v->users->jobsDone > 0 ? 'Yes' : 'No',
                    'Sex' => $v->users->userGender = 1 ? 'M' : 'F',
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

            return Excel::download(new JobExport($data_export, $hotels, $job), $hotels->name.' ('.$job->name.') '.date('Y-m-d', time()).'.xlsx');
        } else {
            return redirect()->back()->with('errors', 'No data available export!');
        }
    }

    public function createMulti()
    {
        $types = JobType::active()->get();
        $hotels = Hotel::active()->get();
        $view_type = config('app.view_type');
        $view_type = collect($view_type);

        $link_url = ['url' => route('job.index'), 'title' => 'Back', 'icon' =>'fa fa-reply'];
        return view('job.create-multi', compact('types','hotels','view_type','link_url'))->with('site','Job');
    }

    public function createMultiPost(Request $request){
        $data = Job::create([
            'hotel_id' => $request->hotel['id'],
            'job_type_id' => $request->type['id'],
            'view_type' => $request->view['value'],
            'slot' => $request->slot,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'start_date' => $request->start_date,
            'is_active'  => 1,
        ]);
        //Session::flash('success', 'Create successfully!');
        return response()->json([
            'id' => $data->id,
            'msg' => 'Create successfully'
        ]);

        /*$start_date = Carbon::createFromFormat('d/m/Y', $request->start_date)->toDateString();
        $start_time = str_replace(" ", "", $request->get('start_time'));
        $end_time = str_replace(" ", "", $request->get('end_time'));
        $msg = 'Create successfully!';
        if($request->get('id') > 0){
            $data = Job::findOrFail($request->get('id'));
            if($data){
                $data->update([
                    'hotel_id' => $request->get('hotel_id'),
                    'job_type_id' => $request->get('job_type_id'),
                    'slot' => $request->get('slot'),
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'start_date' => $start_date,
                    'view_type' => $request->get('view_type'),
                    'is_active'  => 1
                ]);
                $msg = 'Update successfully!';
            }
        }else{
            $data = Job::create([
                'hotel_id' => $request->get('hotel_id'),
                'job_type_id' => $request->get('job_type_id'),
                'slot' => $request->get('slot'),
                'start_time' => $start_time,
                'end_time' => $end_time,
                'start_date' => $start_date,
                'view_type' => $request->get('view_type'),
                'is_active'  => 1,
            ]);
        }

        Session::flash('success', 'Create successfully!');
        return response()->json([
            'id' => $data->id,
            'msg' => $msg
        ]);
        */
    }

    public function inOut(Request $request, $id){
        $data = AllJob::findOrFail($id);
        $link_url = ['url' => route('job.edit', $data->job_id), 'title' => 'Back', 'icon' =>'fa fa-reply'];
        $status = [1 =>'Job Done', 2 =>'Job Failure'];
        return view('job.in-out',compact('data','link_url','status'))->with('site','Job');
    }

    public function inOutPost(Request $request, $id){
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

        $data = AllJob::findOrFail($id);
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
        /*if(file_exists(public_path().$data->userPants) && $data->userPants != ''){
            unlink(public_path().$data->userPants);
            $thumb = str_replace('.png', '_thumb.png', $data->userPants);
            if(file_exists(public_path().$thumb)){
                unlink(public_path().$thumb);
            }
        }
        if(file_exists(public_path().$data->userShoes) && $data->userShoes != ''){
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

        $data->update([
            'real_start' => $real_start,
            'real_end' => $real_end,
            'paidTimeIn' => $paidTimeIn,
            'paidTimeOut' => $paidTimeOut,
            'breakTime' => $request->get('breakTime'),
            'totalHours' => $totalHours,
            'timestamp' => time()*1000,
            'workTime_confirmed' => 1,
            'rwsConfirmed' => $request->status,
            'remarks' => $request->get('remarks'),
        ]);
        
        $details = [
            'status' => $data->rwsConfirmed,
            'body' => 'Job: '.$data->jobs->types->name.', Hotel: '.$data->jobs->hotels->name. '<br> Start time: '.$data->paidTimeIn. ', End time: '.$data->paidTimeOut. ', Break time: '.$data->breakTime. 'hours, Total: '.$data->totalHours.' hours, Remarks'.$data->remarks
        ];

        \Mail::to($user->email)->queue(new \App\Mail\SendMail($details));

        \Log::channel('inOut')->info("User: ".Auth::user()->id. " data=". json_encode($data));

        Session::flash('success', 'Confirm success!');
        return redirect()->back();
    }

    public function timeDiff($firstTime,$lastTime) {
        $firstTime=strtotime($firstTime);
        $lastTime=strtotime($lastTime);
        $timeDiff=$lastTime-$firstTime;
        return $timeDiff;
    }
}
