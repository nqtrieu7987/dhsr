<?php

namespace App\Exports;

use App\Models\AllJob;
use App\Models\Job;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class JobExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $data;
    public function __construct($data, $hotel, $job)
    {
        $this->data = $data;
        $this->hotel = $hotel;
        $this->job = $job;
    }

    public function view(): View
    {
        return view('exports.job', [
            'data' => $this->data,
            'hotel' => $this->hotel,
            'job' => $this->job,
        ]);
    }

    public function collection()
    {
    	$jobs = Job::select('id');
        if($this->hotel_id > 0){
            $jobs = $jobs->where('hotel_id', $this->hotel_id);
            $check_search = true;
        }
        if($this->job > 0){
            $jobs = $jobs->where('job_type_id', $this->job);
            $check_search = true;
        }
        if($this->start_date > 0){
            $jobs = $jobs->where('start_date', $this->start_date);
            $check_search = true;
        }
        $listIds = $jobs->get();

        $ids =[];
        foreach ($listIds as $key => $value) {
            $ids[] = $value['id'];
        }

        return AllJob::whereIn('job_id', $ids)->get();
    }
}
