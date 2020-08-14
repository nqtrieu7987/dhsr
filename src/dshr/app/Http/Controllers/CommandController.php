<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Command;
use Auth;
use Session;
use Carbon\Carbon;

class CommandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commands= Command::orderBy('created_at','DESC')->paginate(100);
        $providers =config('app.provider');
        return view('command.index',compact('commands'))
                ->with('site','command')
                ->with('providers',$providers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $providers =config('app.provider');
        return view('command.create')->with('site','command')->with('providers',$providers);
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
        
        $active = isset($request->is_active) ? "1" : "0";
        
        $command = Command::create([
            'name' => $request->name,
            'viettel' => $request->viettel,
            'mobiphone' => $request->mobiphone,
            'vinaphone' => $request->vinaphone,
            'other' => $request->other,
            'is_active'  => $active,
            ]);

        Session::flash('messageSS', 'Create success!');
        return redirect()->route('command.index');
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
        $command=Command::find($id);
        $providers =config('app.provider');
        return view('command.edit',compact('command'))->with('site','command')->with('providers',$providers);
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

        $command= Command::find($id);

        $active = $request->is_active;
        Command::find($id)->update([
            'name' => $request->name,
            'viettel' => $request->viettel,
            'mobiphone' => $request->mobiphone,
            'vinaphone' => $request->vinaphone,
            'other' => $request->other,
            'is_active'  => $active,
            ]);

        Session::flash('messageSS', 'Update success!');
        return redirect()->route('command.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Command::find($id)->delete();
        return redirect()->back();
    }
}
