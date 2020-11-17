@extends('layouts.master')

@section('template_title')
  List Job
@endsection

@section('template_linked_css')
  @include('partials.dataTableStyle')
  <style type="text/css">
    .dataTables_filter{display: none;}
    .users-table .form-control {width: 70px;text-align: center;}
    .table-responsive{overflow-x:inherit;}
    .help-block{
      position: absolute;
      font-size: 12px;
    }
    .table thead th {
        vertical-align: top;
    }
  </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    {!! Form::open(array('route' => 'job.all-jobs', 'method' => 'GET','id' =>'formSearch', 'class' => 'form-horizontal form-label-left')) !!}
                        <div class="input-group mb-3">
                            <div class="row">
                                {!! Form::label('Hotel', 'Hotel', array('class' => 'control-label mt-2 mr-1 ml-2 hidden-xs text-right')); !!}
                                {!! Form::select('hotel_id', $hotels, request()->get('hotel_id'), ['class' => 'form-control col-md-3 col-xs-6', 'id' =>'hotel_id']); !!}

                                {!! Form::label('Date', 'Date', array('class' => 'control-label mt-2 mr-1 ml-2 hidden-xs text-right')); !!}
                                {!! Form::text('start_date', request()->get('start_date'), array('id' => 'datepicker', 'class' => 'form-control col-md-3 col-xs-6 mt-1-sx pull-right datepicker', 'placeholder' => 'mm/dd/yyyy')) !!}

                                <button class="btn bg-main mr-1 ml-1 mt-3-sx" type="submit"><i aria-hidden="true" class="fa fa-search"></i>&nbsp;Search</button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div>
                    <span class="bg-danger h4 pl-2 pr-2">Requires Attention</span>
                </div>
                <div class="card-body-white mt-3">
                    <div class="table-responsive users-table">
                        <table class="table table-striped table-condensed data-table" id="ct_blah">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Hotel</th>
                                    <th>Job</th>
                                    <th>Shift start</th>
                                    <th>Shift end</th>
                                    <th>Start date</th>
                                    <th>Hours</th>
                                    <th>Actual T-in</th>
                                    <th>Actual T-out</th>
                                    <th>Paid Time In</th>
                                    <th>Paid Time Out</th>
                                    <th>Break Time</th>
                                    <th>Total getHours</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>Save</th>
                                    <th>Approved</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($attentions)> 0)
                                @foreach($attentions as $i => $data)
                                {!! csrf_field() !!}
                                    <tr id="row-{{$data->id}}">
                                        <td style="min-width: 100px;">{{$data->users->userName}}</td>
                                        <td>{{$data->jobs->hotels->name}}</td>
                                        <td>{{$data->jobs->types->name}}</td>
                                        <td>{{$data->jobs->start_time}}</td>
                                        <td>{{$data->jobs->end_time}}</td>
                                        <td>{{$data->jobs->start_date}}</td>
                                        <td>{{getHours($data->jobs->start_time, $data->jobs->end_time)}}</td>
                                        <td>{{$data->real_start}}</td>
                                        <td>{{$data->real_end}}</td>
                                        <td>
                                          <div class="input-group date" id="paidTimeIn{{$data->id}}" data-target-input="nearest">
                                              <input type="text" name="paidTimeIn" id="paidTimeIn_txt{{$data->id}}" value="{{$data->paidTimeIn}}" class="form-control datetimepicker-input" data-target="#paidTimeIn{{$data->id}}" data-toggle="datetimepicker" required onchange="$('#err_paidTimeIn{{$data->id}}').hide(); $('#paidTimeIn_txt{{$data->id}}').removeClass('has-error')">
                                          </div>
                                          <span class="help-block">
                                              <strong class="error_ajax" id="err_paidTimeIn{{$data->id}}"></strong>
                                          </span>
                                        </td>
                                        <td class="align-center">
                                          <div class="input-group date" id="paidTimeOut{{$data->id}}" data-target-input="nearest">
                                              <input type="text" name="paidTimeOut" id="paidTimeOut_txt{{$data->id}}" value="{{$data->paidTimeOut}}" class="form-control datetimepicker-input" data-target="#paidTimeOut{{$data->id}}" data-toggle="datetimepicker" required onchange="$('#err_paidTimeOut{{$data->id}}').hide(); $('#paidTimeOut_txt{{$data->id}}').removeClass('has-error')">
                                          </div>
                                          <span class="help-block">
                                              <strong class="error_ajax" id="err_paidTimeOut{{$data->id}}"></strong>
                                          </span>
                                        </td>
                                        <td class="align-center">
                                            {!! Form::number('breakTime', old('breakTime', $data->breakTime), array('id' => 'breakTime'.$data->id, 'class' => 'form-control breakTime', 'placeholder' => 'Hour','step' =>0.1)) !!}
                                            <span class="help-block">
                                                <strong class="error_ajax" id="err_breakTime{{$data->id}}"></strong>
                                            </span>
                                        </td>
                                        <td class="align-center"><span id="totalHours{{$data->id}}">{{$data->totalHours}}</span></td>
                                        <td class="align-center">
                                            {!! Form::text('remarks', old('remarks', $data->remarks), array('id' => 'remarks'.$data->id, 'class' => 'form-control remarks', 'placeholder' => 'Remarks')) !!}
                                            <span class="help-block">
                                                <strong class="error_ajax" id="err_remarks{{$data->id}}"></strong>
                                            </span>
                                        </td>
                                        <td>
                                            <select id="status{{$data->id}}" class="form-control" style="width: 110px" name="status" @if($data->rwsConfirmed == 1) disabled @endif>
                                                <option value="1">Job Done</option>
                                                <option value="2">Job Failure</option>
                                            </select>
                                        </td>
                                        <td class="align-center">
                                          <input type="hidden" name="id" id="id_{{$data->id}}">
                                          <a onclick="saveChange({{$data->id}}, 1)"><i style="font-size: 24px" id="save{{$data->id}}" class="fa fa-fw fa-save" aria-hidden="true"></i></a>
                                        </td>
                                        <td class="align-center">
                                          <input type="hidden" name="id" id="id_{{$data->id}}">
                                          <a onclick="saveChange({{$data->id}}, 2)"><i style="font-size: 24px" id="approved{{$data->id}}" class="fa fa-check-circle" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <div class="row mt-5">
            <div class="col-sm-12">
                <div>
                    <span class="bg-warning h4 pl-2 pr-2">Pending</span>
                </div>
                <div class="card-body-white mt-3">
                    <div class="table-responsive users-table">
                        <table class="table table-striped table-condensed data-table" id="ct_blah">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Hotel</th>
                                    <th>Job</th>
                                    <th>Shift start</th>
                                    <th>Shift end</th>
                                    <th>Start date</th>
                                    <th>Hours</th>
                                    <th>Actual T-in</th>
                                    <th>Actual T-out</th>
                                    <th>Paid Time In</th>
                                    <th>Paid Time Out</th>
                                    <th>Break Time</th>
                                    <th>Total getHours</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>Approved</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($pendings)> 0)
                                @foreach($pendings as $i => $data)
                                {!! csrf_field() !!}
                                    <tr id="row-{{$data->id}}">
                                        <td style="min-width: 100px;">{{$data->users->userName}}</td>
                                        <td>{{$data->jobs->hotels->name}}</td>
                                        <td>{{$data->jobs->types->name}}</td>
                                        <td>{{$data->jobs->start_time}}</td>
                                        <td>{{$data->jobs->end_time}}</td>
                                        <td>{{$data->jobs->start_date}}</td>
                                        <td>{{getHours($data->jobs->start_time, $data->jobs->end_time)}}</td>
                                        <td>{{$data->real_start}}</td>
                                        <td>{{$data->real_end}}</td>
                                        <td>
                                          <div class="input-group date" id="paidTimeIn{{$data->id}}" data-target-input="nearest">
                                              <input type="text" name="paidTimeIn" id="paidTimeIn_txt{{$data->id}}" value="{{$data->paidTimeIn}}" class="form-control datetimepicker-input" data-target="#paidTimeIn{{$data->id}}" data-toggle="datetimepicker" required onchange="$('#err_paidTimeIn{{$data->id}}').hide(); $('#paidTimeIn_txt{{$data->id}}').removeClass('has-error')">
                                          </div>
                                          <span class="help-block">
                                              <strong class="error_ajax" id="err_paidTimeIn{{$data->id}}"></strong>
                                          </span>
                                        </td>
                                        <td class="align-center">
                                          <div class="input-group date" id="paidTimeOut{{$data->id}}" data-target-input="nearest">
                                              <input type="text" name="paidTimeOut" id="paidTimeOut_txt{{$data->id}}" value="{{$data->paidTimeOut}}" class="form-control datetimepicker-input" data-target="#paidTimeOut{{$data->id}}" data-toggle="datetimepicker" required onchange="$('#err_paidTimeOut{{$data->id}}').hide(); $('#paidTimeOut_txt{{$data->id}}').removeClass('has-error')">
                                          </div>
                                          <span class="help-block">
                                              <strong class="error_ajax" id="err_paidTimeOut{{$data->id}}"></strong>
                                          </span>
                                        </td>
                                        <td class="align-center">
                                            {!! Form::number('breakTime', old('breakTime', $data->breakTime), array('id' => 'breakTime'.$data->id, 'class' => 'form-control breakTime', 'placeholder' => 'Hour','step' =>0.1)) !!}
                                            <span class="help-block">
                                                <strong class="error_ajax" id="err_breakTime{{$data->id}}"></strong>
                                            </span>
                                        </td>
                                        <td class="align-center"><span id="totalHours{{$data->id}}">{{$data->totalHours}}</span></td>
                                        <td class="align-center">
                                            {!! Form::text('remarks', old('remarks', $data->remarks), array('id' => 'remarks'.$data->id, 'class' => 'form-control remarks', 'placeholder' => 'Remarks')) !!}
                                            <span class="help-block">
                                                <strong class="error_ajax" id="err_remarks{{$data->id}}"></strong>
                                            </span>
                                        </td>
                                        <td>
                                            <select id="status{{$data->id}}" class="form-control" style="width: 110px" name="status" @if($data->rwsConfirmed == 1) disabled @endif>
                                                <option value="1">Job Done</option>
                                                <option value="2">Job Failure</option>
                                            </select>
                                        </td>
                                        <td class="align-center">
                                          <input type="hidden" name="id" id="id_{{$data->id}}">
                                          <a onclick="saveChange({{$data->id}}, 2)"><i style="font-size: 24px" id="approved{{$data->id}}" class="fa fa-check-circle" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mt-5">
            <div class="col-sm-12">
                <div>
                    <span class="bg-success h4 pl-2 pr-2">Approved</span>
                </div>
                <div class="card-body-white mt-3">
                    <div class="table-responsive users-table">
                        <table class="table table-striped table-condensed data-table" id="ct_blah">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Hotel</th>
                                    <th>Job</th>
                                    <th>Shift start</th>
                                    <th>Shift end</th>
                                    <th>Start date</th>
                                    <th>Hours</th>
                                    <th>Actual T-in</th>
                                    <th>Actual T-out</th>
                                    <th>Paid Time In</th>
                                    <th>Paid Time Out</th>
                                    <th>Break Time</th>
                                    <th>Total getHours</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($approveds)> 0)
                                @foreach($approveds as $i => $data)
                                {!! csrf_field() !!}
                                    <tr id="row-{{$data->id}}">
                                        <td style="min-width: 100px;">{{$data->users->userName}}</td>
                                        <td>{{$data->jobs->hotels->name}}</td>
                                        <td>{{$data->jobs->types->name}}</td>
                                        <td>{{$data->jobs->start_time}}</td>
                                        <td>{{$data->jobs->end_time}}</td>
                                        <td>{{$data->jobs->start_date}}</td>
                                        <td>{{getHours($data->jobs->start_time, $data->jobs->end_time)}}</td>
                                        <td>{{$data->real_start}}</td>
                                        <td>{{$data->real_end}}</td>
                                        <td>{{$data->paidTimeIn}}</td>
                                        <td class="align-center">{{$data->paidTimeOut}}</td>
                                        <td class="align-center">{{$data->breakTime}}</td>
                                        <td class="align-center"><span id="totalHours{{$data->id}}">{{$data->totalHours}}</span></td>
                                        <td class="align-center">{{$data->remarks}}</td>
                                        <td><span class="{{$data->rwsConfirmed == 1 ? 'text-success' : 'text-danger'}} font-weight-bold">{{$data->rwsConfirmed == 1 ? 'Job Done' : 'Job Failure'}}</span></td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
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

        @if($datas)
        @foreach($datas as $j => $value)
        $('#paidTimeIn{{$value->id}}').datetimepicker({
          format: 'HH:mm',
          pickDate: false,
          pickSeconds: false,
          pick12HourFormat: false

        })
        $('#paidTimeOut{{$value->id}}').datetimepicker({
          format: 'HH:mm',
          pickDate: false,
          pickSeconds: false,
          pick12HourFormat: false
        })
        @endforeach
        @endif

        //Date picker
        @if(request()->get('start_date') == null)
        $('.datepicker').datepicker({
            format:'dd/mm/yyyy'
        }).datepicker("setDate",'now');
        @else
        $('.datepicker').datepicker({
            format:'dd/mm/yyyy'
        });
        @endif

        $(":button").click(function(){
            var $lastRow = $("[id$=blah] tr:not('.ui-widget-header'):last"); //grab row before the last row
            var $newRow = $lastRow.clone(); //clone it
            $newRow.find(":text").val(""); //clear out textbox values    
            $lastRow.after($newRow); //add in the new row at the end
        });

    });
var els = document.getElementsByClassName('has-error');
var red = document.getElementsByClassName('red');
function saveChange(i, type){
    removeClasses(els,'has-error');
    removeClasses(red,'red');
    $('.error_ajax').hide();
    var breakTime = $('#breakTime'+i).val();
    var remarks = $('#remarks'+i).val();
    var status = $('#status'+i).val();
    var paidTimeIn = $('#paidTimeIn_txt'+i).val();
    var paidTimeOut = $('#paidTimeOut_txt'+i).val();
    var data = {
        id: i,
        type: type,
        breakTime: breakTime,
        remarks: remarks,
        status: status,
        paidTimeIn: paidTimeIn,
        paidTimeOut: paidTimeOut
    };
    if(paidTimeIn == ''){
      $('#err_paidTimeIn'+i).text("Start time not null");
      $('#paidTimeIn_txt'+i).addClass('has-error').focus();
      if(type == 1){
        $('#save'+i).addClass('red');
      }else{
        $('#approved'+i).addClass('red');
      }
      return false;
    }
    if(paidTimeOut == ''){
      $('#err_paidTimeOut'+i).text("End time not null");
      $('#paidTimeOut_txt'+i).addClass('has-error').focus();
      if(type == 1){
        $('#save'+i).addClass('red');
      }else{
        $('#approved'+i).addClass('red');
      }
      return false;
    }

    $.ajax({
        type : 'POST',
        url  : '{{route('job.inOutAJ')}}',
        data : data,
        success  : function (data) {
          if(data.id > 0){
            $('#id_'+i).val(data.id);
            $('#totalHours'+i).text(data.totalHours);
            $('#success_ajax').css({"display": "block"});
            $('#success_msg').text(data.msg);
            if(data.type == 1){
                $('#save'+i).css({"color": "#0c0"});
            }else{
                $('#approved'+i).css({"color": "#0c0"});
            }
          }
        }
    });
}

function removeClasses (item, clas) {
  while (item[0]) {
    item[0].classList.remove(clas);
  }
}
</script>
@endsection