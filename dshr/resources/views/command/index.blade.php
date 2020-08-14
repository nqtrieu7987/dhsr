@extends('layouts.app')

@section('template_title')
  Danh Sách Command
@endsection

@section('template_linked_css')
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/dataTables.bootstrap.min.css')}}">
    <style type="text/css" media="screen">
        .users-table {
            border: 0;
        }
        .users-table tr td:first-child {
            padding-left: 15px;
        }
        .users-table tr td:last-child {
            padding-right: 15px;
        }
        .users-table.table-responsive,
        .users-table.table-responsive table {
            margin-bottom: 0;
        }

    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card panel-default">
                    <div class="card-header">

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong>Danh Sách Đầu số</strong>
                            <div class="btn-group pull-right btn-group-xs">
                                <a class="btn btn-success" href="/command/create">
                                    <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                                    Tạo mới command
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th class="hidden-xs">Trạng thái</th>
                                        <th>Viettel</th>
                                        <th>Mobiphone</th>
                                        <th>Vinaphone</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($commands as $command)
                                        <tr>
                                            <td>{{$command->id}}</td>
                                            <td><a href="{{ URL::to('command/' . $command->id . '/edit') }}">{{$command->name}}</a></td>
                                            <td>
                                                @if ($command->is_active)
                                                    <img src="/images/active.png" width="20" height="">
                                                @else
                                                    <img src="/images/deactive.png" width="20" height="">
                                                @endif
                                            </td>
                                            <td>{{$command->viettel}}</td>
                                            <td>{{$command->mobiphone}}</td>
                                            <td>{{$command->vinaphone}}</td>
                                            <td>
                                                {!! Form::open(array('url' => 'command/' . $command->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                                    {!! Form::hidden('_method', 'DELETE') !!}
                                                    {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Delete</span><span class="hidden-xs hidden-sm hidden-md"></span>', array('class' => 'btn btn-danger btn-sm','type' => 'button', 'style' =>'width: 100%;' ,'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Xóa', 'data-message' => 'Bạn có chắc muốn xóa?')) !!}
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $commands->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.modal-delete')

@endsection

@section('footer_scripts')
    @include('scripts.datatables')

    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    {{--
        @include('scripts.tooltips')
    --}}
@endsection