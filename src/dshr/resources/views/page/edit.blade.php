@extends('layouts.app')

@section('template_title')
  Sửa Landingpage
@endsection

@php
    $pageActive = [
        'checked' => '',
        'value' => 0,
        'true'  => '',
        'false' => 'checked'
    ];

    if($page->is_active == 1) {
        $pageActive = [
            'checked' => 'checked',
            'value' => 1,
            'true'  => 'checked',
            'false' => ''
        ];
    }
@endphp

@section('content')

  <div class="container">
    <div class="row">
      <div class="col-md-12 col-md-offset-1">
        <div class="card panel-default">
          <div class="card-header">
            <a href="/page" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              <span class="hidden-xs">Danh sách Landingpage </span><br/>
            </a>
          </div>

          {!! Form::model($page, array('action' => array('PageController@update', $page->id), 'method' => 'PUT', 'files' => true)) !!}
            {!! csrf_field() !!}

            <div class="card-body">

              <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                {!! Form::label('name', 'Tên' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('name', old('name'), array('id' => 'name', 'class' => 'form-control', 'placeholder' => trans('forms.ph-username'))) !!}
                    <label class="input-group-addon" for="name"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('url') ? ' has-error ' : '' }}">
                {!! Form::label('url', 'Url' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('url', old('url'), array('id' => 'url', 'class' => 'form-control', 'placeholder' => 'Url')) !!}
                    <label class="input-group-addon" for="url"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('style') ? ' has-error ' : '' }}">
                {!! Form::label('style', 'Style' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('style', old('style'), array('id' => 'style', 'class' => 'form-control', 'placeholder' => 'Style')) !!}
                    <label class="input-group-addon" for="style"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('button') ? ' has-error ' : '' }}">
                {!! Form::label('button', 'Tên nút' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('button', old('button'), array('id' => 'button', 'class' => 'form-control', 'placeholder' => 'Tên nút')) !!}
                    <label class="input-group-addon" for="button"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('color') ? ' has-error ' : '' }}">
                {!! Form::label('color', 'Màu nút' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="form-group">
                    <input name="color" type="text" id="brightness-demo" class="form-control demo" data-control="brightness" value="{{ $page->color ? $page->color : '#007bff' }}">
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('command') ? ' has-error ' : '' }}">
                {!! Form::label('command', 'Command Code' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::select('command_id', $commands, $page->command, ['class' => 'form-control']); !!}
                    <label class="input-group-addon" for="command"><i class="fa fa-fw fa-pencil-square-o" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('banner') ? ' has-error ' : '' }}">
                {!! Form::label('banner', 'Banner' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::select('banner_id', $banners, $page->banner_id, ['class' => 'form-control']); !!}
                    <label class="input-group-addon" for="banner"><i class="fa fa-fw fa-pencil-square-o" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>
              
              <div class="form-group has-feedback row {{ $errors->has('article') ? ' has-error ' : '' }}">
                {!! Form::label('article', 'Thông tin' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::select('article_id', $articles, $page->article_id, ['class' => 'form-control', 'id' =>'article_id']); !!}
                    <label class="input-group-addon" for="article"><i class="fa fa-fw fa-pencil-square-o" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('article') ? ' has-error ' : '' }}">
                {!! Form::label('article', 'Tin liên quan' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::select('article_list[]', $articles, $article_list, ['class' => 'form-control', 'id' =>'article_list', 'multiple' => 'multiple']); !!}
                    <label class="input-group-addon" for="article"><i class="fa fa-fw fa-pencil-square-o" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('is_active') ? ' has-error ' : '' }}">
                {!! Form::label('is_active', trans('Trạng thái'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                    <label class="switch {{ $pageActive['checked'] }}" for="is_active">
                        <span class="active"><i class="fa fa-toggle-on fa-2x"></i> {{ trans('themes.statusEnabled') }}</span>
                        <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i> {{ trans('themes.statusDisabled') }}</span>
                        <input type="radio" name="is_active" value="1" {{ $pageActive['true'] }}>
                        <input type="radio" name="is_active" value="0" {{ $pageActive['false'] }}>
                    </label>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('code') ? ' has-error ' : '' }}">
                {!! Form::label('code', 'Code Tracking' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                        {!! Form::textarea('code', old('code'), array('id' => 'code', 'class' => 'form-control', 'placeholder' => trans('Code'))) !!}
                    </div>
                </div>
              </div>

            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                  {!! Form::button('<i class="fa fa-fw fa-save" aria-hidden="true"></i> Save Changes', array('class' => 'btn btn-success btn-block margin-bottom-1 btn-save','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'data-title' => trans('modals.edit_user__modal_text_confirm_title'), 'data-message' => trans('modals.edit_user__modal_text_confirm_message'))) !!}
                </div>
                <div class="col-md-4"></div>
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
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    @include('scripts.check-changed')
    @include('scripts.toggleIsActive')

    <script type="text/javascript">
    $("#article_id").select2({
        allowClear: false
    });
    $("#article_list").select2({
        allowClear: false
    });

    $(function(){
      var colpick = $('.demo').each( function() {
        $(this).minicolors({
          control: $(this).attr('data-control') || 'hue',
          inline: $(this).attr('data-inline') === 'true',
          letterCase: 'lowercase',
          opacity: false,
          change: function(hex, opacity) {
            if(!hex) return;
            if(opacity) hex += ', ' + opacity;
            try {
              console.log(hex);
            } catch(e) {}
            $(this).select();
          },
          theme: 'bootstrap'
        });
      });
      
      var $inlinehex = $('#inlinecolorhex h3 small');
      $('#inlinecolors').minicolors({
        inline: true,
        theme: 'bootstrap',
        change: function(hex) {
          if(!hex) return;
          $inlinehex.html(hex);
        }
      });
    });
</script>
@endsection