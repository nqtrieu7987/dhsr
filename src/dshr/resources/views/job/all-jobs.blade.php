@extends('layouts.master')

@section('template_title')
  List Job
@endsection

@section('template_linked_css')
  @include('partials.dataTableStyle')
  <style type="text/css">
      .dataTables_filter{display: none;}
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
                <div class="card panel-default">
                    {{-- <div class="card-header">

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong>List Job</strong>
                            <div class="btn-group pull-right btn-group-xs">
                                <a class="btn btn-blue-light" href="/job/create">
                                    <img class="fa-icon" src="/images/newuser_icon.png">
                                    Create new job
                                </a>
                            </div>
                        </div>
                    </div> --}}

                    <div class="card-body pd-xs-0">
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Hotel</th>
                                        <th>Job</th>
                                        <th>Current Status</th>
                                        <th>Confirm</th>
                                        <th>Cancel</th>
                                        <th>In/Out</th>
                                        <th>Start time</th>
                                        <th>End time</th>
                                        <th>Paid TimeIn</th>
                                        <th>Paid TimeOut</th>
                                        <th>BreakTime</th>
                                        <th>Total Hours</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($datas)
                                    @foreach($datas as $data)
                                        <tr>
                                            <td>{{$data->id}}</td>
                                            <td><a href="{{route('user.edit', $data->users->id)}}">{{$data->users->userName}}</a></td>
                                            <td>{{ $data->jobs != null ? array_get($hotels, $data->jobs['hotel_id']): ''}}</td>
                                            <td>{{ $data->jobs != null ? array_get($jobType, $data->jobs['job_type_id']): ''}}</td>
                                            <td>
                                                <span class="btn text-{{array_get($color_status, $data->status)}} btn-sm" id="text_{{$data->id}}">{{array_get($status, $data->status)}}</span>
                                            </td>
                                            <td class="align-center">
                                                <div id="approve_{{$data->id}}">
                                                    <span class="text-success approve changeStatus" data-id="{{$data->id}}" data-type="approve" id="approve_{{$data->id}}" value="approve">Approval</span><br>
                                                </div>
                                            </td>
                                            <td class="align-center">
                                                <div id="cancel_{{$data->id}}">
                                                    <span class="text-danger changeStatus" data-id="{{$data->id}}" data-type="cancel" id="cancel_{{$data->id}}" value="cancel">Cancel</span>
                                                </div>
                                            </td>
                                            <td>
                                                @if($data->status > 0)
                                                    <a href="{{route('job.inOut', $data->id)}}" target="_blank" class="btn btn-warning btn-sm">In/Out</a>
                                                @endif
                                            </td>
                                            <td class="align-center">{{$data->real_start}}</td>
                                            <td class="align-center">{{$data->real_end}}</td>
                                            <td class="align-center">{{$data->paidTimeIn}}</td>
                                            <td class="align-center">{{$data->paidTimeOut}}</td>
                                            <td>{{$data->breakTime}}</td>
                                            <td>{{$data->totalHours}}</td>
                                            <td>{{$data->remarks}}</td>
                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                            @if($datas)
                                {{ $datas->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.modal-approved')

@endsection

@section('footer_scripts')
    @include('scripts.datatables')

    @include('scripts.approved-modal-script')
    @include('scripts.save-modal-script')
    
<script type="text/javascript">
    $("#hotel_id").select2({
        allowClear: false
    });

    @if(request()->get('start_date'))
        $('#datepicker').datepicker({
            format:'mm/dd/yyyy',
        });
    @else
        $('#datepicker').datepicker({
            format:'mm/dd/yyyy',
        }).datepicker("setDate",'now');
    @endif

    $('#formSearch').submit(function () {
            hotel_id = $('#hotel_id').val();
            job = $('#job').val();
            if(hotel_id == 0){
                alert('Please select hotel!');
                $('#hotel_id').select2('open');
                return false;
            }
        })
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