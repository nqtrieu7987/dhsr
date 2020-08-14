@extends('layouts.app')

@section('content')
<section class="content-header">
    <h3>Thông tin chi tiết</h3>
</section>
<section class="content">
<div class="x_panel">
    <div class="x_content">
        <div class="row ">
            @if($article->is_approve ==0 && Auth::user()->superadmin==1)
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div style="font-size: 20px; font-weight: 600; text-align: center; margin-bottom: 20px;">Dữ liệu mới</div>
            @else
            <div class="col-md-12 col-sm-12 col-xs-12">
            @endif
                <table class="table table-hover">
                    <tr>
                        <th class="border_show_th">Tiêu đề</th>
                        <td class="border_show_td" colspan="5" >{!!$article->title!!}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Tiêu đề (en)</th>
                        <td class="border_show_td" colspan="5">{!!$article->title_en!!}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Header</th>
                        <td class="border_show_td" colspan="5" style="border-top: none !important;">{!!$article->header!!}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Header (en)</th>
                        <td class="border_show_td"  colspan="5">{!!$article->header_en!!}</td>
                    </tr>
                    <tr style="height: 60px; "> 
                        <th class="border_show_th">Vị trí Bài viết</th>
                        <td class="border_show_th" >{!!$article->code!!}</td>

                        <th class="border_show_th">Trạng thái</th>
                        <td  class="border_show_th">
                            @if ($article->is_active)
                            <img src="{!!MEDIADOMAIN!!}/images/tick.png" width="20" height="">
                            @else
                            <img src="{!!MEDIADOMAIN!!}/images/tickno.png" width="20" height="">
                            @endif
                        </td>
                        <th class="border_show_th">Hot</th>
                        <td class="border_show_th">
                            @if ($article->is_hot)
                            <img src="{!!MEDIADOMAIN!!}/images/tick.png" width="20" height="">
                            @else
                            <img src="{!!MEDIADOMAIN!!}/images/tickno.png" width="20" height="">
                            @endif
                        </td>
                    </tr>
                   
                    <tr style="height: 60px;">
                        <th class="border_show_th">Chuyên mục</th>
                        @if($article->category_id != 0)
                            <td class="border_show_th" colspan="3">{!!$article->getCategory->name!!}</td>
                        @else
                            <td class="border_show_th"></td>
                        @endif
                        <th class="border_show_th">Ngày xuất bản</th>
                        <td class="border_show_th" >{!!$article->published_time!!}</td>
                        
                    </tr>
                    <tr>
                        <th class="border_show_th">Hình ảnh</th>
                        <td class="border_show_td" style="border-top: 1px solid #ddd !important;" colspan="5">@if ($article->image)<img class="img-responsive image-show" src="{!! MEDIADOMAIN.$article->image !!}">@endif</td>
                    </tr>
                    <tr >
                        <th class="border_show_th">Nội dung</th>
                        <td class="border_show_td_noneborder" colspan="5">
                            <textarea name="content" readonly="true">{!!$article->content!!}</textarea>
                            <script>
                                CKEDITOR.replace( 'content',{height:'150px',} );
                            </script>
                        </td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Nội dung (en)</th>
                        <td class="border_show_td_noneborder" colspan="5">
                            <textarea name="content_en" readonly="true">{!!$article->content_en!!}</textarea>
                            <script>
                                CKEDITOR.replace( 'content_en',{height: '150px',} );
                            </script>
                        </td>
                    </tr>
                </table>    
            </div>
            @if($article->is_approve ==0 && Auth::user()->superadmin==1)
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div style="font-size: 20px; font-weight: 600; text-align: center; margin-bottom: 20px;">Dữ liệu cũ</div>
                <table class="table table-hover">
                    <tr>
                        <th class="border_show_th">Tiêu đề</th>
                        <td class="border_show_td" colspan="5" >{!!$article->title_fn!!}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Tiêu đề (en)</th>
                        <td class="border_show_td" colspan="5">{!!$article->title_en_fn!!}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Header</th>
                        <td class="border_show_td" colspan="5" style="border-top: none !important;">{!!$article->header_fn!!}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Header (en)</th>
                        <td class="border_show_td"  colspan="5">{!!$article->header_en_fn!!}</td>
                    </tr>
                    <tr style="height: 60px; "> 
                        <th class="border_show_th">Vị trí Bài viết</th>
                        <td class="border_show_th" >{!!$article->code_fn!!}</td>

                        <th class="border_show_th">Trạng thái</th>
                        <td  class="border_show_th">
                            @if ($article->is_active_fn)
                            <img src="{!!MEDIADOMAIN!!}/images/tick.png" width="20" height="">
                            @else
                            <img src="{!!MEDIADOMAIN!!}/images/tickno.png" width="20" height="">
                            @endif
                        </td>
                        <th class="border_show_th">Hot</th>
                        <td class="border_show_th">
                            @if ($article->is_hot_fn)
                            <img src="{!!MEDIADOMAIN!!}/images/tick.png" width="20" height="">
                            @else
                            <img src="{!!MEDIADOMAIN!!}/images/tickno.png" width="20" height="">
                            @endif
                        </td>
                    </tr>
                   
                    <tr style="height: 60px;">
                        <th class="border_show_th">Chuyên mục</th>
                        @if($article->category_id != 0)
                            <td class="border_show_th" colspan="3">{!!$article->getCategory->name_fn!!}</td>
                        @else
                            <td class="border_show_th"></td>
                        @endif
                        <th class="border_show_th">Ngày xuất bản</th>
                        <td class="border_show_th" >{!!$article->published_time_fn!!}</td>
                        
                    </tr>
                    <tr>
                        <th class="border_show_th">Hình ảnh</th>
                        <td class="border_show_td" style="border-top: 1px solid #ddd !important;" colspan="5">@if ($article->image_fn)<img class="img-responsive image-show" src="{!! MEDIADOMAIN.$article->image !!}">@endif</td>
                    </tr>
                    <tr >
                        <th class="border_show_th">Nội dung</th>
                        <td class="border_show_td_noneborder" colspan="5">
                            <textarea name="content_fn" readonly="true">{!!$article->content_fn!!}</textarea>
                            <script>
                                CKEDITOR.replace( 'content_fn',{height:'150px',} );
                            </script>
                        </td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Nội dung (en)</th>
                        <td class="border_show_td_noneborder" colspan="5">
                            <textarea name="content_en_fn" readonly="true">{!!$article->content_en_fn!!}</textarea>
                            <script>
                                CKEDITOR.replace( 'content_en_fn',{height: '150px',} );
                            </script>
                        </td>
                    </tr>
                </table> 
            </div>
            @endif
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <a class="btn btn-default" href="{{ URL::route('article.index') }}">Quay lại</a>
                <a class="btn btn-success" href="{{ route('article.createandcopy', [$article->id,'copy']) }}">Sao Chép &Tạo mới</a>
                <a class="btn btn-primary" href="{{ URL::route('article.edit', [$article->id]) }}">Cập nhật</a>
                <a href="#" data-url="{{ route('article.destroy', [$article->id]) }}" data-token="{{ csrf_token() }}" class="btn btn-danger delete">Xóa</a>
                @if($article->is_approve ==0)
                <a class="btn btn-warning btn-sm approve"  href="#" data-url="{{ route('article.approve', $article->id) }}" data-token="{{ csrf_token() }}" ><i class="fa fa-eject"></i></a>
                @endif
            </div>
        </div>
    </div>
</div>
</section>
<script type="text/javascript">
    $(function () {
    $('.delete').click(function(){
        var url = $(this).data('url');
        var data = {
        _method : 'DELETE',
        _token  : $(this).data('token')
      };
      Lobibox.confirm({
            title: "Xác nhận",
            msg: "Bạn chắc chắn muốn xóa không?",
            buttons: {
                yes: {
                    'class': 'btn btn-success',
                    text: "Đồng ý"
                },
                no: {
                    'class': 'btn btn-warning',
                    text: "Hủy bỏ"
                },
            },
            callback: function ($this, type, ev) {
            if (type === 'yes') {
                $.ajax({
                type : 'POST',
                url  : url,
                data : data,
                success  : function (data) {
                    if(data == 'success') {
                        Lobibox.alert('success', 
                        {   
                            title: "Thông báo",
                            msg: "Xóa thành công",
                            callback: function ($this, type, ev) {
                                window.location.href = "{{ route('article.show',$article->id) }}";
                            }
                         });
                    } else {
                        Lobibox.alert('error', 
                        {   
                            title: "Thông báo",
                            msg: "Xóa lỗi hoặc bạn không có quyền!",
                            callback: function ($this, type, ev) {
                               window.location.href = "{{ route('article.show',$article->id) }}";
                            }
                         });
                    }
                }
            });
            }
          }
      });
    }); 
  });
</script>

<script type="text/javascript">
    $(function () {
    $('.approve').click(function(){
        var url = $(this).data('url');
        var data = {
        _method : 'PATCH',
        _token  : $(this).data('token')
      };
      Lobibox.confirm({
            title: "Xác nhận",
            msg: "Phê duyệt bài viết ?",
            buttons: {
                yes: {
                    'class': 'btn btn-success',
                    text: "Đồng ý"
                },
                no: {
                    'class': 'btn btn-warning',
                    text: "Hủy bỏ"
                },
            },
            callback: function ($this, type, ev) {
            if (type === 'yes') {
                $.ajax({
                type : 'POST',
                url  : url,
                data : data,
                success  : function (data) {
                    if(data == 'success') {
                        Lobibox.alert('success', 
                        {   
                            title: "Thông báo",
                            msg: "Phê duyệt thành công",
                            callback: function ($this, type, ev) {
                                window.location.href = "{{ route('article.show',$article->id) }}";
                            }
                         });
                    } else {
                        Lobibox.alert('error', 
                        {   
                            title: "Thông báo",
                            msg: "Phê duyệt bị lỗi!",
                            callback: function ($this, type, ev) {
                                window.location.href = "{{ route('article.show',$article->id) }}";
                            }
                         });
                    }
                }
            });
            }
          }
      });
    }); 
  });
</script>
@endsection