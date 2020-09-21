@extends('layouts.master')

@section('template_title')
  List View Type
@endsection

@section('template_linked_css')
  @include('partials.dataTableStyle')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card panel-default">

                    <div class="card-body pd-xs-0">
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th class="no-search no-sort">Image active</th>
                                        <th class="no-search no-sort">Image deactive</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datas as $data)
                                        <tr>
                                            <td>{{$data->id}}</td>
                                            <td><a href="{{ URL::to('view-type/' . $data->id . '/edit') }}">{{$data->name}}</a></td>
                                            <td class="align-center">
                                                @if($data->image_active)
                                                    <img onclick="showModalimage_deactive(this)" src="{{$data->image_active}}" height="50">
                                                @endif
                                            </td>
                                            <td class="align-center">
                                                @if($data->image_deactive)
                                                    <img onclick="showModalimage_deactive(this)" src="{{$data->image_deactive}}" alt="{{$data->name}}" height="50">
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
    @include('scripts.save-modal-script')
    @include('scripts.image-modal-script')
@endsection