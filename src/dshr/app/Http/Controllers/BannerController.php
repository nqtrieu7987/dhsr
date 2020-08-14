<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Banner;
use Auth;
use Session;
use Carbon\Carbon;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $page = ($request->page!= null)?$request->page:1;
        $banners= Banner::orderBy('type','ASC')->paginate(100);
        return view('banner.index',compact('banners'))
                ->with('site','banner')
                ->with('page', $page);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('banner.create')->with('site','banner');
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
        ]);
        $logo = ''; $header =''; $footer = ''; $bg ='';
        $file_logo = $request->file('logo');
        if(strlen($file_logo) > 0){
            $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
            $logo = '/uploads/images/banner/'.$timestamp.'-'.$file_logo->getClientOriginalName();
            $file_logo->move('uploads/images/banner/', $logo);
        }
        
        $file_header = $request->file('header');
        if(strlen($file_header) > 0){
            $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
            $header = '/uploads/images/banner/'.$timestamp.'-'.$file_header->getClientOriginalName();
            $file_header->move('uploads/images/banner/', $header);
        }
        
        $file_footer = $request->file('footer');
        if(strlen($file_footer) > 0){
            $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
            $footer = '/uploads/images/banner/'.$timestamp.'-'.$file_footer->getClientOriginalName();
            $file_footer->move('uploads/images/banner/', $footer);
        }

        $file_bg = $request->file('bg');
        if(strlen($file_bg) > 0){
            $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
            $bg = '/uploads/images/banner/'.$timestamp.'-'.$file_bg->getClientOriginalName();
            $file_bg->move('uploads/images/banner/', $bg);
        }

        $active = isset($request->is_active) ? "1" : "0";
        
        $banner = Banner::create([
            'name' => $request->name,
            'url' => $request->url,
            'is_active'  => $active,
            'logo' => $logo,
            'header' => $header,
            'footer' => $footer,
            'bg' => $bg
            ]);

        Session::flash('messageSS', 'Create success!');
        return redirect()->route('banner.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $banner=Banner::find($id);
        return view('banner.show',compact('banner'))->with('site','banner');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $banner=Banner::find($id);
        return view('banner.edit',compact('banner'))->with('site','banner');
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
        ]);

        $banner= Banner::find($id);

        $logo = $banner->logo;
        if($request->logo !=null && $banner->logo != $request->logo)
        {
            $file_logo = $request->file('logo');
            if(strlen($file_logo) > 0){
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                $logo = '/uploads/images/banner/'.$timestamp.'-'.$file_logo->getClientOriginalName();
                $file_logo->move('uploads/images/banner/', $logo);
            }
        }

        $header = $banner->header;
        if($request->header !=null && $banner->header != $request->header)
        {
            $file_header = $request->file('header');
            if(strlen($file_header) > 0){
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                $header = '/uploads/images/banner/'.$timestamp.'-'.$file_header->getClientOriginalName();
                $file_header->move('uploads/images/banner/', $header);
            }
        }

        $footer = $banner->footer;
        if($request->footer !=null && $banner->footer != $request->footer)
        {
            $file_footer = $request->file('footer');
            if(strlen($file_footer) > 0){
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                $footer = '/uploads/images/banner/'.$timestamp.'-'.$file_footer->getClientOriginalName();
                $file_footer->move('uploads/images/banner/', $footer);
            }
        }

        $bg = $banner->bg;
        if($request->bg !=null && $banner->bg != $request->bg)
        {
            $file_bg = $request->file('bg');
            if(strlen($file_bg) > 0){
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                $bg = '/uploads/images/banner/'.$timestamp.'-'.$file_bg->getClientOriginalName();
                $file_bg->move('uploads/images/banner/', $bg);
            }
        }

        $active = $request->is_active;
        
        Banner::find($id)->update([
            'name' => $request->name,
            'url' => $request->url,
            'is_active'  => $active,
            'logo' => $logo,
            'header' => $header,
            'footer' => $footer,
            'bg' => $bg
            ]);

        Session::flash('messageSS', 'Update success!');
        return redirect()->route('banner.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $banner=Banner::find($id);
        if($banner->image !=null){
            $filename=public_path().$banner->image;
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
        Banner::find($id)->delete();
        return redirect()->back();
    }
}