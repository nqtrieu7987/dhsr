@extends('layouts.master')

@section('template_title')
  List Clocking
@endsection

@section('template_linked_css')
  @include('partials.dataTableStyle')
  <style type="text/css">
      .dataTables_filter{display: none;}
  </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card panel-default">
                    <div class="card-body">
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Hotel</th>
                                        <th>Job</th>
                                        <th>Start time</th>
                                        <th>End time</th>
                                        <th>Type</th>
                                        <th>Created At</th>
                                        <th>Date</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datas as $data)
                                        <tr>
                                            <td>{{$data->id}}</td>
                                            <td><a href="{{route('user.edit', $data->Users()->id)}}">{{$data->Users()->userName}}</a></td>
                                            <td>{{ $data->Jobs() != null ? array_get($hotels, $data->Jobs()['hotel_id']): ''}}
                                            <td>{{ $data->Jobs() != null ? array_get($jobType, $data->Jobs()['job_type_id']): ''}}</td>
                                            <td>{{$data->Jobs()['start_time']}}</td>
                                            <td>{{$data->Jobs()['end_time']}}</td>
                                            <td class="align-center">{{$data->type}}</td>
                                            <td class="align-center">{{$data->created_at}}</td>
                                            <td class="align-center">{{$data->date}}</td>
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
    {{--
        @include('scripts.tooltips')
    --}}
@endsection