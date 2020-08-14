@extends('layouts.app')

@section('template_title')
  Create new article
@endsection

@section('content')

  <div class="container">
    <div class="row">
      <div class="col-md-12 col-md-offset-1">
        <div class="card panel-default">
          <div class="card-header">
            <a href="/article" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              <span class="hidden-xs">List articles </span><br/>
            </a>
          </div>
          {!! Form::open(array('action' => 'ArticleController@store', 'method' => 'POST', 'role' => 'form', 'files' => true)) !!}
            {!! csrf_field() !!}

            <div class="card-body">

              <div class="form-group has-feedback row {{ $errors->has('title') ? ' has-error ' : '' }}">
                {!! Form::label('title', 'Title' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('title', old('title'), array('id' => 'title', 'class' => 'form-control', 'placeholder' => 'title')) !!}
                    <div class="input-group-append"><label for="name" class="input-group-text"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i></label></div>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('header') ? ' has-error ' : '' }}">
                {!! Form::label('header', 'Header' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('header', old('header'), array('id' => 'header', 'class' => 'form-control', 'placeholder' => 'header')) !!}
                    <div class="input-group-append"><label for="name" class="input-group-text"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i></label></div>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('url') ? ' has-error ' : '' }}">
                {!! Form::label('url', 'Url' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('url', old('url'), array('id' => 'url', 'class' => 'form-control', 'placeholder' => 'url')) !!}
                    <div class="input-group-append"><label for="name" class="input-group-text"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i></label></div>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('page') ? ' has-error ' : '' }}">
                {!! Form::label('page', 'LandingPage' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::select('page_id', $pages, null, ['class' => 'form-control']); !!}
                    <div class="input-group-append"><label for="name" class="input-group-text"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i></label></div>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('published_time') ? ' has-error ' : '' }}">
                {!! Form::label('published_time', 'Ngày xuất bản' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                   {!! Form::text('published_time', null, array('class' => 'form-control col-md-6 col-sm-6 col-xs-12 dateTimePicker', 'id' => 'published_time')) !!}
                    <div class="input-group-append"><label for="name" class="input-group-text"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i></label></div>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('is_active') ? ' has-error ' : '' }}">
                {!! Form::label('is_active', trans('Active') , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <label class="switch checked" for="is_active">
                    <span class="active"><i class="fa fa-toggle-on fa-2x"></i> {{ trans('Active') }}</span>
                    <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i> {{ trans('Deactivated') }}</span>
                    <input type="radio" name="is_active" value="1" checked>
                    <input type="radio" name="is_active" value="0">
                  </label>

                  @if ($errors->has('is_active'))
                    <span class="help-block">
                      <strong>{{ $errors->first('is_active') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('is_hot') ? ' has-error ' : '' }}">
                {!! Form::label('is_hot', trans('Icon Play Video') , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <label class="switch_hot checked" for="is_hot">
                    <span class="active"><i class="fa fa-toggle-on fa-2x"></i> {{ trans('themes.statusEnabled') }}</span>
                    <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i> {{ trans('themes.statusDisabled') }}</span>
                    <input type="radio" name="is_hot" value="1" checked>
                    <input type="radio" name="is_hot" value="0">
                  </label>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('image') ? ' has-error ' : '' }}">
                {!! Form::label('image', trans('Image'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::file('image', null, array('class' => 'form-control ')) !!}
                    @if ($errors->has('image'))<p style="color:red;">{!!$errors->first('image')!!}</p>@endif
                  </div>
                  @if ($errors->has('image'))
                    <span class="help-block">
                        <strong>{{ $errors->first('image') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('notes') ? ' has-error ' : '' }}">
                {!! Form::label('content', trans('Content') , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                    <div class="input-group">
                        {!! Form::textarea('content', old('content'), array('id' => 'content', 'class' => 'form-control', 'placeholder' => trans('Content'))) !!}
                    </div>
                    @if ($errors->has('content'))
                        <span class="help-block">
                            <strong>{{ $errors->first('content') }}</strong>
                        </span>
                    @endif
                </div>
              </div>

            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col-xs-3"></div>
                <div class="col-xs-6">
                  {!! Form::button('<i class="fa fa-fw fa-save" aria-hidden="true"></i> Save Changes', array('class' => 'btn btn-success btn-block margin-bottom-1 btn-save','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'data-title' => trans('modals.edit_user__modal_text_confirm_title'), 'data-message' => trans('modals.edit_user__modal_text_confirm_message'))) !!}
                </div>
                <div class="col-xs-3"></div>
              </div>
            </div>

          {!! Form::close() !!}

        </div>
      </div>
    </div>
  </div>

    @include('modals.modal-save')
    @include('modals.modal-delete')

@endsection

@section('footer_scripts')
    
    @include('scripts.filemanager')
    <script> CKEDITOR.replace('content', options); </script>

    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    @include('scripts.check-changed')
    @include('scripts.toggleIsActive')
<script type="text/javascript">
    $(document).ready(function() {
        $('#published_time').datetimepicker({
          format: "yyyy-mm-dd hh:ii"
        });
    });
</script>
@endsection