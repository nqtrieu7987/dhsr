@extends('layouts.master')

@section('template_title')
    {!! trans('usersmanagement.editing-user', ['name' => $user->name]) !!}
@endsection

@section('template_linked_css')
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
    </style>
@endsection
@php
$dataActive = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->activated == 1) {$dataActive = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$userGender = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->userGender == 1) {$userGender = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$studentType = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->studentType == 1) {$studentType = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$studentStatus = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->studentStatus == 1) {$studentStatus = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$asWaiter = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->asWaiter == 1) {$asWaiter = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$dyedHair = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->dyedHair == 1) {$dyedHair = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$visibleTattoo = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->visibleTattoo == 1) {$visibleTattoo = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$userPantsApproved = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->userPantsApproved == 1) {$userPantsApproved = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$userShoesApproved = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->userShoesApproved == 1) {$userShoesApproved = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$isFavourite = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->isFavourite == 1) {$isFavourite = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$isWarned = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->isWarned == 1) {$isWarned = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$isDiamond = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->isDiamond == 1) {$isDiamond = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$isW = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->isW == 1) {$isW = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$isMO = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->isMO == 1) {$isMO = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$isMC = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->isMC == 1) {$isMC = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$isRWS = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->isRWS == 1) {$isRWS = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$isKempinski = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->isKempinski == 1) {$isKempinski = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$isHilton = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->isHilton == 1) {$isHilton = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$isGWP = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->isGWP == 1) {$isGWP = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

$TCC = ['checked' => '','value' => 0,'true'  => '','false' => 'checked'];
if($user->TCC == 1) {$TCC = ['checked' => 'checked','value' => 1,'true'  => 'checked','false' => ''];}

@endphp

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card border-0">
                    <div class="card-body p-0">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-sm-4 col-md-3 profile-sidebar text-white rounded-left-sm-up">
                                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        {{-- <a class="nav-link active" data-toggle="pill" href=".edit-profile-tab" role="tab" aria-controls="edit-profile-tab" aria-selected="true">
                                            Avatar
                                        </a> --}}
                                        <a class="nav-link" data-toggle="pill" href=".workPassPhoto-tab" role="tab" aria-controls="edit-profile-tab" aria-selected="true">
                                            Work Pass Photo
                                        </a>
                                        <a class="nav-link" data-toggle="pill" href=".edit-settings-tab" role="tab" aria-controls="edit-settings-tab" aria-selected="false">
                                            NRICF Image
                                        </a>
                                        <a class="nav-link {{$user->studentStatus == 0 ? 'hidden' : ''}}" id="tabStudentImage" data-toggle="pill" href=".student-image-tab" role="tab" aria-controls="edit-settings-tab" aria-selected="false">
                                            Student Image
                                        </a>
                                        <a class="nav-link" data-toggle="pill" href=".edit-account-tab" role="tab" aria-controls="edit-settings-tab" aria-selected="false">
                                            Pants, Shoes Image
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-8 col-md-9 mb-3">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        {{-- <div class="tab-pane fade show active edit-profile-tab" role="tabpanel" aria-labelledby="edit-profile-tab">
                                            <div class="row mb-1">
                                                <div class="col-sm-12">
                                                    <div id="avatar_container">
                                                        <div class="card-body">
                                                            <div class="dz-preview"></div>
                                                            {!! Form::open(array('route' => 'avatar.upload', 'method' => 'POST', 'name' => 'avatarDropzone','id' => 'avatarDropzone', 'class' => 'form single-dropzone dropzone single', 'files' => true)) !!}
                                                                <img id="user_selected_avatar" onclick="showModalImage(this)" class="user-avatar-200 avatarImage" src="@if ($user->avatar != NULL) {{ $user->avatar }} @endif" alt="avatar">
                                                                {!! Form::hidden("type", "avatar") !!}
                                                                {!! Form::hidden("id", $user->id) !!}
                                                            {!! Form::close() !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-5">
                                                <div class="col-10 offset-1 col-sm-10 offset-sm-1 mb-1">
                                                    
                                                </div>
                                            </div>
                                            <div class="row mt-5"></div>
                                        </div> --}}

                                        <div class="tab-pane fade show active workPassPhoto-tab" role="tabpanel" aria-labelledby="workPassPhoto-tab">
                                            <div class="row mb-1">
                                                <div class="col-sm-12">
                                                    <div id="avatar_container">
                                                        <div class="card-body">
                                                            <div class="dz-preview"></div>
                                                            {!! Form::open(array('route' => 'upload.image', 'method' => 'POST', 'name' => 'workPassPhotoDZ','id' => 'workPassPhotoDZ', 'class' => 'form single-dropzone dropzone single', 'files' => true)) !!}
                                                                <img id="user_selected_avatar" onclick="showModalImage(this)" class="user-avatar-200 workPassPhoto" src="@if ($user->workPassPhoto != NULL) {{ $user->workPassPhoto }} @endif" alt="workPassPhoto">
                                                                {!! Form::hidden("type", "workPassPhoto") !!}
                                                                {!! Form::hidden("id", $user->id) !!}
                                                            {!! Form::close() !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-5">
                                                <div class="col-10 offset-1 col-sm-10 offset-sm-1 mb-1">
                                                    {{-- <div class="row" data-toggle="buttons">
                                                        <div class="col-3 col-xs-6"></div>
                                                        <div class="col-6 col-xs-6 left-btn-container">
                                                            <label class="btn bg-main @if($user->workPassPhoto != null) active @endif btn-block btn-sm" data-toggle="collapse" data-target=".collapseOne.show, .collapseTwo:not(.show)">
                                                                <input type="radio" name="avatar_status" id="option2" autocomplete="off" value="1" @if($user->workPassPhoto != null) checked @endif> Use workPassPhoto
                                                            </label>
                                                        </div>
                                                        <div class="col-3 col-xs-6"></div>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade edit-settings-tab" role="tabpanel" aria-labelledby="edit-settings-tab">
                                            <div class="row mb-1">
                                                <div class="col-sm-12">
                                                    <div id="avatar_container">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="dz-preview"></div>
                                                                    {!! Form::open(array('route' => 'upload.image', 'method' => 'POST', 'name' => ' ','id' => 'NRICFrontDZ', 'class' => 'form single-dropzone dropzone single', 'files' => true)) !!}
                                                                        <img id="user_selected_avatar" onclick="showModalImage(this)" class="user-avatar-200 NRICFront" src="@if ($user->NRICFront != NULL) {{ $user->NRICFront }} @endif" alt="NRIC Front">
                                                                        {!! Form::hidden("type", "NRICFront") !!}
                                                                        {!! Form::hidden("id", $user->id) !!}
                                                                    {!! Form::close() !!}
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="dz-preview"></div>
                                                                    {!! Form::open(array('route' => 'upload.image', 'method' => 'POST', 'name' => 'user-NRICBack-dz','id' => 'NRICBackDZ', 'class' => 'form single-dropzone dropzone single', 'files' => true)) !!}
                                                                        <img id="user_selected_avatar" onclick="showModalImage(this)" class="user-avatar-200 NRICBack" src="@if ($user->NRICBack != NULL) {{ $user->NRICBack }} @endif" alt="NRIC Back">
                                                                        {!! Form::hidden("type", "NRICBack") !!}
                                                                        {!! Form::hidden("id", $user->id) !!}
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-5">
                                                <div class="col-10 offset-1 col-sm-10 offset-sm-1 mb-1">
                                                    {{-- <div class="row" data-toggle="buttons">
                                                        <div class="col-5 col-xs-6 right-btn-container">
                                                            <label class="btn bg-main @if($user->NRICFront != null) active @endif btn-block btn-sm" data-toggle="collapse" data-target=".collapseOne:not(.show), .collapseTwo.show">
                                                                <input type="radio" name="avatar_status" id="option1" autocomplete="off" value="0" @if($user->NRICFront != null) checked @endif> User NRICFront
                                                            </label>
                                                        </div>
                                                        <div class="col-2"></div>
                                                        <div class="col-5 col-xs-6 left-btn-container">
                                                            <label class="btn bg-main @if($user->NRICBack != null) active @endif btn-block btn-sm" data-toggle="collapse" data-target=".collapseOne.show, .collapseTwo:not(.show)">
                                                                <input type="radio" name="avatar_status" id="option2" autocomplete="off" value="1" @if($user->NRICBack != null) checked @endif> User NRICBack
                                                            </label>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>

                                        @if($user->studentStatus == 1)
                                        <div class="tab-pane fade student-image-tab" role="tabpanel" aria-labelledby="student-image-tab">
                                            <div class="row mb-1">
                                                <div class="col-sm-12">
                                                    <div id="avatar_container">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="dz-preview"></div>
                                                                    {!! Form::open(array('route' => 'upload.image', 'method' => 'POST', 'name' => ' ','id' => 'studentCardFrontDZ', 'class' => 'form single-dropzone dropzone single', 'files' => true)) !!}
                                                                        <img id="user_selected_avatar" onclick="showModalImage(this)" class="user-avatar-200 studentCardFront" src="@if ($user->studentCardFront != NULL) {{ $user->studentCardFront }} @endif" alt="studentCardFront">
                                                                        {!! Form::hidden("type", "studentCardFront") !!}
                                                                        {!! Form::hidden("id", $user->id) !!}
                                                                    {!! Form::close() !!}
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="dz-preview"></div>
                                                                    {!! Form::open(array('route' => 'upload.image', 'method' => 'POST', 'name' => 'user-studentCardBack-dz','id' => 'studentCardBackDZ', 'class' => 'form single-dropzone dropzone single', 'files' => true)) !!}
                                                                        <img id="user_selected_avatar" onclick="showModalImage(this)" class="user-avatar-200 studentCardBack" src="@if ($user->studentCardBack != NULL) {{ $user->studentCardBack }} @endif" alt="studentCardBack">
                                                                        {!! Form::hidden("type", "studentCardBack") !!}
                                                                        {!! Form::hidden("id", $user->id) !!}
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-5">
                                                <div class="col-10 offset-1 col-sm-10 offset-sm-1 mb-1">
                                                    {{-- <div class="row" data-toggle="buttons">
                                                        <div class="col-5 col-xs-6 right-btn-container">
                                                            <label class="btn bg-main @if($user->studentCardFront != null) active @endif btn-block btn-sm" data-toggle="collapse" data-target=".collapseOne:not(.show), .collapseTwo.show">
                                                                <input type="radio" name="avatar_status" id="option1" autocomplete="off" value="0" @if($user->studentCardFront != null) checked @endif> User studentCardFront
                                                            </label>
                                                        </div>
                                                        <div class="col-2"></div>
                                                        <div class="col-5 col-xs-6 left-btn-container">
                                                            <label class="btn bg-main @if($user->studentCardBack != null) active @endif btn-block btn-sm" data-toggle="collapse" data-target=".collapseOne.show, .collapseTwo:not(.show)">
                                                                <input type="radio" name="avatar_status" id="option2" autocomplete="off" value="1" @if($user->studentCardBack != null) checked @endif> User studentCardBack
                                                            </label>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="tab-pane fade edit-account-tab" role="tabpanel" aria-labelledby="edit-account-tab">
                                            <div class="row mb-1">
                                                <div class="col-sm-12">
                                                    <div id="avatar_container">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-6 col-xs-6">
                                                                    <div class="dz-preview"></div>
                                                                    {!! Form::open(array('route' => 'upload.image', 'method' => 'POST', 'name' => 'userPantsDZ','id' => 'userPantsDZ', 'class' => 'form single-dropzone dropzone single', 'files' => true)) !!}
                                                                        <img id="user_selected_avatar" onclick="showModalImage(this)" class="user-avatar-200 userPants" src="@if ($user->userPants != NULL) {{ $user->userPants }} @endif" alt="userPants">
                                                                        {!! Form::hidden("type", "userPants") !!}
                                                                        {!! Form::hidden("id", $user->id) !!}
                                                                    {!! Form::close() !!}
                                                                </div>
                                                                <div class="col-6 col-xs-6">
                                                                    <div class="dz-preview"></div>
                                                                    {!! Form::open(array('route' => 'upload.image', 'method' => 'POST', 'name' => 'user-userShoes-dz','id' => 'userShoesDZ', 'class' => 'form single-dropzone dropzone single', 'files' => true)) !!}
                                                                        <img id="user_selected_avatar" onclick="showModalImage(this)" class="user-avatar-200 userShoes" src="@if ($user->userShoes != NULL) {{ $user->userShoes }} @endif" alt="userShoes">
                                                                        {!! Form::hidden("type", "userShoes") !!}
                                                                        {!! Form::hidden("id", $user->id) !!}
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </div>
                                                            
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- {!! Form::model($user, ['method' => 'POST', 'route' => ['user.approved-pant-shose', $user->id], 'id' => 'user_profile_form', 'class' => 'form-horizontal', 'role' => 'form', 'enctype' => 'multipart/form-data']) !!}
                                                {{ csrf_field() }}
                                                {!! Form::hidden("id", $user->id) !!} --}}
                                                <div class="row mt-5">
                                                    <div class="col-10 offset-1 col-sm-10 offset-sm-1 mb-1">
                                                        {{-- <div class="row" data-toggle="buttons">
                                                            <div class="col-5 col-xs-6 right-btn-container">
                                                                <label class="btn bg-main @if($user->userPants != null) active @endif btn-block btn-sm" data-toggle="collapse" data-target=".collapseOne:not(.show), .collapseTwo.show">
                                                                    <input type="radio" name="avatar_status" id="option1" autocomplete="off" value="0" @if($user->userPantsApproved != null) checked @endif> User Pants
                                                                </label>
                                                            </div>
                                                            <div class="col-2"></div>
                                                            <div class="col-5 col-xs-6 left-btn-container">
                                                                <label class="btn bg-main @if($user->userShoes != null) active @endif btn-block btn-sm" data-toggle="collapse" data-target=".collapseOne.show, .collapseTwo:not(.show)">
                                                                    <input type="radio" name="avatar_status" id="option2" autocomplete="off" value="1" @if($user->userShoes != null) checked @endif> User Shoes
                                                                </label>
                                                            </div>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                                <form>
                                                <div class="form-group has-feedback row mt-3">
                                                    <div class="col-md-1"></div>
                                                    {!! Form::label('userPantsApproved', 'Pant Approved', array('class' => 'col-md-3 control-label')); !!}
                                                    <div class="col-md-2">
                                                        <label onclick="approveByType(1)" id="switch_userPantsApproved" class="switch_userPantsApproved {{ $userPantsApproved['checked'] }}" for="is_active">
                                                            <span class="active"><i class="fa fa-toggle-on fa-2x"></i></span>
                                                            <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i></span>
                                                            <input type="radio" name="userPantsApproved" value="1" {{ $userPantsApproved['true'] }}>
                                                            <input type="radio" name="userPantsApproved" value="0" {{ $userPantsApproved['false'] }}>
                                                        </label>
                                                    </div>

                                                    <div class="col-md-1"></div>
                                                    {!! Form::label('userShoesApproved', 'Shoes Approved', array('class' => 'col-md-3 control-label')); !!}
                                                    <div class="col-md-2">
                                                        <label onclick="approveByType(2)" id="switch_userShoesApproved" class="switch_userShoesApproved {{ $userShoesApproved['checked'] }}" for="is_active">
                                                            <span class="active"><i class="fa fa-toggle-on fa-2x"></i></span>
                                                            <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i></span>
                                                            <input type="radio" name="userShoesApproved" value="1" {{ $userShoesApproved['true'] }}>
                                                            <input type="radio" name="userShoesApproved" value="0" {{ $userShoesApproved['false'] }}>
                                                        </label>
                                                    </div>
                                                </div>
                                                </form>

                                                {{-- <div class="form-group margin-bottom-2 row">
                                                    <div class="col-4"></div>
                                                    <div class="col-4">
                                                        {!! Form::button(trans('forms.save-changes'), array('class' => 'btn bg-main btn-block margin-bottom-1 mt-3 mb-2','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'data-title' => trans('modals.edit_user__modal_text_confirm_title'), 'data-message' => trans('modals.edit_user__modal_text_confirm_message'))) !!}

                                                    </div>
                                                    <div class="col-4"></div>
                                                </div>
                                            {!! Form::close() !!} --}}
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                           <b> {!! trans('usersmanagement.editing-user', ['name' => $user->name]) !!}</b>
                            <div class="pull-right">
                                <a href="/users" class="btn btn-blue-light btn-xs pull-right">
                                  <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                                  <span class="hidden-xs">Back to list </span><br/>
                                </a>
                            </div>
                        </div>
                    </div> --}}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                <div class="card-header bg-main">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                       <b> Status Info</b>
                                    </div>
                                </div>
                                <div class="card-body">                                        
                                    <div class="row">
                                        {!! \App\Helper\VtHelper::checkStatusUser('isFavourite', 1, $user->isFavourite)!!}
                                        {!! \App\Helper\VtHelper::checkStatusUser('isWarned', 2, $user->isWarned)!!}
                                        {!! \App\Helper\VtHelper::checkStatusUser('isDiamond', 3, $user->isDiamond)!!}
                                        {!! \App\Helper\VtHelper::checkStatusUser('isW', 4, $user->isW)!!}
                                        {!! \App\Helper\VtHelper::checkStatusUser('isMO', 5, $user->isMO)!!}
                                        {!! \App\Helper\VtHelper::checkStatusUser('isMC', 6, $user->isMC)!!}
                                        {!! \App\Helper\VtHelper::checkStatusUser('isMC', 7, $user->isMC)!!}
                                        {!! \App\Helper\VtHelper::checkStatusUser('isKempinski', 8, $user->isKempinski)!!}
                                        {!! \App\Helper\VtHelper::checkStatusUser('isHilton', 9, $user->isHilton)!!}
                                        {!! \App\Helper\VtHelper::checkStatusUser('TCC', 10, $user->TCC)!!}
                                        {!! \App\Helper\VtHelper::checkStatusUser('isGWP', 11, $user->isGWP)!!}
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>



                        {!! Form::open(array('route' => ['user.edit', $user->id], 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}
                            <div class="row mt-5">
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <div class="card">
                                    <div class="card-header bg-main">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                           <b> User Info</b>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group has-feedback row {{ $errors->has('userName') ? ' has-error ' : '' }}">
                                            {!! Form::label('userName', trans('forms.create_user_label_username'), array('class' => 'col-md-3 control-label')); !!}
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    {!! Form::text('userName', $user->userName, array('id' => 'userName', 'class' => 'form-control', 'placeholder' => trans('forms.create_user_ph_username'))) !!}
                                                </div>
                                                @if($errors->has('userName'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('userName') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback row {{ $errors->has('userNRIC') ? ' has-error ' : '' }}">
                                            {!! Form::label('userNRIC', 'ID card', array('class' => 'col-md-3 control-label')); !!}
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    {!! Form::text('userNRIC', $user->userNRIC, array('id' => 'userNRIC', 'class' => 'form-control', 'placeholder' => 'ID card')) !!}
                                                </div>
                                                @if($errors->has('userNRIC'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('userNRIC') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback row {{ $errors->has('email') ? ' has-error ' : '' }}">
                                            {!! Form::label('email', trans('forms.create_user_label_email'), array('class' => 'col-md-3 control-label')); !!}
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    {!! Form::text('email', $user->email, array('id' => 'email', 'readonly' => true, 'class' => 'form-control', 'placeholder' => trans('forms.create_user_ph_email'))) !!}
                                                </div>
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="form-group has-feedback row">
                                            {!! Form::label('userBirthday', 'Birthday', array('class' => 'col-md-3 control-label')); !!}
                                            <div class="col-md-9">
                                                <div class="input-group date">
                                                    {!! Form::text('userBirthday', date('m/d/Y', strtotime($user->userBirthday)), array('id' => 'userBirthday', 'class' => 'form-control col-md-12 col-xs-12 pull-right datepicker', 'placeholder' => 'mm/dd/yyyy')) !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback row {{ $errors->has('contactNo') ? ' has-error ' : '' }}">
                                            {!! Form::label('contactNo', 'Contact No', array('class' => 'col-md-3 control-label')); !!}
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    {!! Form::text('contactNo', $user->contactNo, array('id' => 'contactNo', 'class' => 'form-control', 'placeholder' => 'Contact No')) !!}
                                                </div>
                                                @if($errors->has('contactNo'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('contactNo') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback row {{ $errors->has('address1') ? ' has-error ' : '' }}">
                                            {!! Form::label('address1', 'Address1', array('class' => 'col-md-3 control-label')); !!}
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    {!! Form::text('address1', $user->address1, array('id' => 'address1', 'class' => 'form-control', 'placeholder' => 'Address1')) !!}
                                                </div>
                                                @if($errors->has('address1'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('address1') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback row {{ $errors->has('address2') ? ' has-error ' : '' }}">
                                            {!! Form::label('address2', 'Address 2', array('class' => 'col-md-3 control-label')); !!}
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    {!! Form::text('address2', $user->address2, array('id' => 'address2', 'class' => 'form-control', 'placeholder' => 'Address 2')) !!}
                                                </div>
                                                @if ($errors->has('address2'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('address2') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback row {{ $errors->has('userGender') ? ' has-error ' : '' }}">
                                            {!! Form::label('userGender', 'Gender', array('class' => 'col-4 col-md-4 col-xs-6 control-label')); !!}
                                            <div class="col-8 col-md-8 col-xs-6">
                                                {{-- <label class="switch_userGender {{ $userGender['checked'] }}" for="userGender">
                                                    <span class="active"><i class="fa fa-toggle-on fa-2x"></i></span>
                                                    <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i></span>
                                                    <input type="radio" name="userGender" value="1" {{ $userGender['true'] }}>
                                                    <input type="radio" name="userGender" value="0" {{ $userGender['false'] }}>
                                                </label>
 --}}
                                                <div class="custom-radio float-left mr-5">
                                                    <input type="radio" class="custom-control-input" id="defaultGroupExample1" name="userGender" {{$user->userGender == 1 ? 'checked' : '' }} value="1">
                                                    <label class="custom-control-label" for="defaultGroupExample1">Female</label>
                                                </div>

                                                <!-- Group of default radios - option 2 -->
                                                <div class="custom-radio float-left ml-3">
                                                    <input type="radio" class="custom-control-input" id="defaultGroupExample2" name="userGender" {{$user->userGender == 0 ? 'checked' : '' }} value="0">
                                                    <label class="custom-control-label" for="defaultGroupExample2">Male</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback row {{ $errors->has('activated') ? ' has-error ' : '' }}">
                                            {!! Form::label('activated', 'Status', array('class' => 'col-6 col-md-4 col-xs-6 control-label')); !!}
                                            <div class="col-6 col-md-4 col-xs-6">
                                                <label class="switch {{ $dataActive['checked'] }}" for="is_active">
                                                    <span class="active"><i class="fa fa-toggle-on fa-2x"></i></span>
                                                    <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i></span>
                                                    <input type="radio" name="activated" value="1" {{ $dataActive['true'] }}>
                                                    <input type="radio" name="activated" value="0" {{ $dataActive['false'] }}>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback row {{ $errors->has('dyedHair') ? ' has-error ' : '' }}">
                                            {!! Form::label('dyedHair', 'Dyed Hair', array('class' => 'col-6 col-md-4 col-xs-6 control-label')); !!}
                                            <div class="col-6 col-md-4 col-xs-6">
                                                <label class="switch_dyedHair {{ $dyedHair['checked'] }}" for="is_active">
                                                    <span class="active"><i class="fa fa-toggle-on fa-2x"></i></span>
                                                    <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i></span>
                                                    <input type="radio" name="dyedHair" value="1" {{ $dyedHair['true'] }}>
                                                    <input type="radio" name="dyedHair" value="0" {{ $dyedHair['false'] }}>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback row {{ $errors->has('visibleTattoo') ? ' has-error ' : '' }}">
                                            {!! Form::label('visibleTattoo', 'Visible Tattoo', array('class' => 'col-6 col-md-4 col-xs-6 control-label')); !!}
                                            <div class="col-6 col-md-4 col-xs-6">
                                                <label class="switch_visibleTattoo {{ $visibleTattoo['checked'] }}" for="is_active">
                                                    <span class="active"><i class="fa fa-toggle-on fa-2x"></i></span>
                                                    <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i></span>
                                                    <input type="radio" name="visibleTattoo" value="1" {{ $visibleTattoo['true'] }}>
                                                    <input type="radio" name="visibleTattoo" value="0" {{ $visibleTattoo['false'] }}>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback row {{ $errors->has('studentType') ? ' has-error ' : '' }}">
                                            {!! Form::label('studentType', 'Student Type', array('class' => 'col-6 col-md-4 col-xs-6 control-label')); !!}
                                            <div class="col-6 col-md-4 col-xs-6">
                                                <label class="switch_studentType {{ $studentType['checked'] }}" for="studentType">
                                                    <span class="active"><i class="fa fa-toggle-on fa-2x"></i></span>
                                                    <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i></span>
                                                    <input type="radio" name="studentType" value="1" {{ $studentType['true'] }}>
                                                    <input type="radio" name="studentType" value="0" {{ $studentType['false'] }}>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback row {{ $errors->has('studentStatus') ? ' has-error ' : '' }}">
                                            {!! Form::label('studentStatus', 'Student Status', array('class' => 'col-6 col-md-4 col-xs-6 control-label')); !!}
                                            <div class="col-6 col-md-4 col-xs-6">
                                                <label onclick="approveByType(3)" id="switch_studentStatus" class="switch_studentStatus {{ $studentStatus['checked'] }}" for="studentStatus">
                                                    <span class="active"><i class="fa fa-toggle-on fa-2x"></i></span>
                                                    <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i></span>
                                                    <input type="radio" name="studentStatus" value="1" {{ $studentStatus['true'] }}>
                                                    <input type="radio" name="studentStatus" value="0" {{ $studentStatus['false'] }}>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback row {{ $errors->has('currentSchool') ? ' has-error ' : '' }}">
                                            {!! Form::label('currentSchool', 'Current School', array('class' => 'col-md-3 control-label')); !!}
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    {!! Form::text('currentSchool', $user->currentSchool, array('id' => 'currentSchool', 'class' => 'form-control', 'placeholder' => 'School Name')) !!}
                                                </div>
                                                @if ($errors->has('currentSchool'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('currentSchool') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    </div>
                                    
                                    </div>

                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <div class="card">
                                            <div class="card-header bg-main">
                                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                                   <b> Payment Info</b>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group has-feedback row {{ $errors->has('bankName') ? ' has-error ' : '' }}">
                                                    {!! Form::label('bankName', 'Bank Name', array('class' => 'col-md-3 control-label')); !!}
                                                    <div class="col-md-9">
                                                        <div class="input-group">
                                                            {!! Form::text('bankName', $user->bankName, array('id' => 'bankName', 'class' => 'form-control', 'placeholder' => 'Bank Name')) !!}
                                                        </div>
                                                        @if ($errors->has('bankName'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('bankName') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback row {{ $errors->has('accountNo') ? ' has-error ' : '' }}">
                                                    {!! Form::label('accountNo', 'Account No', array('class' => 'col-md-3 control-label')); !!}
                                                    <div class="col-md-9">
                                                        <div class="input-group">
                                                            {!! Form::text('accountNo', $user->accountNo, array('id' => 'accountNo', 'class' => 'form-control', 'placeholder' => 'Account No')) !!}
                                                        </div>
                                                        @if ($errors->has('accountNo'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('accountNo') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header bg-main">
                                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                                   <b> Emergency Info</b>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group has-feedback row {{ $errors->has('emergencyContactName') ? ' has-error ' : '' }}">
                                                    {!! Form::label('emergencyContactName', 'Emg Name', array('class' => 'col-md-3 control-label')); !!}
                                                    <div class="col-md-9">
                                                        <div class="input-group">
                                                            {!! Form::text('emergencyContactName', $user->emergencyContactName, array('id' => 'emergencyContactName', 'class' => 'form-control', 'placeholder' => 'Emergency Name')) !!}
                                                        </div>
                                                        @if ($errors->has('emergencyContactName'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('emergencyContactName') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group has-feedback row {{ $errors->has('emergencyContactNo') ? ' has-error ' : '' }}">
                                                    {!! Form::label('emergencyContactNo', 'Emg contactNo', array('class' => 'col-md-3 control-label')); !!}
                                                    <div class="col-md-9">
                                                        <div class="input-group">
                                                            {!! Form::text('emergencyContactNo', $user->emergencyContactNo, array('id' => 'emergencyContactNo', 'class' => 'form-control', 'placeholder' => 'Emergency contactNo')) !!}
                                                        </div>
                                                        @if ($errors->has('emergencyContactNo'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('emergencyContactNo') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group has-feedback row {{ $errors->has('relationToEmergencyContact') ? ' has-error ' : '' }}">
                                                    {!! Form::label('relationToEmergencyContact', 'Emg Relationship', array('class' => 'col-md-3 control-label')); !!}
                                                    <div class="col-md-9">
                                                        <div class="input-group">
                                                            {!! Form::text('relationToEmergencyContact', $user->relationToEmergencyContact, array('id' => 'relationToEmergencyContact', 'class' => 'form-control', 'placeholder' => 'Emergency Relationship')) !!}
                                                        </div>
                                                        @if ($errors->has('relationToEmergencyContact'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('relationToEmergencyContact') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header bg-main">
                                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                                   <b> Job Info</b>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group has-feedback row {{ $errors->has('jobsDone') ? ' has-error ' : '' }}">
                                                    {!! Form::label('jobsDone', 'Job Done', array('class' => 'col-md-3 control-label')); !!}
                                                    <div class="col-md-9">
                                                        <div class="input-group">
                                                            {!! Form::text('jobsDone', ($user->jobsDone > 0 ? $user->jobsDone : 0), array('id' => 'jobsDone', 'class' => 'form-control', 'placeholder' => 'Job Done')) !!}
                                                        </div>
                                                        @if ($errors->has('jobsDone'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('jobsDone') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback row {{ $errors->has('feedback') ? ' has-error ' : '' }}">
                                                    {!! Form::label('feedback', 'Feedback', array('class' => 'col-md-3 control-label')); !!}
                                                    <div class="col-md-9">
                                                        <div class="input-group">
                                                            {!! Form::text('feedback', $user->feedback, array('id' => 'feedback', 'class' => 'form-control', 'placeholder' => 'Feedback')) !!}
                                                        </div>
                                                        @if ($errors->has('feedback'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('feedback') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-xl-4 col-4-md col-sm-6 col-xs-6"></div>
                                <div class="col-xl-4 col-4-md col-sm-6 col-xs-6">
                                    {!! Form::button(trans('forms.save-changes'), array('class' => 'btn bg-main btn-block margin-bottom-1 mt-3 mb-2','type' => 'submit')) !!}
                                </div>
                                <div class="col-xl-4 col-4-md col-sm-6 col-xs-6"></div>
                            </div>
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="account-admin-subnav nav nav-pills nav-justified margin-bottom-3 margin-top-1">
                            <li class="nav-item bg-info">
                                <a data-toggle="pill" href="#changepw" class="nav-link danger-pill-trigger text-white active" aria-selected="true">
                                    ONGOING
                                </a>
                            </li>
                            <li class="nav-item bg-info">
                                <a data-toggle="pill" href="#deleteAccount" class="nav-link danger-pill-trigger text-white">
                                    PREV JOB
                                </a>
                            </li>
                            <li class="nav-item bg-info">
                                <a data-toggle="pill" href="#commentAccount" class="nav-link danger-pill-trigger text-white">
                                    COMMENT
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">

                            <div id="changepw" class="tab-pane fade show active">
                                <div class="table-responsive users-table">
                                    <div id="table_ongoing">
                                        @include('partials/pagination_ongoing', ['data' => $jobsOngoing, 'name' => 'ongoing'])
                                    </div>
                                </div>
                            </div>

                            <div id="deleteAccount" class="tab-pane fade">
                                <div class="table-responsive users-table">
                                    <div id="table_data">
                                        @include('partials/pagination_data', ['data' => $jobsPrev, 'name' => 'prev'])
                                    </div>
                                </div>
                            </div>

                            <div id="commentAccount" class="tab-pane fade">

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive users-table">
                                                <table class="table table-striped table-condensed data-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Time</th>
                                                            <th>Comment</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="table-comments">
                                                        @if($user->comments)
                                                        @php $comments = json_decode($user->comments, true); arsort($comments); @endphp
                                                        @foreach ($comments as $key => $cmt)
                                                            @if(is_array($cmt))
                                                            <tr>
                                                                <td>{{$cmt['timestamp']}}</td>
                                                                <td>{{$cmt['comment']}}</td>
                                                            </tr>
                                                            @else
                                                            <tr>
                                                                <td>{{$key}}</td>
                                                                <td>{{$cmt}}</td>
                                                            </tr>
                                                            @endif
                                                        @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                        </div>
                                    </div>
                                
                                <div class="row">
                                    <div class="col-sm-6 offset-sm-3 margin-bottom-3 text-center">
                                        {{-- {!! Form::model($user, ['method' => 'POST', 'route' => ['user.updateComment', $user->id], 'class' => 'form-horizontal', 'role' => 'form']) !!} --}}

                                            <div class="form-group">
                                                <div class="col-md-12 col-sm-8 col-xs-12">
                                                    {!! Form::textarea('comments', old('comments'), array('id' => 'comments', 'class' => 'form-control', 'placeholder' => 'Comments','size' => '30x3')) !!}
                                                    @if ($errors->has('comments'))<p style="color:red;">{!!$errors->first('comments')!!}</p>@endif
                                                </div>
                                            </div>
                                            {!! Form::hidden("id", $user->id) !!}
                                            <span class="btn bg-main btn-block margin-bottom-1 mt-3 mb-2 addComment"><i class="fa fa-fw fa-save" aria-hidden="true"></i> Save Changes</span>
                                            {{-- {!! Form::button(trans('forms.save-changes'), array('class' => 'btn bg-main btn-block margin-bottom-1 mt-3 mb-2','onclick' => 'addComment()')) !!} --}}

                                        {{-- {!! Form::close() !!} --}}

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.modal-save')
    @include('modals.modal-delete')
    @include('modals.modal-form')
@endsection

@section('footer_scripts')
  @include('scripts.image-modal-script')
  @include('scripts.delete-modal-script')
  @include('scripts.save-modal-script')
  @include('scripts.toggleIsActive')
  @include('scripts.check-changed')
  @include('scripts.form-modal-script')
  {{-- @include('scripts.user-avatar-dz') --}}
  <script src="{{{ config('settings.dropZoneJsCDN') }}}"></script>
  @include('scripts.user-workPassPhoto-dz')
  @include('scripts.user-NRICFront-dz')
  @include('scripts.user-NRICBack-dz')
  @include('scripts.user-userPants-dz')
  @include('scripts.user-userShoes-dz')
  @if($user->studentStatus == 1)
      @include('scripts.user-studentCardFront-dz')
      @include('scripts.user-studentCardBack-dz')
  @endif
  <script type="text/javascript">
        $('#userBirthday').datepicker({
            format:'mm/dd/yyyy',
        });

        $('.dropdown-menu li a').click(function() {
            $('.dropdown-menu li').removeClass('active');
        });

        $('.profile-trigger').click(function() {
            $('.panel').alterClass('card-*', 'card-default');
        });

        $('.settings-trigger').click(function() {
            $('.panel').alterClass('card-*', 'card-info');
        });

        $('#user_basics_form').on('keyup change', 'input, select, textarea', function(){
            $('#account_save_trigger').attr('disabled', false).removeClass('disabled').show();
        });

        $('#user_profile_form').on('keyup change', 'input, select, textarea', function(){
            $('#confirmFormSave').attr('disabled', false).removeClass('disabled').show();
        });

        $('#comments').val('');

        function approveByType(type){
            $('#waiting').show();
            if(type == 1){
                var checked;
                if($( "#switch_userPantsApproved" ).hasClass("checked")){
                    checked = 0;
                }else{
                    checked = 1;
                }
            }else if(type == 2){
                var checked;
                if($( "#switch_userShoesApproved" ).hasClass("checked")){
                    checked = 0;
                }else{
                    checked = 1;
                }
            }else if(type == 3){
                var checked;
                if($( "#switch_studentStatus" ).hasClass("checked")){
                    checked = 0;
                    $('#tabStudentImage').addClass('hidden');
                }else{
                    checked = 1;
                    $('#tabStudentImage').removeClass('hidden');
                }
            }
            console.log('checked='+checked);
            $.ajax({
                type : 'POST',
                url  : '{{route('user.approvedByType')}}',
                data : {type: type, checked: checked, id: '{{$user->id}}'},
                success  : function (data) {
                    $('#waiting').hide();
                    if(data == 'success') {
                        // Lobibox.alert('success', 
                        // {   
                        //     title: "Message",
                        //     msg: "Update success!",
                        //     callback: function ($this, type, ev) {
                        //         //window.location.href = "{{ route('user.edit',$user->id) }}";
                        //     }
                        //  });
                    } else {
                        Lobibox.alert('error', 
                        {   
                            title: "Message",
                            msg: "Update error!",
                            callback: function ($this, type, ev) {
                               window.location.href = "{{ route('user.edit',$user->id) }}";
                            }
                         });
                    }
                }
            });
        }

//ajax pagiation
$(document).ready(function(){
    $(document).on('click', '#ongoing .pagination a', function(event){
    event.preventDefault(); 
    var page = $(this).attr('href').split('page=')[1];
    console.log('ongoing');
    fetch_ongoing(page);
  });

  $(document).on('click', '.pagination a', function(event){
    event.preventDefault(); 
    var page = $(this).attr('href').split('page=')[1];
    fetch_data(page);
  });

  function fetch_data(page)
  {
    $('#waiting').show();
      $.ajax({
        url:"/pagination/fetch_data?id={{$user->id}}&type=user&page="+page+"&site={{$site}}",
        success:function(data)
        {
            $('#waiting').hide();
            $('#table_data').html(data);
        }
      });
  }

  function fetch_ongoing(page)
  {
    $('#waiting').show();
      $.ajax({
        url:"/pagination/fetch_ongoing?id={{$user->id}}&type=user&page="+page+"&site={{$site}}",
        success:function(data)
        {
            $('#waiting').hide();
            $('#table_ongoing').html(data);
        }
      });
  }
});

$('.addComment').click(function(){
    var comments = $("#comments").val();
    $('#waiting').show();
    $.ajax({
        type : 'POST',
        url  : '{{route('user.updateComment')}}',
        data : {id: '{{$user->id}}', comments: comments},
        success  : function (data) {
            $('#waiting').hide();
            var html = "<tr><td>"+data.time+"</td><td>"+data.comments+"</td></tr>";
            $('#table-comments').prepend(html);
        }
    });
});

$('.changeStatusUser').click(function(){
    $('#waiting').show();
    var type = $(this).data("type");
    var stt = $(this).data("stt");
    var data = {
        id: {{$user->id}},
        type: type,
        stt: stt
    };
    $.ajax({
        type : 'POST',
        url  : '{{route('change.statusUser')}}',
        data : data,
        success  : function (data) {
            $('#waiting').hide();
            console.log("img="+data.img+" status="+ data.status);
            if(data.status == 1){
                $('#img'+stt).parent().removeClass('deactive');
                $('#img'+stt).parent().addClass('active');
            }else{
                $('#img'+stt).parent().removeClass('active');
                $('#img'+stt).parent().addClass('deactive');
            }
            $('#img'+stt).attr("src", data.img);
        }
    });
});
    </script>
@endsection