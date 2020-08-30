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

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hotels= Hotel::orderBy('created_at','DESC')->paginate(100);
        $link_url = ['url' => route('hotel.create'), 'title' => 'Add', 'icon' =>'fa fa-plus-circle'];
        return view('hotel.index',compact('hotels','link_url'))
                ->with('site','Hotel');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $link_url = ['url' => route('hotel.index'), 'title' => 'Back', 'icon' =>'fa fa-reply'];
        return view('hotel.create', compact('link_url'))->with('site','Hotel');
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
            'name' => 'required|max:255',
        ]);
        
        $image = '';
        $file_image = $request->file('image');
        if(strlen($file_image) > 0){
            $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
            $image = '/uploads/images/hotel/'.$timestamp.'-'.$file_image->getClientOriginalName();
            $file_image->move('uploads/images/hotel/', $image);
        }

        $logo = '';
        $file_logo = $request->file('logo');
        if(strlen($file_logo) > 0){
            $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
            $logo = '/uploads/images/hotel/'.$timestamp.'-'.$file_logo->getClientOriginalName();
            $file_logo->move('uploads/images/hotel/', $logo);
        }

        $active = isset($request->is_active) ? "1" : "0";
        
        $hotel = Hotel::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active'  => $active,
            'image' => $image,
            'logo' => $logo,
            ]);

        Session::flash('success', 'Create successfully!');
        return redirect()->route('hotel.index');
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
        $hotel=Hotel::find($id);

        $jobType = JobType::where('is_active', 1)->pluck('name', 'id')->toArray();
        $status = config('app.job_status');
        $color_status = config('app.color_status');

        $idPrev = Job::where('hotel_id', $id)->where('start_date', '<', now())->pluck('id')->toArray();
        $idOn = Job::where('hotel_id', $id)->where('start_date', '>=', now())->pluck('id')->toArray();
        $jobsPrev = AllJob::whereIn('job_id', $idPrev)->orderBy('timestamp', 'DESC')->paginate(20);
        $jobsOngoing = AllJob::whereIn('job_id', $idOn)->orderBy('timestamp', 'DESC')->paginate(20);

        $link_url = ['url' => route('hotel.index'), 'title' => 'Back', 'icon' =>'fa fa-reply'];
        return view('hotel.edit',compact('hotel','jobsPrev','jobsOngoing','jobType','status','link_url','color_status'))->with('site','Hotel: '.$id);
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
            'name' => 'required|max:255',
        ]);

        $hotel= Hotel::find($id);

        $image = $hotel->image;
        if($request->image !=null && $hotel->image != $request->image)
        {
            $file_image = $request->file('image');
            if(strlen($file_image) > 0){
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                $image = '/uploads/images/hotel/'.$timestamp.'-'.$file_image->getClientOriginalName();
                $file_image->move('uploads/images/hotel/', $image);
            }
        }

        $logo = $hotel->logo;
        if($request->logo !=null && $hotel->logo != $request->logo)
        {
            $file_logo = $request->file('logo');
            if(strlen($file_logo) > 0){
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                $logo = '/uploads/images/hotel/'.$timestamp.'-'.$file_logo->getClientOriginalName();
                $file_logo->move('uploads/images/hotel/', $logo);
            }
        }

        $active = $request->is_active;
        Hotel::find($id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => $image,
            'logo' => $logo,
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
        Hotel::find($id)->delete();
        return redirect()->back();
    }
}
