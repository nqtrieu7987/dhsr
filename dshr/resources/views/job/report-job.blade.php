@extends('layouts.master')

@section('template_title')
  List Job
@endsection

@section('template_linked_css')
  @include('partials.dataTableStyle')
    <style type="text/css" media="screen">
        .dataTables_filter{display: none;}
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    {!! Form::open(array('route' => 'report.job', 'method' => 'GET', 'class' => 'form-horizontal form-label-left', 'id' => 'reportJob')) !!}
                        <div class="input-group mb-3">
                            <div class="row">
                                {!! Form::label('Hotel', 'Hotel', array('class' => 'control-label mt-2 mr-1 ml-2 hidden-xs text-right')); !!}
                                {!! Form::select('hotel_id', $hotels, request()->get('hotel_id'), ['class' => 'form-control col-md-3 col-xs-6', 'id' =>'hotel_id']); !!}

                                {!! Form::label('Job', 'Job', array('class' => 'control-label mt-2 mr-1 ml-2 hidden-xs text-right')); !!}
                                {!! Form::select('job', $jobType, request()->get('job'), ['class' => 'form-control col-md-3 col-xs-6', 'id' =>'job']); !!}

                                {!! Form::label('Date', 'Date', array('class' => 'control-label mt-2 mr-1 ml-2 hidden-xs text-right')); !!}
                                {!! Form::text('start_date', request()->get('start_date'), array('id' => 'datepicker', 'class' => 'form-control col-md-2 col-xs-4 mt-1-sx pull-right datepicker', 'placeholder' => 'dd/mm/yyyy')) !!}
                                <button class="btn bg-main mr-1 ml-1 mt-3-sx" type="submit"><i aria-hidden="true" class="fa fa-search"></i>&nbsp;Search</button>
                                <a href="{{route('job.export-excel', request()->all())}}" onclick="checkHotelJob()" class="btn btn-success mt-3-sx"><i class="fa fa-download"></i></a>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card panel-default">

                    <div class="card-body pd-xs-0">
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Job</th>
                                        <th>Status</th>
                                        <th class="hidden-xs">Start time</th>
                                        <th class="hidden-xs">End time</th>
                                        <th class="hidden-xs">Paid TimeIn</th>
                                        <th class="hidden-xs">Paid TimeOut</th>
                                        <th>BreakTime</th>
                                        <th>Total Hours</th>
                                        <th class="hidden-xs">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($check_search && $datas)
                                    @foreach($datas as $k => $data)
                                        <tr>
                                            <td>{{$k+1}}</td>
                                            <td><a href="{{route('user.edit', $data->Users()->id)}}">{{$data->Users()->userName}}</a></td>
                                            <td>{{ $data->Jobs() != null ? array_get($jobType, $data->Jobs()['job_type_id']): ''}}</td>
                                            <td>
                                                @if($data->status == 3)
                                                    {!! Form::open(array('url' => 'job/approved/' . $data->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Approved')) !!}
                                                        {!! Form::hidden('_method', 'DELETE') !!}
                                                        {!! Form::hidden('remarks', $data->remarks) !!}
                                                        {!! Form::button('<span class="hidden-xs hidden-sm">Approved</span><span class="hidden-xs hidden-sm hidden-md"></span>', array('class' => 'btn btn-warning btn-sm','type' => 'button', 'style' =>'width: 100%;' ,'data-toggle' => 'modal', 'data-target' => '#confirmApproved', 'data-title' => 'Approved', 'data-message' => 'Are you sure?')) !!}
                                                    {!! Form::close() !!}
                                                @else
                                                    <span class="btn btn-success btn-sm">{{array_get($status, $data->status)}}</span>
                                                @endif
                                            </td>
                                            <td>{{$data->real_start}}</td>
                                            <td>{{$data->real_end}}</td>
                                            <td>{{$data->paidTimeIn}}</td>
                                            <td>{{$data->paidTimeOut}}</td>
                                            <td>{{$data->breakTime}}</td>
                                            <td>{{$data->totalHours}}</td>
                                            <td>{{$data->remarks}}</td>
                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
    @include('scripts.datatables-asc')
    @include('scripts.save-modal-script')
    {{--
        @include('scripts.tooltips')
    --}}
    <script type="text/javascript">
        $("#hotel_id").select2({
            allowClear: false
        });
        $("#job").select2({
            allowClear: false
        });

        @if(request()->get('start_date'))
            $('#datepicker').datepicker({
                format:'dd/mm/yyyy',
            });
        @else
            $('#datepicker').datepicker({
                format:'dd/mm/yyyy',
            }).datepicker("setDate",'now');
        @endif


        $('#reportJob').submit(function () {
            hotel_id = $('#hotel_id').val();
            job = $('#job').val();
            if(hotel_id == 0){
                alert('Please select hotel!');
                $('#hotel_id').select2('open');
                return false;
            }
            if(job == 0){
                alert('Please select job!');
                $('#job').select2('open');
                return false;
            }
        })

        function checkHotelJob(){
            hotel_id = $('#hotel_id').val();
            job = $('#job').val();
            if(hotel_id == 0){
                alert('Please select hotel!');
                $('#hotel_id').select2('open');
                return false;
            }
            if(job == 0){
                alert('Please select job!');
                $('#job').select2('open');
                return false;
            }
        }
    </script>
@endsection