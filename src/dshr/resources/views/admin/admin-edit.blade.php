@extends('layouts.master')

@section('template_title')
  Edit admin
@endsection

@section('content')

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 col-md-offset-1">
        <div class="card panel-default">

          {!! Form::model($user, array('action' => array('HomeController@update', $user->id), 'method' => 'PUT', 'files' => true)) !!}
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

              <div class="form-group has-feedback row {{ $errors->has('username') ? ' has-error ' : '' }}">
                {!! Form::label('username', 'Username' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('username', old('username'), array('id' => 'username', 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Username','readonly'=> true)) !!}
                  </div>
                  @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('email') ? ' has-error ' : '' }}">
                {!! Form::label('email', 'Email' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::email('email', old('email'), array('id' => 'email', 'rows' => 3, 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => trans('Email'))) !!}
                  </div>
                  @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('hotel_id') ? ' has-error ' : '' }}">
                {!! Form::label('hotel_id', 'Select Hotel' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::select('hotel_id', $hotels, null, ['id' => 'hotel_id', 'class' => 'form-control col-md-7 col-xs-12']); !!}
                  </div>
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