@extends('layouts.master')

@section('template_title')
  Edit Job Type
@endsection

@php
    $dataActive = [
        'checked' => '',
        'value' => 0,
        'true'  => '',
        'false' => 'checked'
    ];

    if($data->is_active == 1) {
        $dataActive = [
            'checked' => 'checked',
            'value' => 1,
            'true'  => 'checked',
            'false' => ''
        ];
    }
@endphp

@section('content')

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 col-md-offset-1">
        <div class="card panel-default">
          {{-- <div class="card-header">
            <a href="/job-type" class="btn btn-blue-light btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              <span class="hidden-xs">Back to list </span><br/>
            </a>
          </div> --}}

          {!! Form::model($data, array('action' => array('JobTypeController@update', $data->id), 'method' => 'PUT', 'files' => true)) !!}
            {!! csrf_field() !!}

            <div class="card-body">

              <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                {!! Form::label('name', 'Name' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('name', old('name'), array('id' => 'name', 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => trans('forms.ph-username'))) !!}
                  </div>
                  @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('is_active') ? ' has-error ' : '' }}">
                {!! Form::label('is_active', 'Active', array('class' => 'col-3 col-md-3 col-sm-3 col-xs-3 control-label')); !!}
                <div class="col-3 col-md-3 col-sm-3 col-xs-3">
                    <label class="switch {{ $dataActive['checked'] }}" for="is_active">
                        <span class="active"><i class="fa fa-toggle-on fa-2x"></i></span>
                        <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i></span>
                        <input type="radio" name="is_active" value="1" {{ $dataActive['true'] }}>
                        <input type="radio" name="is_active" value="0" {{ $dataActive['false'] }}>
                    </label>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('comment') ? ' has-error ' : '' }}">
                {!! Form::label('comment', 'Comment' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::textarea('comment', old('comment'), array('id' => 'comment', 'rows' => 3, 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => trans('Comment'))) !!}
                  </div>
                  @if ($errors->has('comment'))
                    <span class="help-block">
                        <strong>{{ $errors->first('comment') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                  {!! Form::button('<i class="fa fa-fw fa-save" aria-hidden="true"></i> Save Changes', array('class' => 'btn bg-main btn-block margin-bottom-1 btn-save','type' => 'submit')) !!}
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