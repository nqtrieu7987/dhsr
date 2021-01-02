@extends('layouts.app')

@section('template_title')
  Danh Sách Tin Tức
@endsection

@section('template_linked_css')
  @include('partials.dataTableStyle')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="card-header">

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong>Danh Sách Tin Tức</strong>
                            <div class="btn-group pull-right btn-group-xs">
                                <a class="btn btn-success" href="/article/create">
                                    <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                                    Tạo mới tin tức
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">

                        <div class="table-responsive users-table overflow-auto">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tiêu đề</th>
                                        <th>LandingPage</th>
                                        <th>Hình ảnh</th>
                                        <th>Vị trí bài viết</th>
                                        <th>Trạng thái</th>
                                        <th>Icon Play Video</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sub="."; ?>
                        <?php $index = 1;?>
                        @foreach($articles as $article)
                            <tr>
                                <td>{{$article->id}}</td>
                                <td style="max-width: 250px"><a href="{{ route('article.edit', [$article->id])}}">{!!$article->title!!}</a></td>
                                <td>{{array_get($pages, $article->page_id)}}</td>
                                <td> @if($article->image != null || $article->image !='')<img src="{!!MEDIADOMAIN.$article->image!!}" height="30">@endif</td> 
                                <td>{{$article->code}}</td>
                                <td style="text-align: -webkit-center;">
                                    @if ($article->is_active)
                                    <img src="{!!MEDIADOMAIN!!}/images/tick.png" width="20" height="">
                                    @endif
                                </td>
                                <td >
                                    @if ($article->is_hot)
                                    <img src="{!!MEDIADOMAIN!!}/images/tick.png" width="20" height="">
                                    @endif
                                </td>
                                <td>
                                    {!! Form::open(array('url' => 'article/' . $article->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                        {!! Form::hidden('_method', 'DELETE') !!}
                                        {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Delete</span><span class="hidden-xs hidden-sm hidden-md"></span>', array('class' => 'btn btn-danger btn-sm','type' => 'button', 'style' =>'width: 100%;' ,'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Xóa', 'data-message' => 'Bạn có chắc muốn xóa?')) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            <?php $index ++;?>
                        @endforeach
                                </tbody>
                            </table>

                            {{ $articles->links() }}
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