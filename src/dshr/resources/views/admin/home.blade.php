@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if(Auth::user()->hotel_id > 0)
                        @if($data)
                        <div class="tab-content">
                            <div id="deleteAccount" class="tab-pane fade show active">
                                <div class="table-responsive users-table">
                                  <div id="table_data" class="overflow-auto">
                                    <table class="table table-striped table-condensed data-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                @if($site === 'users')
                                                    <th>Job</th>
                                                @else
                                                    <th>Username</th>
                                                @endif
                                                <th>Current Status</th>
                                                <th>Confirm</th>
                                                <th>Cancel</th>
                                                {{-- <th>In/Out</th> --}}
                                                <th>Start_date</th>
                                                <th class="hidden-xs">Paid TimeIn</th>
                                                <th class="hidden-xs">Paid TimeOut</th>
                                                <th class="hidden-xs">BreakTime</th>
                                                <th class="hidden-xs">Total Hours</th>
                                                <th class="hidden-xs">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($data)
                                            @php $j = 1; @endphp
                                            @foreach($data as $jobpv)
                                            <tr>
                                                <td>{{(20 * (request()->get('page', 1) - 1)) + $j++}}</td>
                                                @if($site === 'users')
                                                    <td><a href="{{route('admin.job.edit', $jobpv->job_id)}}">{{$jobpv->jobs->types->name}}</a></td>
                                                @else
                                                    <td><a href="{{route('user.edit', $jobpv->users->id)}}">{{$jobpv->users->userName}}</a></td>
                                                @endif
                                                <td>
                                                    <span class="btn text-{{array_get($color_status, $jobpv->status)}} btn-sm" id="text_{{$jobpv->id}}">{{array_get($status, $jobpv->status)}}</span>
                                                </td>
                                                <td class="align-center">
                                                    <div id="approve_{{$jobpv->id}}">
                                                        <span class="text-success approve changeStatus" data-id="{{$jobpv->id}}" data-type="approve" id="approve_{{$jobpv->id}}" value="approve">Approval</span><br>
                                                    </div>
                                                </td>
                                                <td class="align-center">
                                                    <div id="cancel_{{$jobpv->id}}">
                                                        <span class="text-danger changeStatus" data-id="{{$jobpv->id}}" data-type="cancel" id="cancel_{{$jobpv->id}}" value="cancel">Cancel</span>
                                                    </div>
                                                </td>
                                                {{-- <td>
                                                    @if($jobpv->status > 0)
                                                        <a href="{{route('job.inOut', $jobpv->id)}}" target="_blank" class="btn btn-warning btn-sm">In/Out</a>
                                                    @endif
                                                </td> --}}
                                                <td>{{$jobpv->jobs->start_date}}</td>
                                                <td class="align-center">{{$jobpv->paidTimeIn}}</td>
                                                <td class="align-center">{{$jobpv->paidTimeOut}}</td>
                                                <td>{{$jobpv->breakTime}}</td>
                                                <td>{{$jobpv->totalHours}}</td>
                                                <td>{{$jobpv->remarks}}</td>
                                            </tr>
                                           @endforeach
                                           @endif
                                        </tbody>
                                    </table>
                                    
                                    @if($data){{ $data->links() }}@endif
                                  </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @else
                            <h3>The registration is completed. Please contact the system admin for approval.</h3>
                        @endif
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

{{-- <script type="text/javascript">
$(document).ready(function(){
  $(document).on('click', '.pagination a', function(event){
    event.preventDefault(); 
    var page = $(this).attr('href').split('page=')[1];
    fetch_data(page);
  });

  function fetch_data(page)
  {
    $('#waiting').show();
  $.ajax({
    url:"/pagination/fetch_data?id=1&type=job&page="+page+"&jobStatus=1",
    success:function(data)
    {
      $('#waiting').hide();
      $('#table_data').html(data);
    }
  });
  }
});
</script> --}}

<script type="text/javascript">
    $('.changeStatus').click(function(){
        $('#waiting').show();
        var idstt = this.id;
        var id = $(this).data("id");
        var type = $(this).data("type");
        var data = {
            id: id,
            model: 'alljob',
            type: type
        };
        $.ajax({
            type : 'GET',
            url  : '{{route('admin.changeUpdateStatus')}}?id='+id+'&model=alljob&type='+type,
            data : data,
            success  : function (data) {
                $('#waiting').hide();
                if(data.status === 201){
                  //$('.approve').hide();
                  alert(data.msg);
                }else{
                  //$('#approve_cancel_'+id).hide();
                  if(data.status === 200){
                    $('#text_'+id).text('Confirm');
                    $('#text_'+id).removeClass('text-secondary');
                    $('#text_'+id).removeClass('text-danger');
                    $('#text_'+id).addClass('text-primary');
                  }else if(data.status === 202){
                    $('#text_'+id).text('Cancel');
                    $('#text_'+id).removeClass('text-secondary');
                    $('#text_'+id).removeClass('text-primary');
                    $('#text_'+id).addClass('text-danger');
                  }
                  //alert(data.msg);
                }
            }
        });
    });
</script>
@endsection
