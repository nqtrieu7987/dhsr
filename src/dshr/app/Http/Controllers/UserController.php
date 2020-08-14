<?php

namespace App\Http\Controllers;
use App\Models\JobType;
use App\Models\AllJob;
use App\Models\Job;
use App\Models\User;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Helper\VtHelper;
use Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Job::selectRaw("hotel_id, COUNT(*) AS 'tong'")->groupBy('hotel_id')->get();

        $jobsOngoing = AllJob::leftJoin('job', function($join) {
                $join->on('old_all_jobs.job_id', '=', 'job.id');
            })
            ->where('job.start_date','>=',DATE(NOW()))
            ->where('job.is_active', 1)
            ->orderBy('job.start_date', 'ASC')
            ->limit(20)
            ->get();

        $dataJobs = [];
        foreach ($jobsOngoing as $key => $value) {
            if(!isset($dataJobs[$value->Jobs()->Hotels()->name])){
                $dataJobs[$value->Jobs()->Hotels()->name] = [];
            }
            array_push($dataJobs[$value->Jobs()->Hotels()->name], $value);
        }
        $user = Auth::user();
        $link_url = ['url' => route('public.home'), 'title' => 'Ongoing Jobs', 'icon' =>'fa fa-refresh'];
        if ($user->isAdmin()) {
            return view('pages.admin.home', compact('datas','dataJobs','link_url'));
        }

        return view('pages.user.home');
    }
}
