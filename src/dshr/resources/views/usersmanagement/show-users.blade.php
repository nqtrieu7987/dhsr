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
                        <div class="row">
                            <div class="col-12">
                                
                            
                        <div class="btn-filter-users">
                            <a href="{{route('users')}}?type=all" class="btn btn-primary mr-3 mt-3">All</a>
                            {{-- <a href="{{route('users')}}?type=attire" class="btn btn-warning mr-3 mt-3">Approval Attire</a> --}}
                            <a href="{{route('users')}}?type=uniform" class="btn btn-info mr-3 mt-3">Uniform</a>
                            <a href="{{route('users')}}?type=failed" class="btn btn-success mr-3 mt-3">Failed</a>
                            <a href="{{route('users')}}?type=blacklist" class="btn btn-danger mr-3 mt-3">Blacklist</a>
                            <a href="{{route('users')}}?type=ic" class="btn btn-primary mr-3 mt-3">IC</a>
                            {{-- <a href="/users/deleted" class="btn btn-dark">Deleted</a> --}}

                            {{-- <div class="btn-group pull-right btn-group-xs">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="sr-only">
                                        {!! trans('usersmanagement.users-menu-alt') !!}
                                    </span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="/users/create">
                                        <img class="fa-icon" src="/images/newuser_black_icon.png">
                                        {!! trans('usersmanagement.buttons.create-new') !!}
                                    </a>
                                    <a class="dropdown-item" href="/users/deleted">
                                        <img class="fa-icon" src="/images/deleteduser_icon.png.png">
                                        {!! trans('usersmanagement.show-deleted-users') !!}
                                    </a>
                                </div>
                            </div> --}}
                        </div>
                        </div>
                        </div>
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
                        {{-- @if(config('usersmanagement.enableSearchUsers'))
                            @include('partials.search-users-form')
                        @endif --}}

                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table">
                                <caption id="user_count">
                                    {{ trans_choice('usersmanagement.users-table.caption', 1, ['userscount' => $users->count()]) }}
                                </caption>
                                <thead class="thead">
                                    <tr>
                                        <th class="hidden-xs">ID</th>
                                        @if(request()->get('type') =='all')
                                        <th class="hidden-xs">IC No</th>
                                        @endif
                                        <th class="no-search no-sort">Avatar</th>
                                        <th>Username</th>
                                        <th>Contact No</th>
                                        @if(request()->get('type') =='ic')
                                            <th class="no-search no-sort">NRIC Front</th>
                                            <th class="no-search no-sort">NRIC Back</th>
                                        @elseif(request()->get('type') =='attire')
                                            <th class="no-search no-sort">User Pants</th>
                                            <th class="no-search no-sort">Pants Approved</th>
                                            <th class="no-search no-sort">Pants Rejected</th>
                                            <th class="no-search no-sort">User Shoes</th>
                                            <th class="no-search no-sort">Shoes Approved</th>
                                            <th class="no-search no-sort">Shoes Rejected</th>
                                        @else
                                            <th>Reset pass</th>
                                            <th class="hidden-xs no-search no-sort">Gender</th>
                                            <th class="hidden-xs">Job Done</th>
                                            <th class="no-search no-sort">Student</th>
                                            <th class="hidden-sm hidden-xs hidden-md">Feedback</th>
                                            <th class="hidden-sm hidden-xs hidden-md">Emg Name</th>
                                            <th class="no-search no-sort">Work in TCC before</th>
                                        @endif
                                        <th class="no-search no-sort">Appovals</th>
                                    </tr>
                                </thead>
                                <tbody id="users_table">
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{$user->id}}</td>
                                            @if(request()->get('type') =='all')
                                            <td class="hidden-xs">{{$user->userNRIC}}</td>
                                            @endif
                                            <td><div class="header-user"><img onclick="showModalImage(this)" src="{{$user->workPassPhoto}}" alt="{{$user->userName}}"></div></td>
                                            <td><a href="{{ URL::to('users/edit/'.$user->id) }}" title="{{$user->userName}}">{{$user->userName ? $user->userName : $user->email}}</a></td>
                                            <td>{{$user->contactNo}}</td>
                                            @if(request()->get('type') =='ic')
                                                <td><img style="width: 100px" onclick="showModalImage(this)" src="{{$user->NRICFront}}" alt="NRICFront"></td>
                                                <td><img style="width: 100px" onclick="showModalImage(this)" src="{{$user->NRICBack}}" alt="NRICBack"></td>
                                            @elseif(request()->get('type') =='attire')
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
                                            @else
                                                <td><a href="{{route('user.resetpass', $user->id)}}">Resetpass</a></td>
                                                <td class="align-center hidden-sm hidden-xs">{!! \App\Helper\VtHelper::checkGenderIcon($user->userGender)!!}</td>
                                                <td class="align-center hidden-sm hidden-xs">{!! $user->jobsDone !!}</td>
                                                <td class="align-center">{!! \App\Helper\VtHelper::checkStatusIcon($user->studentType)!!}</td>
                                                <td class="hidden-xs hidden-sm hidden-xs">{!! $user->feedback !!}</td>
                                                <td class="hidden-xs hidden-sm hidden-xs">{!! $user->emergencyContactName !!}</td>
                                                <td class="align-center">{!! \App\Helper\VtHelper::checkStatusIcon($user->TCC)!!}</td>
                                            @endif
                                            <td class="align-center" id="img_status_{{$user->id}}">
                                                {!! \App\Helper\VtHelper::checkStatusIcon($user->activated)!!}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tbody id="search_results"></tbody>
                                @if(config('usersmanagement.enableSearchUsers'))
                                    <tbody id="search_results"></tbody>
                                @endif

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
