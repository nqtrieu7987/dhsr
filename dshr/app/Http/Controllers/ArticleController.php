<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Article;
use App\Models\Page;
use Session;
use Carbon\Carbon;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = ($request->page!= null)?$request->page:1;
        $articles = Article::orderBy('id', 'desc')->paginate(20);
        $pages=Page::where('is_active', 1)->pluck('name', 'id');

        return view('article.index', compact('articles'))
                    ->with('page', $page)
                    ->with('pages', $pages)
                    ->with('site', 'article');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $site = 'article';
        $pages=Page::where('is_active', 1)->pluck('name', 'id');
        return view('article.create', compact('pages'))
                ->with('site', $site);
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
            'title' => 'required'
        ]);
        $filename = '';
        $file = $request->file('image');
        if(strlen($file) > 0){
            $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
            $filename = '/uploads/images/article/'.$timestamp. '-' .$file->getClientOriginalName();
            $file->move('uploads/images/article/', $filename);
        }
        $article=Article::create([
            'title' => $request->title,
            'header' => $request->header,
            'author' => $request->author,
            'type' => $request->type,
            'url' => $request->url,
            'page_id' => $request->page_id,
            'published_time' => $request->published_time,
            'image' => $filename,
            'is_hot'  => $request->is_hot,
            'is_active'  => $request->is_active,
            'content' => str_replace('<img alt=""','<img class="img-responsive" alt=""',$request->content),
            'slug' => Str::slug($request->title)
        ]);

        Session::flash('messageSS', 'Create success!');
        return redirect()->route('article.index');
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
        $site = 'article';

        $article= Article::find($id);
        if(!isset($article)){
            return redirect()->route('article.index');
        }
        $pages=Page::where('is_active', 1)->pluck('name', 'id');
        return view('article.edit', compact('article','pages'))
                ->with('site', $site);
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
            'title' => 'required',
        ]);

        $article= Article::find($id);
        $filename = $article->image;

        if($request->image !=null && $article->image != $request->image)
        {
            $file = $request->file('image');
            if(strlen($file) > 0){
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                $filename = '/uploads/images/article/'.$timestamp. '-' .$file->getClientOriginalName();
                $file->move('uploads/images/article/', $filename);
            }
        }
        $article->update([
            'title' => $request->title,
            'header' => $request->header,
            'author' => $request->author,
            'type' => $request->type,
            'slug' => $request->slug,
            'page_id' => $request->page_id,
            'published_time' => date('Y-m-d H:i', strtotime($request->published_time)),
            'image' => $filename,
            'is_hot'  => $request->is_hot,
            'is_active'  => $request->is_active,
            'content' => str_replace('<img alt=""','<img class="img-responsive" alt=""',$request->content)
        ]);
        
        Session::flash('messageSS', 'Update success!');
        return redirect()->route('article.edit',$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article=Article::find($id);
        if($article->image !=null){
            $filename=public_path().$article->image;
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
        Article::find($id)->delete();
        Session::flash('success', 'Xóa thành công!');
        return redirect()->back();
    }
}
