<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Command;
use App\Models\Banner;
use App\Models\Article;
use Auth;
use Session;
use Carbon\Carbon;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pages = Page::orderBy('created_at', 'DESC');
        if($request->name != ''){
            $pages = $pages->where('name','LIKE', '%'.$request->name.'%');
        }
        $pages = $pages->paginate(20);
        $commands=Command::all();
        $banners=Banner::all();
        return view('page.index',compact('pages','commands','banners'))
                ->with('site','page');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $commands =Command::where('is_active', 1)->pluck('name', 'id');
        $banners =Banner::where('is_active', 1)->pluck('name', 'id')->toArray();
        $banners[0] ='Chọn banner';
        ksort($banners);
        $articles =Article::where('is_active', 1)->pluck('title', 'id');
        $style = config('app.style');
        return view('page.create', compact('commands','banners','articles','style'))->with('site','page');
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
            'name' => 'required',
            'command_id' => 'required',
            'article_id' => 'required',
            'style' => 'required|numeric|min:1|max:9'
        ]);

        $active = isset($request->is_active) ? "1" : "0";
        $article_list = null;
        if($request->article_list != ''){
            $article_list = implode(',', $request->article_list);
        }
        $page = Page::create([
            'name' => $request->name,
            'code' => $request->code,
            'is_active'  => $active,
            'command_id' => $request->command_id,
            'banner_id' => $request->banner_id,
            'article_id' => $request->article_id,
            'article_list' => $article_list,
            'style' => $request->style,
            'button' => $request->button,
            'color' => $request->color,
            'url' => $request->url
            ]);

        Session::flash('messageSS', 'Create success!');
        return redirect()->route('page.index');
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
        $page = Page::find($id);
        $commands = Command::where('is_active', 1)->pluck('name', 'id');
        $banners = Banner::where('is_active', 1)->pluck('name', 'id')->toArray();
        $banners[0] ='Chọn banner';
        ksort($banners);
        $articles =Article::where('is_active', 1)->pluck('title', 'id');
        $article_list = null;
        if($page->article_list != ''){
            $article_list = explode(',', $page->article_list);
        }
        $style = config('app.style');
        return view('page.edit',compact('page', 'commands', 'banners','articles','style','article_list'))->with('site','page');
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

        $page= Page::find($id);
        $article_list = null;
        if($request->article_list != ''){
            $article_list = implode(',', $request->article_list);
        }

        $active = $request->is_active;
        $page->update([
            'name' => $request->name,
            'code' => $request->code,
            'is_active'  => $active,
            'command_id' => $request->command_id,
            'banner_id' => $request->banner_id,
            'article_id' => $request->article_id,
            'article_list' => $article_list,
            'style' => $request->style,
            'button' => $request->button,
            'color' => $request->color,
            'url' => $request->url
            ]);

        Session::flash('messageSS', 'Update success!');
        return redirect()->route('page.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Page::find($id)->delete();
        return redirect()->back();
    }
}
