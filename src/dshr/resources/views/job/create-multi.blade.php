@extends('layouts.master')

@section('template_title')
  Create multiple Jobs
@endsection

@section('content')
<style type="text/css">
.help-block{
  position: absolute;
  font-size: 12px;
}
.table td, .table th {
  padding: 1rem .75rem;
  vertical-align: middle;
  border-top: 1px solid #dee2e6;
}
.fa-fw{
  color: #aaa;
}
.table-responsive{
  overflow-x:inherit;
}
</style>
<div id="app">
  <div class="container">
      <div class="row">
          <div class="col-12">
                <div id="show-mess"></div>
                <form-status></form-status>
          </div>
      </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 col-md-offset-1">
        <div class="card card-default">
            <div class="card-body">
                <div>
                    <create-jobs :hotels="{{ json_encode($hotels) }}" :types="{{ json_encode($types) }}" :view_type="{{ json_encode($view_type) }}" :showErrorItem="false"></create-jobs>
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
    $(document).ready(function() {
        $(":button").click(function(){
            var $lastRow = $("[id$=blah] tr:not('.ui-widget-header'):last"); //grab row before the last row
            var $newRow = $lastRow.clone(); //clone it
            $newRow.find(":text").val(""); //clear out textbox values    
            $lastRow.after($newRow); //add in the new row at the end
        });

    });

function saveChange(i){
    $('#error_ajax').hide();
    var id = $('#id_'+i).val();
    var hotel_id = $('#hotel_id'+i).val();
    var job_type_id = $('#job_type_id'+i).val();
    var slot = $('#slot'+i).val();
    var start_time = $('#start_time_txt'+i).val();
    var end_time = $('#end_time_txt'+i).val();
    var start_date = $('#start_date_txt'+i).val();
    var view_type = $('#view_type'+i).val();
    var data = {
        id: id,
        hotel_id: hotel_id,
        job_type_id: job_type_id,
        slot: slot,
        start_time: start_time,
        end_time: end_time,
        start_date: start_date,
        view_type: view_type
    };
    if(slot == ''){
      $('#err_slot'+i).text("Slot not null");
      $('#slot'+i).addClass('has-error').focus();
      $('#save'+i).css({"color": "#a94442"});
      return false;
    }
    if(start_time == ''){
      $('#err_start_time'+i).text("Start time not null");
      $('#start_time_txt'+i).addClass('has-error').focus();
      $('#save'+i).css({"color": "#a94442"});
      return false;
    }
    if(end_time == ''){
      $('#err_end_time').text("End time not null");
      $('#end_time_txt'+i).addClass('has-error').focus();
      $('#save'+i).css({"color": "#a94442"});
      return false;
    }

    $.ajax({
        type : 'POST',
        url  : '{{route('job.createMultiPost')}}',
        data : data,
        success  : function (data) {
          if(data.id > 0){
            $('#id_'+i).val(data.id);
            $('#success_ajax').css({"display": "block"});
            $('#success_msg').text(data.msg);
            $('#save'+i).css({"color": "#155724"});
          }
        }
    });
}
</script>
@endsection