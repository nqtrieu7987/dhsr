<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobType;
use Auth;
use Session;
use Carbon\Carbon;

class JobTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas= JobType::orderBy('created_at','DESC')->paginate(100);
        
        $link_url = ['url' => route('job-type.create'), 'title' => 'Add', 'icon' =>'fa fa-plus-circle'];
        return view('job-type.index',compact('datas','link_url'))
                ->with('site','Job Type');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $link_url = ['url' => route('job-type.index'), 'title' => 'Back', 'icon' =>'fa fa-reply'];
        return view('job-type.create', compact('link_url'))->with('site','Job Type');
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
        
        $active = isset($request->is_active) ? "1" : "0";
        
        $data = JobType::create([
            'name' => $request->name,
            'comment' => $request->comment,
            'is_active'  => $active,
            ]);

        Session::flash('success', 'Create success!');
        return redirect()->route('job-type.index');
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
        $data=JobType::find($id);
        $link_url = ['url' => route('job-type.index'), 'title' => 'Back', 'icon' =>'fa fa-reply'];
        return view('job-type.edit',compact('data','link_url'))->with('site','Job Type: '.$id);
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

        $data= JobType::find($id);

        $active = $request->is_active;
        JobType::find($id)->update([
            'name' => $request->name,
            'comment' => $request->comment,
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
        JobType::find($id)->delete();
        Session::flash('success', 'Delete successfully!');
        return redirect()->route('job-type.index');
    }
}
