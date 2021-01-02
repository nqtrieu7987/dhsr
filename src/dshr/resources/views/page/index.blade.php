@extends('layouts.app')

@section('template_title')
  Danh Sách Landingpage
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
                <div class="x_content">
                   {!! Form::open(array('route' => 'page.index','method' => 'get', 'class' => 'form-horizontal form-label-left') ) !!}
                    <div class="row">
                            <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                <div class="">
                                    {!! Form::text('name', null, array('class' => 'form-control col-md-12 col-xs-12', 'placeholder' => 'Nhập tên')) !!}
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                <button type="submit" class="btn btn-primary btm-sm"><i class="fa fa-search" aria-hidden="true"></i><small> Search</small></button>
                                <a class="btn btn-warning" href="{{ route('page.index') }}">Refresh</a>
                            </div>
                        <div class="col-md-4 col-sm-12 col-xs-12 text-right">
                        </div>
                    </div>
                    {!! Form::close() !!}
               </div> 
            </div>

            <div class="col-sm-12">
                <div class="card panel-default">
                    <div class="card-header">

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong>Danh Sách Landingpage</strong>
                            <div class="btn-group pull-right btn-group-xs">
                                <a class="btn btn-success" href="/page/create">
                                    <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                                    Tạo mới Landingpage
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive users-table overflow-auto">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th class="hidden-xs">Trạng thái</th>
                                        <th>Viettel</th>
                                        <th>Mobi</th>
                                        <th>Vina</th>
                                        <th>Style</th>
                                        <th colspan="2">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pages as $page)
                                        <tr>
                                            <td>{{$page->id}}</td>
                                            <td><a href="{{ URL::to('page/' . $page->id . '/edit') }}">{{$page->name}}</a></td>
                                            <td>
                                                @if ($page->is_active)
                                                    <img src="/images/active.png" width="20" height="">
                                                @else
                                                    <img src="/images/deactive.png" width="20" height="">
                                                @endif
                                            </td>
                                            <td>{{array_get($page->Commands(), 'viettel')}}</td>
                                            <td>{{array_get($page->Commands(), 'mobiphone')}}</td>
                                            <td>{{array_get($page->Commands(), 'vinaphone')}}</td>
                                            <td>{{$page->style}}</td>
                                            <td>
                                                <a class="btn btn-sm btn-success btn-block" target="_blank" href="{{ route('home') }}/{{$page->url}}/?p={{$page->id}}" data-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-pencil fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Show</span><span class="hidden-xs hidden-sm hidden-md"> </span>
                                                </a>
                                            </td>
                                            <td>
                                                {!! Form::open(array('url' => 'page/' . $page->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                                    {!! Form::hidden('_method', 'DELETE') !!}
                                                    {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Delete</span><span class="hidden-xs hidden-sm hidden-md"></span>', array('class' => 'btn btn-danger btn-sm','type' => 'button', 'style' =>'width: 100%;' ,'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Xóa', 'data-message' => 'Bạn có chắc muốn xóa?')) !!}
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $pages->links() }}
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