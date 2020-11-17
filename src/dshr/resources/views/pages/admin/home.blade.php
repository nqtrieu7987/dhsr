@extends('layouts.master')

@section('template_title')
    Welcome {{ Auth::user()->name }}
@endsection


@section('template_linked_css')
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/dataTables.bootstrap.min.css')}}">
@endsection

@section('content')
@if(count($dataJobs)> 0)
  <div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card panel-default">
                <div class="card-body pd-xs-0">
                    <div class="table-responsive users-table">
                        <table class="table table-striped table-condensed data-table">
                            <thead>
                                <tr>
                                    <!-- <th>Hotel</th> -->
                                    <th>#</th>
                                    <th>Job</th>
                                    <th>User</th>
                                    <th>Slot</th>
                                    <th>Shift Start</th>
                                    <th>Shift End</th>
                                    <th>Start Date</th>
                                    <th>Paid Time In</th>
                                    <th>Paid Time Out</th>
                                    <th>Break Time</th>
                                    <th>Total Hours</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; $j = 1 @endphp
                                @foreach($dataJobs as $k => $jobs)
                                  <tr @if($j % 2 == 0) style="background-color: #1E3458; color: white;" @else style="background-color: #1E3458; color: white;" @endif>
                                    <!-- <td rowspan="{{count($jobs)+ 1}}" style="border:1px solid #ccc"><b>{{$k}}</b></td> -->
                                    <td colspan="12" style="border:1px solid #ccc"><b>{{$k}}</b></td>
                                  </tr>
                                  @php $j++ @endphp
                                  @php $current_job =''; $current_slot=0 @endphp 
                                  @foreach($jobs as $job)
                                      @if (($current_job) !== ($job->jobs->types->name))
                                        @php $current_job = ($job->jobs->types->name);
                                        $current_slot = 1; @endphp
                                      @else 
                                        @php $current_slot++; @endphp
                                      @endif
                                      <tr @if($i % 2 == 0) style="background-color: rgba(0,0,0,.05)" @else style="background-color: rgba(0,0,0,0)" @endif>
                                          <td>{{$i}}.</td>
                                          <td>{{$current_job}}</td>
                                          <td><a href="{{route('user.edit', $job->users->id)}}">{{$job->users->userName}}</a></td>
                                          <td>{{$current_slot}}</td>
                                          <td>{{$job->jobs->start_time}}</td>
                                          <td>{{$job->jobs->end_time}}</td>
                                          <td>{{$job->jobs->start_date}}</td>
                                          <td>{{$job->paidTimeIn}}</td>
                                          <td>{{$job->paidTimeOut}}</td>
                                          <td>{{$job->breakTime}}</td>
                                          <td>{{$job->totalHours}}</td>
                                          <td>{{$job->remarks}}</td>
                                      </tr>
                                      @php $i++ @endphp
                                  @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@endif

    {{-- <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-success">
                  <div class="card-header">
                    <h3 class="card-title">Job of hotel</h3>

                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="chart">
                      <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                  </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="card card-info">
                  <div class="card-header">
                    <h3 class="card-title">Stacked Bar Chart</h3>

                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="chart">
                      <canvas id="stackedBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div> --}}

@endsection