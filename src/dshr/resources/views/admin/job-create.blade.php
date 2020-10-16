@extends('layouts.master')

@section('template_title')
  Create Job
@endsection

@section('content')

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 col-md-offset-1">
        <div class="card card-default">
          {{-- <div class="card-header">
            <a href="/hotel" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              <span class="hidden-xs">List Job </span><br/>
            </a>
          </div> --}}
          {!! Form::open(array('action' => 'JobAdminController@store', 'method' => 'POST', 'role' => 'form', 'files' => true)) !!}
            {!! csrf_field() !!}

            <div class="card-body">
              <div class="form-group has-feedback row {{ $errors->has('job_type_id') ? ' has-error ' : '' }}">
                {!! Form::label('job_type_id', 'Select Job Type' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::select('job_type_id', $types, null, ['id' => 'job', 'class' => 'form-control col-md-7 col-xs-12']); !!}
                  </div>
                </div>
              </div>

              {{-- <div class="form-group has-feedback row {{ $errors->has('hotel_id') ? ' has-error ' : '' }}">
                {!! Form::label('hotel_id', 'Select Hotel' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::select('hotel_id', $hotels, null, ['id' => 'hotel_id', 'class' => 'form-control col-md-7 col-xs-12']); !!}
                  </div>
                </div>
              </div> --}}

              <div class="form-group has-feedback row {{ $errors->has('slot') ? ' has-error ' : '' }}">
                {!! Form::label('slot', 'Slot' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('slot', old('slot'), array('id' => 'slot', 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Slot')) !!}
                  </div>
                  @if ($errors->has('slot'))
                    <span class="help-block">
                        <strong>{{ $errors->first('slot') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

                <div class="form-group has-feedback row">
                    <label class="col-md-3 control-label">Start Time:</label>
                    <div class="col-md-9">
                        <div class="input-group date" id="start_time" data-target-input="nearest">
                            <input type="text" name="start_time" class="form-control col-md-7 col-xs-12 datetimepicker-input" data-target="#start_time" data-toggle="datetimepicker">
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback row">
                    <label class="col-md-3 control-label">End Time:</label>
                    <div class="col-md-9">
                        <div class="input-group date" id="end_time" data-target-input="nearest">
                            <input type="text" name="end_time" class="form-control col-md-7 col-xs-12 datetimepicker-input" data-target="#end_time" data-toggle="datetimepicker">
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback row">
                    <label class="col-md-3 control-label">Start Date:</label>
                    <div class="col-md-9">
                        <div class="input-group date">
                            <input type="text" name="start_date" class="form-control col-md-7 col-xs-12 pull-right datepicker" id="datepicker" placeholder="mm/dd/yyyy">
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback row {{ $errors->has('view_type') ? ' has-error ' : '' }}">
                    {!! Form::label('view_type', 'Style' , array('class' => 'col-md-3 control-label')); !!}
                    <div class="col-md-9">
                        <div class="input-group">
                            {!! Form::select('view_type', $view_type, null, ['id' => 'view_type', 'class' => 'form-control col-md-7 col-xs-12']); !!}
                        </div>
                    </div>
                </div>
              
              <div class="form-group has-feedback row {{ $errors->has('is_active') ? ' has-error ' : '' }}">
                {!! Form::label('is_active', trans('Active') , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <label class="switch checked" for="is_active">
                    <span class="active"><i class="fa fa-toggle-on fa-2x"></i></span>
                    <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i></span>
                    <input type="radio" name="is_active" value="1" checked>
                    <input type="radio" name="is_active" value="0">
                  </label>
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
<script type="text/javascript">
    $(document).ready(function() {
        $("#hotel_id").select2({
            allowClear: false
        });
        $("#job").select2({
            allowClear: false
        });
        $("#view_type").select2({
            allowClear: false
        });

        $('#start_time').datetimepicker({
          format: 'LT'
        })

        $('#end_time').datetimepicker({
          format: 'LT'
        })

        //Date picker
        $('#datepicker').datepicker({
            format:'mm/dd/yyyy',
        }).datepicker("setDate",'now');
    });
</script>
@endsection