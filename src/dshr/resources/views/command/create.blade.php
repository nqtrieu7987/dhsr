  @extends('layouts.app')

@section('template_title')
  Tạo Command
@endsection

@section('content')

  <div class="container">
    <div class="row">
      <div class="col-md-12 col-md-offset-1">
        <div class="card card-default">
          <div class="card-header">
            <a href="/viettel" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              <span class="hidden-xs">Danh sách Command </span><br/>
            </a>
          </div>
          {!! Form::open(array('action' => 'CommandController@store', 'method' => 'POST', 'role' => 'form', 'files' => true)) !!}
            {!! csrf_field() !!}

            <div class="card-body">
              <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                {!! Form::label('name', 'Tên' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('name', old('name'), array('id' => 'name', 'class' => 'form-control', 'placeholder' => 'Tên')) !!}
                    <label class="input-group-addon" for="name"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('viettel') ? ' has-error ' : '' }}">
                {!! Form::label('viettel', 'Viettel' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('viettel', old('viettel'), array('id' => 'viettel', 'class' => 'form-control', 'placeholder' => 'Cú pháp Viettel')) !!}
                    <label class="input-group-addon" for="viettel"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('mobiphone') ? ' has-error ' : '' }}">
                {!! Form::label('mobiphone', 'Mobiphone' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('mobiphone', old('mobiphone'), array('id' => 'mobiphone', 'class' => 'form-control', 'placeholder' => 'Cú pháp Mobiphone')) !!}
                    <label class="input-group-addon" for="mobiphone"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('vinaphone') ? ' has-error ' : '' }}">
                {!! Form::label('vinaphone', 'Vinaphone' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('vinaphone', old('vinaphone'), array('id' => 'vinaphone', 'class' => 'form-control', 'placeholder' => 'Cú pháp Vinaphone')) !!}
                    <label class="input-group-addon" for="vinaphone"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('other') ? ' has-error ' : '' }}">
                {!! Form::label('other', 'Other' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('other', old('other'), array('id' => 'other', 'class' => 'form-control', 'placeholder' => 'Cú pháp Other')) !!}
                    <label class="input-group-addon" for="other"><i class="fa fa-fw fa-pencil-square-o }}" aria-hidden="true"></i></label>
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
            </div>

            <div class="form-group has-feedback row {{ $errors->has('url') ? ' has-error ' : '' }}">
                {!! Form::label('url', 'Code Tracking' , array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                        {!! Form::textarea('code', old('code'), array('id' => 'code', 'class' => 'form-control', 'placeholder' => trans('Code'))) !!}
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