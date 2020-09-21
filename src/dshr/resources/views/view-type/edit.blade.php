@extends('layouts.master')

@section('template_title')
  Edit ViewType
@endsection

@php
    $dataActive = [
        'checked' => '',
        'value' => 0,
        'true'  => '',
        'false' => 'checked'
    ];

    if($data->is_active == 1) {
        $dataActive = [
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
          {!! Form::model($data, array('action' => array('ViewTypeController@update', $data->id), 'method' => 'PUT', 'files' => true)) !!}
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

              <div class="form-group has-feedback row {{ $errors->has('image_active') ? ' has-error ' : '' }}">
                {!! Form::label('image_active', trans('Image active'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9 pl-0">
                  <div class="input-group">
                    <div class="file-field">
                      <a class="btn-floating peach-gradient mt-0 float-left waves-effect waves-light">
                        <i class="fas fa-paperclip" aria-hidden="true"></i>
                        <input type="file" name="image_active" id="img1" onchange="ValidateSingleInput(this);">
                      </a>
                      <div class="file-path-wrapper">
                        <input class="file-path validate" id="img1_name" type="text" readonly placeholder="Upload your file">
                      </div>
                    </div>
                    @if ($errors->has('image_active'))<p style="color:red;">{!!$errors->first('image_active')!!}</p>@endif
                    @if($data->image_active !=null || $data->image_active!='')<img class="img-responsive image_active-show" width="90" src="{!!$data->image_active!!}">@endif
                  </div>
                  @if ($errors->has('image_active'))
                    <span class="help-block">
                        <strong>{{ $errors->first('image_active') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('image_deactive') ? ' has-error ' : '' }}">
                {!! Form::label('image_deactive', trans('Image deactive'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9 pl-0">
                  <div class="input-group">
                    <div class="file-field">
                        <a class="btn-floating peach-gradient mt-0 float-left waves-effect waves-light">
                          <i class="fas fa-paperclip" aria-hidden="true"></i>
                          <input type="file" id="img2" name="image_deactive" onchange="ValidateSingleInput(this);">
                        </a>
                        <div class="file-path-wrapper">
                          <input class="file-path validate" id="img2_name" type="text" readonly placeholder="Upload your file">
                        </div>
                      </div>

                    @if ($errors->has('image_deactive'))<p style="color:red;">{!!$errors->first('image_deactive')!!}</p>@endif
                    @if($data->image_deactive !=null || $data->image_deactive!='')<img class="img-responsive image_active-show" width="90" src="{!!$data->image_deactive!!}">@endif
                  </div>
                  @if ($errors->has('image_deactive'))
                    <span class="help-block">
                        <strong>{{ $errors->first('image_deactive') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('is_active') ? ' has-error ' : '' }}">
                {!! Form::label('is_active', 'Active', array('class' => 'col-3 col-md-3 col-sm-3 col-xs-3 control-label')); !!}
                <div class="col-3 col-md-3 col-sm-3 col-xs-3">
                    <label class="switch {{ $dataActive['checked'] }}" for="is_active">
                        <span class="active"><i class="fa fa-toggle-on fa-2x"></i></span>
                        <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i></span>
                        <input type="radio" name="is_active" value="1" {{ $dataActive['true'] }}>
                        <input type="radio" name="is_active" value="0" {{ $dataActive['false'] }}>
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