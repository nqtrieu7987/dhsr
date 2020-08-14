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
                                  <tr @if($j % 2 == 0) style="background-color: rgba(0,0,0,0)" @else style="background-color: rgba(0,0,0,.05)" @endif>
                                    <!-- <td rowspan="{{count($jobs)+ 1}}" style="border:1px solid #ccc"><b>{{$k}}</b></td> -->
                                    <td colspan="12" style="border:1px solid #ccc"><b>{{$k}}</b></td>
                                  </tr>
                                  @php $j++ @endphp
                                  @php $current_job =''; $current_slot=0 @endphp 
                                  @foreach($jobs as $job)
                                      @if (($current_job) !== ($job->Jobs()->Types()->name))
                                        @php $current_job = ($job->Jobs()->Types()->name);
                                        $current_slot = 1; @endphp
                                      @else 
                                        @php $current_slot++; @endphp
                                      @endif
                                      <tr @if($i % 2 == 0) style="background-color: rgba(0,0,0,.05)" @else style="background-color: rgba(0,0,0,0)" @endif>
                                          <td>{{$i}}.</td>
                                          <td>{{$current_job}}</td>
                                          <td><a href="{{route('user.edit', $job->Users()->id)}}">{{$job->Users()->userName}}</a></td>
                                          <td>{{$current_slot}}</td>
                                          <td>{{$job->Jobs()->start_time}}</td>
                                          <td>{{$job->end_time}}</td>
                                          <td>{{$job->start_date}}</td>
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

@section('footer_scripts')
<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    // var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

    /*var areaChartData = {
      labels  : [
        @foreach($datas as $k => $data)
            @if($k < count($datas)-1)
            '{{$data->Hotels()->name}}',
            @else
            '{{$data->Hotels()->name}}'
            @endif
        @endforeach
      ],
      datasets: [
        {
          label               : 'Job number',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [
            @foreach($datas as $k => $data)
                @if($k < count($datas)-1)
                '{{$data->tong}}',
                @else
                '{{$data->tong}}'
                @endif
            @endforeach
          ]
        },
        {
          label               : 'Job Complete',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [65, 59, 80, 81, 56, 55, 40,40]
        },
      ]
    }

    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }*/

    // This will get the first returned node in the jQuery collection.
    // var areaChart       = new Chart(areaChartCanvas, { 
    //   type: 'line',
    //   data: areaChartData, 
    //   options: areaChartOptions
    // })

    //-------------
    //- LINE CHART -
    //--------------
    // var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
    // var lineChartOptions = jQuery.extend(true, {}, areaChartOptions)
    // var lineChartData = jQuery.extend(true, {}, areaChartData)
    // lineChartData.datasets[0].fill = false;
    // lineChartData.datasets[1].fill = false;
    // lineChartOptions.datasetFill = false

    // var lineChart = new Chart(lineChartCanvas, { 
    //   type: 'line',
    //   data: lineChartData, 
    //   options: lineChartOptions
    // })

    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    // var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    // var donutData        = {
    //   labels: [
    //       'Chrome', 
    //       'IE',
    //       'FireFox', 
    //       'Safari', 
    //       'Opera', 
    //       'Navigator', 
    //   ],
    //   datasets: [
    //     {
    //       data: [700,500,400,600,300,100],
    //       backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
    //     }
    //   ]
    // }
    // var donutOptions     = {
    //   maintainAspectRatio : false,
    //   responsive : true,
    // }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    // var donutChart = new Chart(donutChartCanvas, {
    //   type: 'doughnut',
    //   data: donutData,
    //   options: donutOptions      
    // })

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    // var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    // var pieData        = donutData;
    // var pieOptions     = {
    //   maintainAspectRatio : false,
    //   responsive : true,
    // }
    // //Create pie or douhnut chart
    // // You can switch between pie and douhnut using the method below.
    // var pieChart = new Chart(pieChartCanvas, {
    //   type: 'pie',
    //   data: pieData,
    //   options: pieOptions      
    // })

    //-------------
    //- BAR CHART -
    //-------------

    
    /*var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = jQuery.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    var barChart = new Chart(barChartCanvas, {
      type: 'bar', 
      data: barChartData,
      options: barChartOptions
    })

    //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
    var stackedBarChartData = jQuery.extend(true, {}, barChartData)

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true
        }]
      }
    }

    var stackedBarChart = new Chart(stackedBarChartCanvas, {
      type: 'bar', 
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })*/
  })
</script>
@endsection
