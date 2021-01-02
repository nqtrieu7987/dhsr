@extends('layouts.master')

@section('template_title')
    {!! trans('usersmanagement.showing-all-users') !!}
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/dataTables.bootstrap.min.css')}}">
    <style type="text/css">
        .dataTables_filter{display: none;}
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mt-3">
                            <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 col-xs-12">
                                <form id="search_form" style="width: 100%;" action="" method="GET">
                                    <div class="input-group mb-3">
                                            <input type="text" class="form-control mr-2" required="" 
                                                       value="{{request()->get('keyword')}}" id="keyword"
                                                       name="keyword" placeholder="Search users">
                                            <button class="btn bg-main" type="submit"><i aria-hidden="true" class="fa fa-search"></i>&nbsp;Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pd-xs-0">
                        <div class="table-responsive users-table overflow-auto">
                            <table class="table table-striped table-condensed data-table">
                                <caption id="user_count">
                                    {{ trans_choice('usersmanagement.users-table.caption', 1, ['userscount' => $users->count()]) }}
                                </caption>
                                <thead class="thead">
                                    <tr>
                                        <th class="no-search no-sort">Avatar</th>
                                        <th>Username</th>
                                        <th>Contact No</th>
                                        <th class="no-search no-sort">User Pants</th>
                                        <th class="no-search no-sort">Pants Approved</th>
                                        <th class="no-search no-sort">Pants Rejected</th>
                                        <th class="no-search no-sort">User Shoes</th>
                                        <th class="no-search no-sort">Shoes Approved</th>
                                        <th class="no-search no-sort">Shoes Rejected</th>
                                    </tr>
                                </thead>
                                <tbody id="users_table">
                                    @foreach($users as $user)
                                        <tr>
                                            <td><div class="header-user"><img onclick="showModalImage(this)" src="{{$user->workPassPhoto}}" alt="{{$user->userName}}"></div></td>
                                            <td><a href="{{ URL::to('users/edit/'.$user->id) }}" title="{{$user->userName}}">{{$user->userName ? $user->userName : $user->email}}</a></td>
                                            <td>{{$user->contactNo}}</td>
                                            <td class="align-center">
                                                <img @if($user->userPantsApproved == 1) style="display: block;" @endif class="status-icon check" src="/images/Active.png" id="pants_check_{{$user->id}}">
                                                <img style="width: 100px" onclick="showModalImage(this)" src="{{$user->userPants}}" alt="userPants" id="pants_img_{{$user->id}}">
                                            </td>
                                            <td class="align-center">
                                                <span class="text-success changeStatus" data-id="{{$user->id}}" data-type="userPantsApproved" id="pants_{{$user->id}}" data-value="1">Approved</span>
                                            </td>
                                            <td class="align-center">
                                                <span class="text-danger changeStatus" data-id="{{$user->id}}" data-type="userPantsApproved" id="pants_rj_{{$user->id}}" data-value="0">Rejected</span>
                                            </td>
                                            <td class="align-center">
                                                <img @if($user->userShoesApproved == 1) style="display: block;" @endif class="status-icon check" src="/images/Active.png" id="shoes_check_{{$user->id}}">
                                                <img style="width: 100px" onclick="showModalImage(this)" src="{{$user->userShoes}}" alt="userShoes" id="shoes_img_{{$user->id}}">
                                            </td>
                                            <td class="align-center">
                                                <span class="text-success changeStatus" data-id="{{$user->id}}" data-type="userShoesApproved" id="shoes_{{$user->id}}" data-value="1">Approved</span>
                                            </td>
                                            <td class="align-center">
                                                <span class="text-danger changeStatus" data-id="{{$user->id}}" data-type="userShoesApproved" id="shoes_rj_{{$user->id}}" data-value="0">Rejected</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if(config('usersmanagement.enablePagination'))
                                {{ $users->appends(request()->except('page'))->links() }}
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
    @if (request()->get('keyword') == '')
        @include('scripts.datatables')
    @endif
    @include('scripts.save-modal-script')
    @if(config('usersmanagement.tooltipsEnabled'))
        @include('scripts.tooltips')
    @endif
    @if(config('usersmanagement.enableSearchUsers'))
        @include('scripts.search-users')
    @endif

    @include('scripts.image-modal-script')
    <script type="text/javascript">
        $('.changeStatus').click(function(){
            var idstt = this.id;
            var id = $(this).data("id");
            var type = $(this).data("type");
            var value = $(this).data("value");
            var data = {
                id: id,
                type: type,
                value: value,
                model: 'user',
            };

            if(value == 0){
                var r = confirm("Do you want reject?");
                if (r == true) {
                    $('#waiting').show();
                    $.ajax({
                        type : 'POST',
                        url  : '{{route('change.status')}}',
                        data : data,
                        success  : function (data) {
                            $('#waiting').hide();
                            $('#'+idstt).hide();
                            if(type =='userPantsApproved'){
                                $('#pants_'+id).hide();
                                $('#pants_img_'+id).hide();
                                $('#pants_check_'+id).hide();
                            }else{
                                $('#shoes_'+id).hide();
                                $('#shoes_img_'+id).hide();
                                $('#shoes_check_'+id).hide();
                            }
                            if(data.msg === 0){
                                $('#'+idstt).removeClass('text-success');
                                $('#'+idstt).addClass('text-danger');
                                $('#'+idstt).text('Deactivated');
                                $('#img_'+idstt).html('<i class="fa fa-check text-success" aria-hidden="true"></i>');
                            }else{
                                $('#'+idstt).removeClass('text-danger');
                                $('#'+idstt).addClass('text-success');
                                $('#'+idstt).text('Active');
                                $('#img_'+idstt).html('<i class="fa fa-close text-danger" aria-hidden="true"></i>');
                            }
                        }
                    });
                } else {
                    
                }
            }else{
                $('#waiting').show();
                $.ajax({
                    type : 'POST',
                    url  : '{{route('change.status')}}',
                    data : data,
                    success  : function (data) {
                        $('#waiting').hide();
                        $('#'+idstt).hide();
                        if(type =='userPantsApproved'){
                            $('#pants_'+id).hide();
                            $('#pants_check_'+id).show();
                        }else{
                            $('#shoes_'+id).hide();
                            $('#shoes_check_'+id).show();
                        }
                        if(data.msg === 0){
                            $('#'+idstt).removeClass('text-success');
                            $('#'+idstt).addClass('text-danger');
                            $('#'+idstt).text('Deactivated');
                            $('#img_'+idstt).html('<i class="fa fa-check text-success" aria-hidden="true"></i>');
                        }else{
                            $('#'+idstt).removeClass('text-danger');
                            $('#'+idstt).addClass('text-success');
                            $('#'+idstt).text('Active');
                            $('#img_'+idstt).html('<i class="fa fa-close text-danger" aria-hidden="true"></i>');
                        }
                    }
                });
            }
        });
    </script>
@endsection
