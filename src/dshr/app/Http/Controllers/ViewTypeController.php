<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ViewType;
use App\Models\AllJob;
use App\Models\Job;
use App\Models\JobType;
use Auth;
use Session;
use Carbon\Carbon;

class ViewTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas= ViewType::orderBy('created_at','DESC')->paginate(100);
        $link_url = ['url' => route('view-type.create'), 'title' => 'Add', 'icon' =>'fa fa-plus-circle'];
        return view('view-type.index',compact('datas','link_url'))
                ->with('site','ViewType');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $link_url = ['url' => route('view-type.index'), 'title' => 'Back', 'icon' =>'fa fa-reply'];
        return view('view-type.create', compact('link_url'))->with('site','ViewType');
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
        $data = ViewType::create([
            'name' => $request->name,
            'is_active'  => $active,
            ]);

        $image = '';
        $file_image = $request->file('image_active');
        if(strlen($file_image) > 0){
            $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
            $image = '/uploads/images/view-type/'.$data->id.'-active.'.$file_image->getClientOriginalExtension();
            $file_image->move('uploads/images/view-type/', $image);
        }

        $logo = '';
        $file_logo = $request->file('image_deactive');
        if(strlen($file_logo) > 0){
            $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
            $logo = '/uploads/images/view-type/'.$data->id.'-deactive.'.$file_logo->getClientOriginalExtension();
            $file_logo->move('uploads/images/view-type/', $logo);
        }
        $data->update([
            'image_active' => $image,
            'image_deactive' => $logo,
        ]);
        Session::flash('success', 'Create successfully!');
        return redirect()->route('view-type.index');
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
        $data = ViewType::find($id);

        $link_url = ['url' => route('view-type.index'), 'title' => 'Back', 'icon' =>'fa fa-reply'];
        return view('view-type.edit',compact('data','link_url'))->with('site','ViewType: '.$id);
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

        $data= ViewType::find($id);

        $image = $data->image_active;
        if($request->image_active !=null && $data->image_active != $request->image_active)
        {
            $file_image = $request->file('image_active');
            if(strlen($file_image) > 0){
                $image = '/uploads/images/view-type/'.$id.'-active.'.$file_image->getClientOriginalExtension();
                $file_image->move('uploads/images/view-type/', $image);
            }
        }

        $logo = $data->image_deactive;
        if($request->image_deactive !=null && $data->image_deactive != $request->image_deactive)
        {
            $file_logo = $request->file('image_deactive');
            if(strlen($file_logo) > 0){
                $logo = '/uploads/images/view-type/'.$id.'-deactive.'.$file_logo->getClientOriginalExtension();
                $file_logo->move('uploads/images/view-type/', $logo);
            }
        }

        $active = $request->is_active;
        ViewType::find($id)->update([
            'name' => $request->name,
            'image_active' => $image,
            'image_deactive' => $logo,
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
        ViewType::find($id)->delete();
        return redirect()->back();
    }
}