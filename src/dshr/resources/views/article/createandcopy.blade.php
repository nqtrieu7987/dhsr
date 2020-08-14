@extends('layouts.app')

@section('content')
<section class="content-header">
    <h3>Thêm mới bài viết</h3>
</section>
<section class="content">
<div class="x_panel">
    <div class="x_content">
        <div class="row">
            {!! Form::open(array('route' => ['article.store'],'method'=>'POST', 'files' => true,  'class' => 'form-horizontal form-label-left', 'id' => 'formCreate')) !!}
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Tiêu đề <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::text('title', $article->title, array('class' => 'form-control ')) !!}
                    @if ($errors->has('title'))<p style="color:red;">{!!$errors->first('title')!!}</p>@endif
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Tiêu đề (en) <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::text('title_en', $article->title_en, array('class' => 'form-control ')) !!}
                    @if ($errors->has('title_en'))<p style="color:red;">{!!$errors->first('title_en')!!}</p>@endif
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3 col-xs-12" for="first-name">Vị Trí Bài viết<span class="required">*</span></label>
                <div class="col-md-1 col-sm-1 col-xs-6">
                    {!! Form::number('code', 1, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                    @if ($errors->has('code'))<p style="color:red;">{!!$errors->first('code')!!}</p>@endif
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Trạng Thái</label>
                <div class="col-md-1 col-sm-1 col-xs-3">
                    {!! Form::checkbox('is_active', $article->is_active, true) !!}
                    @if ($errors->has('is_active'))<p style="color:red;">{!!$errors->first('is_active')!!}</p>@endif
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-3" for="first-name">Hot</label>
                <div class="col-md-1 col-sm-1 col-xs-3">
                    {!! Form::checkbox('is_hot', $article->is_hot, false) !!}
                    @if ($errors->has('is_hot'))<p style="color:red;">{!!$errors->first('is_hot')!!}</p>@endif
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="owner">Chuyên mục</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('category_id', $categorys,$article->category_id, ['class' => 'select2_single form-control']); !!}
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Header</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::textarea('header', $article->header, array('class' => 'form-control ','size' => '30x3')) !!}
                    @if ($errors->has('header'))<p style="color:red;">{!!$errors->first('header')!!}</p>@endif
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Header (en)</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::textarea('header_en', $article->header_en, array('class' => 'form-control ','size' => '30x3')) !!}
                    @if ($errors->has('header_en'))<p style="color:red;">{!!$errors->first('header_en')!!}</p>@endif
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name"> Ngày xuất bản</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::text('published_time', $article->published_time, array('class' => 'form-control col-md-6 col-sm-6 col-xs-12 dateTimePicker', 'id' => 'published_time')) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3 col-xs-12" for="first-name">Hình ảnh</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::file('image', null, array('class' => 'form-control ')) !!}
                    @if ($errors->has('image'))<p style="color:red;">{!!$errors->first('image')!!}</p>@endif
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Nội dung</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {!! Form::textarea('content', $article->content, array('class' => 'form-control ')) !!}
                    @if ($errors->has('content'))<p style="color:red;">{!!$errors->first('content')!!}</p>@endif
                    <script>
                        CKEDITOR.replace( 'content',{height:'150px',} );
                    </script>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Nội dung (en)</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {!! Form::textarea('content_en', $article->content_en, array('class' => 'form-control ')) !!}
                    @if ($errors->has('content_en'))<p style="color:red;">{!!$errors->first('content_en')!!}</p>@endif
                    <script>
                        CKEDITOR.replace( 'content_en',{height:'150px',} );
                    </script>
                </div>
            </div>
            <div><input type="hidden" id="saveOrcreate" name="save" value="abc"></div>
            <div class="form-group text-center">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" id="save" value="save" class="btn btn-success" >Lưu</button>
                    <button type="submit"  id="saveandcreate" class="btn btn-info">Lưu & Tạo mới</button>
                    <button type="submit"  id="saveandcoppy" class="btn btn-primary">Lưu & Coppy</button>
                    <a class="btn btn-default" href="{{ URL::route('article.index') }}">Hủy</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
</section>
<script type="text/javascript">
    $('#save').click(function(){
        $('#saveOrcreate').val('save');
    });

    $('#saveandcreate').click(function(){
        $('#saveOrcreate').val('create');
    });

    $('#saveandcoppy').click(function(){
        $('#saveOrcreate').val('copy');
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#published_time').datetimepicker().datetimepicker("setDate", new Date());
    });
</script>
@endsection