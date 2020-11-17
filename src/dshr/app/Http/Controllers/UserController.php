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
        $jobsOngoing = AllJob::leftJoin('job', function($join) {
                $join->on('all_jobs.job_id', '=', 'job.id');
            })
            ->with('jobs', 'users')
            ->where('job.start_date','>=', date('Y-m-d'))
            ->where('job.is_active', 1)
            ->orderBy('job.start_date', 'ASC')
            ->limit(20)
            ->get([
            'all_jobs.*', //to get ids and timestamps
            'job.job_type_id as job_type_id',
            'job.hotel_id as hotel_id',
            'job.slot as slot',
        ]);
        $dataJobs = [];
        foreach ($jobsOngoing as $key => $value) {
            if(!isset($dataJobs[$value->jobs->hotels->name])){
                $dataJobs[$value->jobs->hotels->name] = [];
            }
            array_push($dataJobs[$value->jobs->hotels->name], $value);
        }
        $user = Auth::user();
        $link_url = ['url' => route('public.home'), 'title' => 'Ongoing Jobs', 'icon' =>'fa fa-refresh'];
        if ($user->isAdmin()) {
            return view('pages.admin.home', compact('dataJobs','link_url'));
        }

        return view('pages.user.home');
    }
}
