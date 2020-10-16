@extends('layouts.master')

@section('template_title')
  Check In/ Check Out
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
@section('template_linked_css')
    <style type="text/css">
        .form-control{max-width: 420px}
    </style>
@endsection
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 col-md-offset-1">
        <div class="card panel-default">
          {!! Form::model($data, array('action' => array('JobAdminController@inOutPost', $data->id), 'method' => 'POST', 'files' => true)) !!}
            {!! csrf_field() !!}

            <div class="card-body">
                <div class="form-group has-feedback row">
                    <label class="col-md-3 control-label">Username:</label>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" name="userName" value="{{$data->Users()->userName}}" class="form-control col-md-7 col-xs-12" disabled="">
                        </div>
                    </div>
                    
                    <label class="col-md-3 control-label">Job:</label>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" name="job" value="{{$data->Jobs()->Types()->name}}" class="form-control col-md-7 col-xs-12" disabled="">
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback row">
                    <label class="col-md-3 control-label">Real Start:</label>
                    <div class="col-md-3">
                        <div class="input-group date" id="real_start" data-target-input="nearest">
                            <input type="text" name="real_start" value="{{$data->real_start}}" class="form-control col-md-7 col-xs-12 datetimepicker-input" data-target="#real_start" data-toggle="datetimepicker">
                        </div>
                    </div>
                    
                    <label class="col-md-3 control-label">Real End:</label>
                    <div class="col-md-3">
                        <div class="input-group date" id="real_end" data-target-input="nearest">
                            <input type="text" name="real_end" value="{{$data->real_end}}" class="form-control col-md-7 col-xs-12 datetimepicker-input" data-target="#real_end" data-toggle="datetimepicker">
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback row">
                    <label class="col-md-3 control-label">Paid Time In:</label>
                    <div class="col-md-3">
                        <div class="input-group date" id="paidTimeIn" data-target-input="nearest">
                            <input type="text" name="paidTimeIn" value="{{$data->paidTimeIn}}" class="form-control col-md-7 col-xs-12 datetimepicker-input" data-target="#paidTimeIn" data-toggle="datetimepicker">
                        </div>
                    </div>

                    <label class="col-md-3 control-label">Paid Time Out:</label>
                    <div class="col-md-3">
                        <div class="input-group date" id="paidTimeOut" data-target-input="nearest">
                            <input type="text" name="paidTimeOut" value="{{$data->paidTimeOut}}" class="form-control col-md-7 col-xs-12 datetimepicker-input" data-target="#paidTimeOut" data-toggle="datetimepicker">
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback row">
                    <label class="col-md-3 control-label">Break Time:</label>
                    <div class="col-md-3">
                    <div class="input-group">
                    {!! Form::text('breakTime', old('breakTime'), array('id' => 'breakTime', 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Hour')) !!}
                    </div>
                    @if ($errors->has('breakTime'))
                        <span class="help-block"><strong>{{ $errors->first('breakTime') }}</strong></span>
                    @endif
                    </div>

                    <label class="col-md-3 control-label">Remarks:</label>
                    <div class="col-md-3">
                    <div class="input-group">
                    {!! Form::text('remarks', old('remarks'), array('id' => 'remarks', 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Remarks')) !!}
                    </div>
                    @if ($errors->has('remarks'))
                        <span class="help-block"><strong>{{ $errors->first('remarks') }}</strong></span>
                    @endif
                    </div>
                </div>

                <div class="form-group has-feedback row">
                    <label class="col-md-3 control-label">Total Hours:</label>
                    <div class="col-md-3">
                    <div class="input-group">
                    {!! Form::text('totalHours', old('totalHours'), array('id' => 'totalHours','readonly' => true, 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Total Hours')) !!}
                    </div>
                    </div>

                    <label class="col-md-3 control-label">Time Confirm:</label>
                    <div class="col-md-3">
                    <div class="input-group">
                    {!! Form::text('timestamp', date('Y-m-d H:i:s', $data->timestamp/1000), array('id' => 'timestamp','readonly' => true, 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'timestamp')) !!}
                    </div>
                    </div>
                </div>

                <div class="form-group has-feedback row {{ $errors->has('status') ? ' has-error ' : '' }}">
                    {!! Form::label('status', 'Job Status' , array('class' => 'col-md-3 control-label')); !!}
                    <div class="col-md-3">
                        <div class="input-group">
                            <select id="status" class="form-control col-md-7 col-xs-12" name="status" @if($data->rwsConfirmed == 1) disabled @endif>
                                <option value="1">Job Done</option>
                                <option value="2">Job Failure</option>
                            </select>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('#real_start').datetimepicker({
            format: 'LT'
        })

        $('#real_end').datetimepicker({
            format: 'LT'
        })

        $('#paidTimeIn').datetimepicker({
            format: 'LT'
        })

        $('#paidTimeOut').datetimepicker({
            format: 'LT'
        })
    });
</script>
@endsection