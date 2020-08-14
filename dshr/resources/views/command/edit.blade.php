@extends('layouts.app')

@section('template_title')
  Sửa command
@endsection

@php
    $commandActive = [
        'checked' => '',
        'value' => 0,
        'true'  => '',
        'false' => 'checked'
    ];

    if($command->is_active == 1) {
        $commandActive = [
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
            <a href="/command" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              <span class="hidden-xs">Chỉnh sửa command </span><br/>
            </a>
          </div>

          {!! Form::model($command, array('action' => array('CommandController@update', $command->id), 'method' => 'PUT', 'files' => true)) !!}
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

              <div class="form-group has-feedback row {{ $errors->has('viettel') ? ' has-error ' : '' }}">
                {!! Form::label('viettel', 'Viettel' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('viettel', old('viettel'), array('id' => 'viettel', 'class' => 'form-control', 'placeholder' => 'Viettel')) !!}
                    <label class="input-group-addon" for="viettel"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('mobiphone') ? ' has-error ' : '' }}">
                {!! Form::label('mobiphone', 'Mobiphone' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('mobiphone', old('mobiphone'), array('id' => 'mobiphone', 'class' => 'form-control', 'placeholder' => 'Mobiphone')) !!}
                    <label class="input-group-addon" for="mobiphone"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('vinaphone') ? ' has-error ' : '' }}">
                {!! Form::label('vinaphone', 'Vinaphone' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('vinaphone', old('vinaphone'), array('id' => 'vinaphone', 'class' => 'form-control', 'placeholder' => 'Vinaphone')) !!}
                    <label class="input-group-addon" for="vinaphone"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('other') ? ' has-error ' : '' }}">
                {!! Form::label('other', 'Other' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('other', old('other'), array('id' => 'other', 'class' => 'form-control', 'placeholder' => 'Other')) !!}
                    <label class="input-group-addon" for="other"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('is_active') ? ' has-error ' : '' }}">
                {!! Form::label('is_active', trans('Trạng thái'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                    <label class="switch {{ $commandActive['checked'] }}" for="is_active">
                        <span class="active"><i class="fa fa-toggle-on fa-2x"></i> {{ trans('themes.statusEnabled') }}</span>
                        <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i> {{ trans('themes.statusDisabled') }}</span>
                        <input type="radio" name="is_active" value="1" {{ $commandActive['true'] }}>
                        <input type="radio" name="is_active" value="0" {{ $commandActive['false'] }}>
                    </label>
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
@endsection