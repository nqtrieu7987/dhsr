@extends('layouts.master')

@section('template_title')
  Edit hotel
@endsection

@php
    $hotelActive = [
        'checked' => '',
        'value' => 0,
        'true'  => '',
        'false' => 'checked'
    ];

    if($hotel->is_active == 1) {
        $hotelActive = [
            'checked' => 'checked',
            'value' => 1,
            'true'  => 'checked',
            'false' => ''
        ];
    }
@endphp

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 col-md-offset-1">
        <div class="card panel-default">
          {{-- <div class="card-header">
            <a href="/hotel" class="btn btn-blue-light btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              <span class="hidden-xs">Back to list </span><br/>
            </a>
          </div> --}}

          {!! Form::model($hotel, array('action' => array('HotelController@update', $hotel->id), 'method' => 'PUT', 'files' => true)) !!}
            {!! csrf_field() !!}

            <div class="card-body">

              <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                {!! Form::label('name', 'Name' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('name', old('name'), array('id' => 'name', 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => trans('forms.ph-username'))) !!}
                  </div>
                  @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('phone') ? ' has-error ' : '' }}">
                {!! Form::label('phone', 'Phone' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('phone', old('phone'), array('id' => 'phone', 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Phone')) !!}
                  </div>
                  @if ($errors->has('phone'))
                    <span class="help-block">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('address') ? ' has-error ' : '' }}">
                {!! Form::label('address', 'Address' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('address', old('address'), array('id' => 'address', 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Address')) !!}
                  </div>
                  @if ($errors->has('address'))
                    <span class="help-block">
                        <strong>{{ $errors->first('address') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('logo') ? ' has-error ' : '' }}">
                {!! Form::label('logo', trans('Logo'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9 pl-0">
                  <div class="input-group">
                    <div class="file-field">
                      <a class="btn-floating peach-gradient mt-0 float-left waves-effect waves-light">
                        <i class="fas fa-paperclip" aria-hidden="true"></i>
                        <input type="file" name="logo" id="img1" onchange="ValidateSingleInput(this);">
                      </a>
                      <div class="file-path-wrapper">
                        <input class="file-path validate" id="img1_name" type="text" readonly placeholder="Upload your file">
                      </div>
                    </div>
                    @if ($errors->has('logo'))<p style="color:red;">{!!$errors->first('logo')!!}</p>@endif
                    @if($hotel->logo !=null || $hotel->logo!='')<img class="img-responsive logo-show" width="90" src="{!!$hotel->logo!!}">@endif
                  </div>
                  @if ($errors->has('image'))
                    <span class="help-block">
                        <strong>{{ $errors->first('image') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('image') ? ' has-error ' : '' }}">
                {!! Form::label('image', trans('Image'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9 pl-0">
                  <div class="input-group">
                    <div class="file-field">
                        <a class="btn-floating peach-gradient mt-0 float-left waves-effect waves-light">
                          <i class="fas fa-paperclip" aria-hidden="true"></i>
                          <input type="file" id="img2" name="image" onchange="ValidateSingleInput(this);">
                        </a>
                        <div class="file-path-wrapper">
                          <input class="file-path validate" id="img2_name" type="text" readonly placeholder="Upload your file">
                        </div>
                      </div>

                    @if ($errors->has('image'))<p style="color:red;">{!!$errors->first('image')!!}</p>@endif
                    @if($hotel->image !=null || $hotel->image!='')<img class="img-responsive logo-show" width="90" src="{!!$hotel->image!!}">@endif
                  </div>
                  @if ($errors->has('image'))
                    <span class="help-block">
                        <strong>{{ $errors->first('image') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('is_active') ? ' has-error ' : '' }}">
                {!! Form::label('is_active', 'Active', array('class' => 'col-3 col-md-3 col-sm-3 col-xs-3 control-label')); !!}
                <div class="col-3 col-md-3 col-sm-3 col-xs-3">
                    <label class="switch {{ $hotelActive['checked'] }}" for="is_active">
                        <span class="active"><i class="fa fa-toggle-on fa-2x"></i></span>
                        <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i></span>
                        <input type="radio" name="is_active" value="1" {{ $hotelActive['true'] }}>
                        <input type="radio" name="is_active" value="0" {{ $hotelActive['false'] }}>
                    </label>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                  {!! Form::button('<i class="fa fa-fw fa-save" aria-hidden="true"></i> Save Changes', array('class' => 'btn bg-main btn-block margin-bottom-1 btn-save','type' => 'submit')) !!}
                </div>
                <div class="col-md-4"></div>
              </div>
            </div>

          {!! Form::close() !!}

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
                        </ul>
                        <div class="tab-content">

                            <div id="changepw" class="tab-pane fade show active">
                                <div class="table-responsive users-table">
                                    <div id="table_ongoing" class="overflow-auto">
                                        @include('partials/pagination_ongoing', ['data' => $jobsOngoing, 'name' => 'ongoing'])
                                    </div>
                                </div>
                            </div>

                            <div id="deleteAccount" class="tab-pane fade">
                                <div class="table-responsive users-table">
                                  <div id="table_data" class="overflow-auto">
                                    @include('partials/pagination_data', ['data' => $jobsPrev, 'name' => 'prev'])
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

@endsection

@section('footer_scripts')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    @include('scripts.check-changed')
    @include('scripts.toggleIsActive')
<script type="text/javascript">
$(document).ready(function(){
  $(document).on('click', '#preview .pagination a', function(event){
    event.preventDefault(); 
    var page = $(this).attr('href').split('page=')[1];
    fetch_data(page);
  });

  $(document).on('click', '#ongoing .pagination a', function(event){
    event.preventDefault(); 
    var page = $(this).attr('href').split('page=')[1];
    console.log('ongoing');
    fetch_ongoing(page);
  });

  function fetch_data(page)
  {
    $('#waiting').show();
    $.ajax({
      url:"/pagination/fetch_data?id={{$hotel->id}}&type=hotel&page="+page,
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
        url:"/pagination/fetch_ongoing?id={{$hotel->id}}&type=hotel&page="+page,
        success:function(data)
        {
          $('#waiting').hide();
          $('#table_ongoing').html(data);
        }
      });
  }
});

  var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png"];
    function ValidateSingleInput(oInput) {
      var id = oInput.id;
      if (oInput.type == "file") {
          var sFileName = oInput.value;
           if (sFileName.length > 0) {
            $('#'+id+'_name').val(sFileName);
              var blnValid = false;
              for (var j = 0; j < _validFileExtensions.length; j++) {
                  var sCurExtension = _validFileExtensions[j];
                  console.log('sCurExtension='+sCurExtension);
                  if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                      blnValid = true;
                      break;
                  }
              }
               
              if (!blnValid) {
                  alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                  oInput.value = "";
                  return false;
              }
          }
      }
      return true;
    }
</script>
@endsection