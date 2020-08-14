@extends('layouts.app')

@section('template_title')
  Danh Sách Ảnh
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
                            <strong>Danh Sách Ảnh</strong>
                            <div class="btn-group pull-right btn-group-xs">
                                <a class="btn btn-success" href="/banner/create">
                                    <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                                    Tạo mới banner
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
                                        <th>Logo</th>
                                        <th>Header</th>
                                        <th>Footer</th>
                                        <th>Background</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($banners as $banner)
                                        <tr>
                                            <td>{{$banner->id}}</td>
                                            <td><a href="{{ URL::to('banner/' . $banner->id . '/edit') }}">{{$banner->name}}</a></td>
                                            <td>
                                                @if ($banner->is_active)
                                                    <img src="/images/active.png" width="20" height="">
                                                @else
                                                    <img src="/images/deactive.png" width="20" height="">
                                                @endif
                                            </td>
                                            <td>
                                                @if($banner->logo)
                                                    <img src="{{MEDIADOMAIN.$banner->logo}}" height="30"> 
                                                @endif
                                            </td>
                                            <td>
                                                @if($banner->header)
                                                    <img src="{{MEDIADOMAIN.$banner->header}}" height="30"> 
                                                @endif
                                            </td>
                                            <td>
                                                @if($banner->footer)
                                                    <img src="{{MEDIADOMAIN.$banner->footer}}" height="30"> 
                                                @endif
                                            </td>
                                            <td>
                                                @if($banner->bg)
                                                    <img src="{{MEDIADOMAIN.$banner->bg}}" height="30"> 
                                                @endif
                                            </td>
                                            <td>
                                                {!! Form::open(array('url' => 'banner/' . $banner->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                                    {!! Form::hidden('_method', 'DELETE') !!}
                                                    {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Delete</span><span class="hidden-xs hidden-sm hidden-md"></span>', array('class' => 'btn btn-danger btn-sm','type' => 'button', 'style' =>'width: 100%;' ,'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Xóa', 'data-message' => 'Bạn có chắc muốn xóa?')) !!}
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $banners->links() }}
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