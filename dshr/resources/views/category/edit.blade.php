@extends('layouts.app')

@section('template_title')
  Sửa món ăn
@endsection

@php
    $categoryActive = [
        'checked' => '',
        'value' => 0,
        'true'  => '',
        'false' => 'checked'
    ];

    if($category->is_active == 1) {
        $categoryActive = [
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
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="card-header">
            <a href="/category" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              <span class="hidden-xs">Danh sách thể loại</span><br/>
            </a>
          </div>

          {!! Form::model($category, array('action' => array('CategoryController@update', $category->id), 'method' => 'PUT')) !!}
            {!! csrf_field() !!}

            <div class="panel-body">

              <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                {!! Form::label('name', 'Tên' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('name', old('name'), array('id' => 'name', 'class' => 'form-control', 'placeholder' => trans('forms.ph-username'))) !!}
                    <label class="input-group-addon" for="name"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('slug') ? ' has-error ' : '' }}">
                {!! Form::label('slug', 'Slug' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('slug', old('slug'), array('id' => 'slug', 'class' => 'form-control', 'placeholder' => trans('forms.ph-username'))) !!}
                    <label class="input-group-addon" for="slug"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('is_active') ? ' has-error ' : '' }}">
                {!! Form::label('is_active', trans('Trạng thái'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                    <label class="switch {{ $categoryActive['checked'] }}" for="is_active">
                        <span class="active"><i class="fa fa-toggle-on fa-2x"></i> {{ trans('themes.statusEnabled') }}</span>
                        <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i> {{ trans('themes.statusDisabled') }}</span>
                        <input type="radio" name="is_active" value="1" {{ $categoryActive['true'] }}>
                        <input type="radio" name="is_active" value="0" {{ $categoryActive['false'] }}>
                    </label>
                </div>
              </div>

            </div>

            <div class="panel-footer">
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

    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    @include('scripts.check-changed')
    @include('scripts.toggleIsActive')

@endsection