@extends('layouts.app')

@section('content')
<section class="content-header">
    <h3>Thông tin chi tiết</h3>
</section>
<div class="x_panel">
    <div class="x_content">
    	<div class="row ">
            @if($banner->is_approve ==0 && Auth::user()->superadmin==1)
			<div class="col-md-6 col-sm-6 col-xs-12">
                <div style="font-size: 20px; font-weight: 600; text-align: center; margin-bottom: 20px;">Dữ liệu mới</div>
            @else
            <div class="col-md-12 col-sm-12 col-xs-12">
            @endif
                
				<table class="table table-hover">
					<tr>
                        <th class="border_show_th">Tên </th>
                        <td class="border_show_td">{{$banner->name}}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Tên(en) </th>
                        <td class="border_show_td">{{$banner->name_en}}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Header</th>
                        <td class="border_show_td" style="border-top: none !important;">{!!$banner->header!!}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Header(en)</th>
                        <td class="border_show_td">{!!$banner->header_en!!}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Địa chỉ liên kết </th>
                        <td class="border_show_td">{{$banner->url}}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Trạng Thái</th>
                        <td class="border_show_td">@if ($banner->is_active)
                        <img src="{{MEDIADOMAIN}}/images/active.png" alt="active"><div hidden="true">active</div>
                        @else
                        <img src="{{MEDIADOMAIN}}/images/deactive.png" alt="deactive"><div hidden="true">deactive</div>
                        @endif</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Loại</th>
                        <td class="border_show_td">{{$types[$banner->type]}}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Hình ảnh</th>
                        <td class="border_show_td">
                        @if ($banner->image)
                        <img class="img-responsive image-show" src="{!! MEDIADOMAIN.$banner->image !!}">
                        @endif
                        </td>
                    </tr>
				</table>	
			</div>
            @if($banner->is_approve ==0 && Auth::user()->superadmin==1)
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div style="font-size: 20px; font-weight: 600; text-align: center; margin-bottom: 20px;">Dữ liệu cũ</div>
                <table class="table table-hover">
                    <tr>
                        <th class="border_show_th">Tên </th>
                        <td class="border_show_td">{{$banner->name_fn}}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Tên(en) </th>
                        <td class="border_show_td">{{$banner->name_en_fn}}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Header </th>
                        <td class="border_show_td" style="border-top: none !important;">{!!$banner->header_fn!!}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Header(en)</th>
                        <td class="border_show_td">{!!$banner->header_en_fn!!}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Địa chỉ liên kết</th>
                        <td class="border_show_td">{{$banner->url_fn}}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Trạng Thái</th>
                        <td class="border_show_td">@if ($banner->is_active_fn)
                        <img src="{{MEDIADOMAIN}}/images/active.png" alt="active"><div hidden="true">active</div>
                        @else
                        <img src="{{MEDIADOMAIN}}/images/deactive.png" alt="deactive"><div hidden="true">deactive</div>
                        @endif</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Loại</th>
                        <td class="border_show_td">{{$types[$banner->type_fn]}}</td>
                    </tr>
                    <tr>
                        <th class="border_show_th">Hình ảnh</th>
                        <td class="border_show_td">
                        @if ($banner->image_fn)
                        <img class="img-responsive image-show" src="{!! MEDIADOMAIN.$banner->image_fn !!}">
                        @endif
                        </td>
                    </tr>
                </table>    
            </div>
            @endif
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <a class="btn btn-default" href="{{ URL::route('banner.index') }}">Quay lại</a>
                <a class="btn btn-primary" href="{{ URL::route('banner.edit', [$banner->id]) }}">Cập nhật</a>
                <a href="#" data-url="{{ route('banner.destroy', [$banner->id]) }}" data-token="{{ csrf_token() }}" class="btn btn-danger delete">Xóa</a>
                @if($banner->is_approve ==0 && Auth::user()->superadmin==1)
                <a class="btn btn-warning btn-sm approve"  href="#" data-url="{{ route('banner.approve', $banner->id) }}" data-token="{{ csrf_token() }}" ><i class="fa fa-eject"></i></a>
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
                                window.location.href = "{{ route('banner.index',$banner->id) }}";
                            }
                         });
                    } else {
                        Lobibox.alert('error', 
                        {   
                            title: "Thông báo",
                            msg: "Xóa lỗi hoặc bạn không có quyền!",
                            callback: function ($this, type, ev) {
                               window.location.href = "{{ route('banner.index',$banner->id) }}";
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
                                window.location.href = "{{ route('banner.show',$banner->id) }}";
                            }
                         });
                    } else {
                        Lobibox.alert('error', 
                        {   
                            title: "Thông báo",
                            msg: "Phê duyệt bị lỗi!",
                            callback: function ($this, type, ev) {
                                window.location.href = "{{ route('banner.show',$banner->id) }}";
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