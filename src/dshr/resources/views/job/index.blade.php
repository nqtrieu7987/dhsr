@extends('layouts.master')

@section('template_title')
Job List
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
                    {!! Form::open(array('route' => 'job.index', 'method' => 'GET', 'class' => 'form-horizontal form-label-left')) !!}
                        <div class="input-group mb-3">
                            <div class="row">
                                {!! Form::label('Hotel', 'Hotel', array('class' => 'control-label mt-2 mr-2 hidden-xs text-right')); !!}
                                {!! Form::select('hotel_id', $hotels, request()->get('hotel_id'), ['class' => 'form-control mt-2 col-md-3 col-xs-6', 'id' =>'hotel_id']); !!}

                                {!! Form::label('Job', 'Job', array('class' => 'control-label mt-2 mr-2 ml-3 hidden-xs text-right')); !!}
                                {!! Form::select('job', $types, request()->get('job'), ['class' => 'form-control mt-2 col-md-3 col-xs-6', 'id' =>'job']); !!}

                                {!! Form::label('Slot', 'Slot', array('class' => 'control-label mt-2 mr-2 ml-3 hidden-xs text-right')); !!}
                                {!! Form::select('slot', $slots, request()->get('slot'), ['class' => 'form-control mt-2 col-md-3 col-xs-6', 'id' =>'slot']); !!}
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
                            <strong>Job List</strong>
                            <div class="btn-group pull-right btn-group-xs">
                                <a class="btn btn-blue-light" href="/job/create">
                                    <img class="fa-icon" src="/images/newuser_icon.png">
                                    Create new job
                                </a>
                            </div>
                        </div>
                    </div> --}}

                    <div class="card-body pd-xs-0">
                        <div class="table-responsive users-table overflow-auto">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Job</th>
                                        <th>Hotel</th>
                                        <th>Slot</th>
                                        <th>Current Slot</th>
                                        <th>Start time</th>
                                        <th>End time</th>
                                        <th>Start date</th>
                                        <th>View type</th>
                                        <th class="no-search no-sort">Status</th>
                                        <th class="no-search no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datas as $data)
                                        <tr>
                                            <td>{{$data->id}}</td>
                                            <td><a href="{{ URL::to('job/' . $data->id . '/edit') }}">{{$data->types->name}}</a></td>
                                            <td>{{$data->hotels->name}}</td>
                                            <td>{{$data->slot}}</td>
                                            <td>{{$data->current_slot}}</td>
                                            <td class="align-center">{{$data->start_time}}</td>
                                            <td class="align-center">{{$data->end_time}}</td>
                                            <td class="align-center">{{$data->start_date}}</td>
                                            <td>{{array_get($view_type, $data->view_type)}}</td>
                                            <td class="align-center" id="img_status_{{$data->id}}">
                                                {!! \App\Helper\VtHelper::checkStatusIcon($data->is_active)!!}
                                            </td>
                                            <td class="align-center">
                                                @if( $data->is_active == 1)
                                                    <span class="{{$data->is_active == 1 ? 'text-danger' : 'text-success'}} changeStatus" data-id="{{$data->id}}" id="status_{{$data->id}}" value="Deactivated">Deactivated</span>
                                                @else
                                                    <span class="{{$data->is_active == 1 ? 'text-danger' : 'text-success'}} changeStatus" data-id="{{$data->id}}" id="status_{{$data->id}}" value="Active">Active</span>
                                                @endif
                                            </td>
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

@endsection

@section('footer_scripts')
    @include('scripts.datatables')

    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    <script type="text/javascript">
        $("#hotel_id").select2({
            allowClear: false
        });
        $("#job").select2({
            allowClear: false
        });
        $("#slot").select2({
            allowClear: false
        });

        $('.changeStatus').click(function(){
            $('#waiting').show();
            var idstt = this.id;
            var id = $(this).data("id");
            var data = {
                id: id,
                model: 'job',
            };
            $.ajax({
                type : 'POST',
                url  : '{{route('change.status')}}',
                data : data,
                success  : function (data) {
                    $('#waiting').hide();
                    if(data.msg === 0){
                        $('#'+idstt).removeClass('text-success');
                        $('#'+idstt).addClass('text-danger');
                        $('#'+idstt).text('Deactivated');
                        $('#img_'+idstt).html('<img class="status-icon" src="/images/Active.png">');
                    }else{
                        $('#'+idstt).removeClass('text-danger');
                        $('#'+idstt).addClass('text-success');
                        $('#'+idstt).text('Active');
                        $('#img_'+idstt).html('<img class="status-icon" src="/images/Deactivated.png">');
                    }
                }
            });
        });
    </script>
@endsection