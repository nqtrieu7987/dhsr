<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use Auth;
use Session;
use Carbon\Carbon;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks= Bank::orderBy('created_at','DESC')->paginate(100);
        $link_url = ['url' => route('bank.create'), 'title' => 'Add', 'icon' =>'fa fa-plus-circle'];
        return view('bank.index',compact('banks','link_url'))
                ->with('site','Bank');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $link_url = ['url' => route('bank.index'), 'title' => 'Back', 'icon' =>'fa fa-reply'];
        return view('bank.create', compact('link_url'))->with('site','Bank');
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
        
        $logo = '';
        $file_logo = $request->file('logo');
        if(strlen($file_logo) > 0){
            $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
            $logo = '/uploads/images/bank/'.$timestamp.'-'.$file_logo->getClientOriginalName();
            $file_logo->move('uploads/images/bank/', $logo);
        }

        $active = isset($request->is_active) ? "1" : "0";
        
        $bank = Bank::create([
            'name' => $request->name,
            'is_active'  => $active,
            'logo' => $logo,
            ]);

        Session::flash('success', 'Create successfully!');
        return redirect()->route('bank.index');
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
        $bank=Bank::findOrFail($id);
        $link_url = ['url' => route('bank.index'), 'title' => 'Back', 'icon' =>'fa fa-reply'];
        return view('bank.edit',compact('bank','link_url'))->with('site','Bank: '.$id);
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

        $bank= Bank::findOrFail($id);

        $logo = $bank->logo;
        if($request->logo !=null && $bank->logo != $request->logo)
        {
            $file_logo = $request->file('logo');
            if(strlen($file_logo) > 0){
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                $logo = '/uploads/images/bank/'.$timestamp.'-'.$file_logo->getClientOriginalName();
                $file_logo->move('uploads/images/bank/', $logo);
            }
        }

        $active = $request->is_active;
        Bank::findOrFail($id)->update([
            'name' => $request->name,
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
        Bank::findOrFail($id)->delete();
        return redirect()->back();
    }
}