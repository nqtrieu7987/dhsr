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
                                        <th>Job</th>
                                        <th>Status</th>
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
                                    @foreach($datas as $data)
                                        <tr>
                                            <td>{{$data->id}}</td>
                                            <td><a href="{{route('user.edit', $data->Users()->id)}}">{{$data->Users()->userName}}</a></td>
                                            <td>{{ $data->Jobs() != null ? array_get($jobType, $data->Jobs()['job_type_id']): ''}}</td>
                                            <td>
                                                @if($data->status == 3)
                                                    {!! Form::open(array('url' => 'job/approved/' . $data->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Approved')) !!}
                                                        {!! Form::hidden('_method', 'DELETE') !!}
                                                        {!! Form::hidden('remarks', $data->remarks) !!}
                                                        {!! Form::button('<span>Approved</span><span></span>', array('class' => 'btn btn-warning btn-sm','type' => 'button', 'style' =>'width: 100%;' ,'data-toggle' => 'modal', 'data-target' => '#confirmApproved', 'data-title' => 'Approved', 'data-message' => 'Are you sure?')) !!}
                                                    {!! Form::close() !!}
                                                @else
                                                    <span class="btn btn-success btn-sm">{{array_get($status, $data->status)}}</span>
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
                                </tbody>
                            </table>

                            {{ $datas->links() }}
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
@endsection