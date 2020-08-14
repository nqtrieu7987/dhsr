@extends('layouts.master')

@section('template_title')
  Create multiple Jobs
@endsection

@section('content')
<style type="text/css">
.help-block{
  position: absolute;
  font-size: 12px;
}
.table td, .table th {
  padding: 1rem .75rem;
  vertical-align: middle;
  border-top: 1px solid #dee2e6;
}
.fa-fw{
  color: #aaa;
}
</style>

  <div class="container">
      <div class="row">
          <div class="col-12">
              @include('partials.form-status')
          </div>
      </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 col-md-offset-1">
        <div class="card card-default">

          <div class="card-body">
            <div class="table-responsive users-table">
                <table class="table table-striped table-condensed data-table" id="ct_blah">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Hotel</th>
                            <th>Job</th>
                            <th class="hidden-xs">Slot</th>
                            <th class="hidden-xs">Start time</th>
                            <th class="hidden-xs">End time</th>
                            <th class="hidden-xs">Start date</th>
                            <th class="hidden-xs">View type</th>
                            <th>
                              <button class="btn text-primary" style="width: 90px;font-weight: bold; font-size: 18px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add</button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 0; $i< 5; $i ++)
                        {!! Form::open(['route' => 'job.createMultiPost','method'=>'POST', 'id' => 'form'.$i]) !!}
                        {!! csrf_field() !!}
                            <tr id="row-{{$i}}">
                                <td><a onclick="$('#row-{{$i}}').html('')"><i style="font-size: 24px" class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a></td>
                                <td>{!! Form::select('hotel_id', $hotels, null, ['id' => 'hotel_id'.$i, 'class' => 'hotel_id form-control']); !!}</td>
                                <td>{!! Form::select('job_type_id', $types, null, ['id' => 'job_type_id'.$i, 'class' => 'job form-control']); !!}</td>
                                <td>
                                  {!! Form::number('slot', old('slot'), array('id' => 'slot'.$i, 'class' => 'form-control', 'placeholder' => 'Slot','style' => 'min-width: 65px; text-align: left;', 'onchange' =>'$("#err_slot'.$i.'").hide(); $("#slot'.$i.'").removeClass("has-error")')) !!}
                                  <span class="help-block">
                                      <strong id="err_slot{{$i}}"></strong>
                                  </span>
                                </td>
                                <td>
                                  <div class="input-group date" id="start_time{{$i}}" data-target-input="nearest">
                                      <input type="text" name="start_time" id="start_time_txt{{$i}}" class="form-control datetimepicker-input" data-target="#start_time{{$i}}" data-toggle="datetimepicker" style="min-width: 70px; text-align: center;" required onchange="$('#err_start_time{{$i}}').hide(); $('#start_time_txt{{$i}}').removeClass('has-error')">
                                  </div>
                                  <span class="help-block">
                                      <strong id="err_start_time{{$i}}"></strong>
                                  </span>
                                </td>
                                <td class="align-center">
                                  <div class="input-group date" id="end_time{{$i}}" data-target-input="nearest">
                                      <input type="text" name="end_time" id="end_time_txt{{$i}}" class="form-control datetimepicker-input" data-target="#end_time{{$i}}" data-toggle="datetimepicker" style="min-width: 70px; text-align: center;" required onchange="$('#err_end_time{{$i}}').hide(); $('#end_time_txt{{$i}}').removeClass('has-error')">
                                  </div>
                                  <span class="help-block">
                                      <strong id="err_end_time{{$i}}"></strong>
                                  </span>
                                </td>
                                <td class="align-center">
                                  <div class="input-group date start_date">
                                      <input type="text" name="start_date" id="start_date_txt{{$i}}" class="form-control pull-right datepicker" placeholder="dd/mm/yyyy" style="min-width: 105px" required onchange="$('#err_start_date{{$i}}').hide(); $('#start_date_txt{{$i}}').removeClass('has-error')">
                                  </div>
                                  <span class="help-block">
                                      <strong id="err_start_date{{$i}}"></strong>
                                  </span>
                                </td>
                                <td class="align-center">
                                  {!! Form::select('view_type', $view_type, null, ['id' => 'view_type'.$i, 'class' => 'view_type form-control']); !!}
                                </td>
                                <td>
                                  <input type="hidden" name="id" id="id_{{$i}}">
                                  <a onclick="saveChange({{$i}})"><i style="font-size: 24px" id="save{{$i}}" class="fa fa-fw fa-save" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                            
                        {!! Form::close() !!}
                        @endfor
                    </tbody>
                </table>
                <table width="100%" height="50%" border="0">
                    <tr>
                        <td width="50%"></td>
                    </tr>
                </table>
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
        $(".hotel_id").select2({
            allowClear: false,
            width: '180px'
        });
        $(".job").select2({
            allowClear: false,  
            width: '180px'
        });
        $(".view_type").select2({
            allowClear: false,
            width: '180px'
        });

        @for($j = 0; $j < 5; $j++)
        $('#start_time{{$j}}').datetimepicker({
          format: 'HH:mm',
          pickDate: false,
          pickSeconds: false,
          pick12HourFormat: false

        })
        $('#end_time{{$j}}').datetimepicker({
          format: 'HH:mm',
          pickDate: false,
          pickSeconds: false,
          pick12HourFormat: false
        })
        @endfor

        //Date picker
        $('.datepicker').datepicker({
            format:'dd/mm/yyyy'
        }).datepicker("setDate",'now');


        $(":button").click(function(){
            var $lastRow = $("[id$=blah] tr:not('.ui-widget-header'):last"); //grab row before the last row
            var $newRow = $lastRow.clone(); //clone it
            $newRow.find(":text").val(""); //clear out textbox values    
            $lastRow.after($newRow); //add in the new row at the end
        });

    });

function saveChange(i){
    $('#error_ajax').hide();
    var id = $('#id_'+i).val();
    var hotel_id = $('#hotel_id'+i).val();
    var job_type_id = $('#job_type_id'+i).val();
    var slot = $('#slot'+i).val();
    var start_time = $('#start_time_txt'+i).val();
    var end_time = $('#end_time_txt'+i).val();
    var start_date = $('#start_date_txt'+i).val();
    var view_type = $('#view_type'+i).val();
    var data = {
        id: id,
        hotel_id: hotel_id,
        job_type_id: job_type_id,
        slot: slot,
        start_time: start_time,
        end_time: end_time,
        start_date: start_date,
        view_type: view_type
    };
    if(slot == ''){
      $('#err_slot'+i).text("Slot not null");
      $('#slot'+i).addClass('has-error').focus();
      $('#save'+i).css({"color": "#a94442"});
      return false;
    }
    if(start_time == ''){
      $('#err_start_time'+i).text("Start time not null");
      $('#start_time_txt'+i).addClass('has-error').focus();
      $('#save'+i).css({"color": "#a94442"});
      return false;
    }
    if(end_time == ''){
      $('#err_end_time').text("End time not null");
      $('#end_time_txt'+i).addClass('has-error').focus();
      $('#save'+i).css({"color": "#a94442"});
      return false;
    }

    $.ajax({
        type : 'POST',
        url  : '{{route('job.createMultiPost')}}',
        data : data,
        success  : function (data) {
          if(data.id > 0){
            $('#id_'+i).val(data.id);
            $('#success_ajax').css({"display": "block"});
            $('#success_msg').text(data.msg);
            $('#save'+i).css({"color": "#155724"});
          }
        }
    });
}
</script>
@endsection