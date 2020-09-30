<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\AllJob;
use App\Models\JobType;
use App\Models\Hotel;
use App\Models\User;
use App\Models\Admin;
use App\Exports\JobExport;
use Auth;
use Session, Excel;
use Carbon\Carbon;

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
        return view('admin.home');
    }

    public function reportJob(Request $request){
        $check_search = false;
        $jobs = Job::select('id');
        if($request->hotel_id > 0){
            $jobs = $jobs->where('hotel_id', $request->hotel_id);
            $check_search = true;
            $hotel = Hotel::find($request->hotel_id);
        }
        if($request->job > 0){
            $jobs = $jobs->where('job_type_id', $request->job);
            $check_search = true;
            $job = JobType::find($request->job);
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
            $datas = AllJob::whereIn('job_id', $ids)->get();
        }

        $jobType = JobType::where('is_active', 1)->pluck('name', 'id')->toArray();
        $jobType[0] ='Select Job';
        ksort($jobType);

        $hotels = Hotel::where('is_active', 1)->pluck('name', 'id')->toArray();
        $hotels[0] ='Select Hotel';
        ksort($hotels);

        $status = config('app.job_status');
        if($request->submit == 'export' && $datas != null){
            foreach ($datas as $k => $v) {
                $data_export[] = [
                    'No' => $k+1,
                    'Date Of Work' => date('Y-m-d H:i:s', $v['timestamp']/1000),
                    'Name' => $v->Users()['name'],
                    'Exp' => $v->Users()['jobsDone'] > 0 ? 'Yes' : 'No',
                    'Sex' => $v->Users()['gender'] = 1 ? 'M' : 'F',
                    'Feedback' => $v->Users()['feedback'],
                    'IC/FIN No.' => $v->Users()['userNRIC'],
                    'Shift' => $v->Jobs()['start_time'].' - '.$v->Jobs()['end_time'],
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

        return view('job.report-job',compact('datas','status','jobType','hotels','check_search'))
                ->with('site','Hotel Attendance');
    }

}
