  @extends('layouts.app')

@section('template_title')
  Tạo banner
@endsection

@section('content')

  <div class="container">
    <div class="row">
      <div class="col-md-12 col-md-offset-1">
        <div class="card card-default">
          <div class="card-header">
            <a href="/banner" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              <span class="hidden-xs">Danh sách ảnh </span><br/>
            </a>
          </div>
          {!! Form::open(array('action' => 'BannerController@store', 'method' => 'POST', 'role' => 'form', 'files' => true)) !!}
            {!! csrf_field() !!}

            <div class="card-body">
              <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                {!! Form::label('name', 'Tên' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('name', old('name'), array('id' => 'name', 'class' => 'form-control', 'placeholder' => 'Tên ảnh')) !!}
                    <label class="input-group-addon" for="name"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('url') ? ' has-error ' : '' }}">
                {!! Form::label('url', 'Url' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('url', old('url'), array('id' => 'url', 'class' => 'form-control', 'placeholder' => 'Url')) !!}
                    <label class="input-group-addon" for="url"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>
              
              <div class="form-group has-feedback row {{ $errors->has('is_active') ? ' has-error ' : '' }}">
                {!! Form::label('is_active', trans('Active') , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <label class="switch checked" for="is_active">
                    <span class="active"><i class="fa fa-toggle-on fa-2x"></i> {{ trans('themes.statusEnabled') }}</span>
                    <span class="inactive"><i class="fa fa-toggle-on fa-2x fa-rotate-180"></i> {{ trans('themes.statusDisabled') }}</span>
                    <input type="radio" name="is_active" value="1" checked>
                    <input type="radio" name="is_active" value="0">
                  </label>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('logo') ? ' has-error ' : '' }}">
                {!! Form::label('logo', trans('Logo'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::file('logo', null, array('class' => 'form-control ')) !!}
                    @if ($errors->has('logo'))<p style="color:red;">{!!$errors->first('logo')!!}</p>@endif
                  </div>
                  @if ($errors->has('logo'))
                    <span class="help-block">
                        <strong>{{ $errors->first('logo') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('header') ? ' has-error ' : '' }}">
                {!! Form::label('header', trans('Header (414 x 45px)'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::file('header', null, array('class' => 'form-control ')) !!}
                    @if ($errors->has('header'))<p style="color:red;">{!!$errors->first('header')!!}</p>@endif
                  </div>
                  @if ($errors->has('header'))
                    <span class="help-block">
                        <strong>{{ $errors->first('header') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('footer') ? ' has-error ' : '' }}">
                {!! Form::label('footer', trans('Footer (414 x 45px)'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::file('footer', null, array('class' => 'form-control ')) !!}
                    @if ($errors->has('footer'))<p style="color:red;">{!!$errors->first('footer')!!}</p>@endif
                  </div>
                  @if ($errors->has('footer'))
                    <span class="help-block">
                        <strong>{{ $errors->first('footer') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('bg') ? ' has-error ' : '' }}">
                {!! Form::label('bg', trans('Background (nhỏ hơn 10px)'), array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::file('bg', null, array('class' => 'form-control ')) !!}
                    @if ($errors->has('bg'))<p style="color:red;">{!!$errors->first('bg')!!}</p>@endif
                  </div>
                  @if ($errors->has('bg'))
                    <span class="help-block">
                        <strong>{{ $errors->first('bg') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('text_footer') ? ' has-error ' : '' }}">
                {!! Form::label('text_footer', 'Text footer' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('text_footer', old('text_footer'), array('id' => 'text_footer', 'class' => 'form-control', 'placeholder' => 'Text footer')) !!}
                    <label class="input-group-addon" for="type"><i class="fa fa-fw fa-pencil-square-o" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col-xs-3"></div>
                <div class="col-xs-6">
                  {!! Form::button('<i class="fa fa-fw fa-save" aria-hidden="true"></i> Save Changes', array('class' => 'btn btn-success btn-block margin-bottom-1 btn-save','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'data-title' => trans('modals.edit_user__modal_text_confirm_title'), 'data-message' => trans('modals.edit_user__modal_text_confirm_message'))) !!}
                </div>
                <div class="col-xs-3"></div>
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
    $(document).ready(function() {
        $('#published_time').datetimepicker({
          format: "yyyy-mm-dd hh:ii"
        });
    });
</script>
@endsection