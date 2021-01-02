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
          {!! Form::model($data, array('action' => array('JobController@update', $data->id), 'method' => 'PUT', 'files' => true)) !!}
            {!! csrf_field() !!}

            <div class="card-body">
              <div class="form-group has-feedback row {{ $errors->has('job_type_id') ? ' has-error ' : '' }}">
                {!! Form::label('job_type_id', 'Select Job Type' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::select('job_type_id', $jobType, null, ['id' => 'job', 'class' => 'form-control col-md-7 col-xs-12']); !!}
                  </div>
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
                            <input type="text" name="start_time" value="{{$data->start_time}}" class="form-control col-md-7 col-xs-12 datetimepicker-input" data-target="#start_time" data-toggle="datetimepicker">
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback row">
                    <label class="col-md-3 control-label">End Time:</label>
                    <div class="col-md-9">
                        <div class="input-group date" id="end_time" data-target-input="nearest">
                            <input type="text" name="end_time" value="{{$data->end_time}}" class="form-control col-md-7 col-xs-12 datetimepicker-input" data-target="#end_time" data-toggle="datetimepicker">
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback row">
                    <label class="col-md-3 control-label">Start Date:</label>
                    <div class="col-md-9">
                        <div class="input-group date">
                            <input type="text" name="start_date" value="{{date('m/d/Y', strtotime($data->start_date))}}" class="form-control col-md-7 col-xs-12 pull-right datepicker" id="datepicker" placeholder="mm/dd/yyyy">
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback row {{ $errors->has('view_type') ? ' has-error ' : '' }}">
                    {!! Form::label('view_type', 'Style' , array('class' => 'col-md-3 control-label')); !!}
                    <div class="col-md-9">
                        <div class="input-group">
                            {!! Form::select('view_type', $view_type, null, ['id' => 'view_type','class' => 'form-control col-md-7 col-xs-12']); !!}
                        </div>
                    </div>
                </div>
              
              <div class="form-group has-feedback row {{ $errors->has('is_active') ? ' has-error ' : '' }}">
                {!! Form::label('is_active', trans('Active') , array('class' => 'col-3 col-md-3 col-sm-3 col-xs-3 control-label')); !!}
                <div class="col-3 col-md-3 col-sm-3 col-xs-3">
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


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <div id="deleteAccount" class="tab-pane fade show active">
                            <div class="table-responsive users-table">
                              <div id="table_data" class="overflow-auto">
                                @include('partials/pagination_status', ['data' => $jobsPrev, 'name' => 'prev'])
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
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
        });
    });

$(document).ready(function(){
  $(document).on('click', '.pagination a', function(event){
    event.preventDefault(); 
    var page = $(this).attr('href').split('page=')[1];
    fetch_data(page);
  });

  function fetch_data(page)
  {
    $('#waiting').show();
  $.ajax({
    url:"/pagination/fetch_data?id={{$data->id}}&type=job&page="+page+"&jobStatus=1",
    success:function(data)
    {
      $('#waiting').hide();
      $('#table_data').html(data);
    }
  });
  }
});
</script>

<script type="text/javascript">
    $('.changeStatus').click(function(){
        $('#waiting').show();
        var idstt = this.id;
        var id = $(this).data("id");
        var type = $(this).data("type");
        var data = {
            id: id,
            model: 'alljob',
            type: type
        };
        $.ajax({
            type : 'GET',
            url  : '{{route('changeUpdateStatus')}}?id='+id+'&model=alljob&type='+type,
            data : data,
            success  : function (data) {
                $('#waiting').hide();
                if(data.status === 201){
                  //$('.approve').hide();
                  alert(data.msg);
                }else{
                  //$('#approve_cancel_'+id).hide();
                  if(data.status === 200){
                    $('#text_'+id).text('Confirm');
                    $('#text_'+id).removeClass('text-secondary');
                    $('#text_'+id).removeClass('text-danger');
                    $('#text_'+id).addClass('text-primary');
                  }else if(data.status === 202){
                    $('#text_'+id).text('Cancel');
                    $('#text_'+id).removeClass('text-secondary');
                    $('#text_'+id).removeClass('text-primary');
                    $('#text_'+id).addClass('text-danger');
                  }
                  //alert(data.msg);
                }
            }
        });
    });
</script>
@endsection