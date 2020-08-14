@extends('layouts.master')

@section('template_title')
  List Job Type
@endsection

@section('template_linked_css')
  @include('partials.dataTableStyle')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card panel-default">
                    {{-- <div class="card-header">

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong>List Job Type</strong>
                            <div class="btn-group pull-right btn-group-xs">
                                <a class="btn btn-blue-light" href="/job-type/create">
                                    <img class="fa-icon" src="/images/newuser_icon.png">
                                    Create new job type
                                </a>
                            </div>
                        </div>
                    </div> --}}

                    <div class="card-body pd-xs-0">
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Comment</th>
                                        <th class="no-search no-sort">Status</th>
                                        <th class="no-search no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datas as $data)
                                        <tr>
                                            <td>{{$data->id}}</td>
                                            <td><a href="{{ URL::to('job-type/' . $data->id . '/edit') }}">{{$data->name}}</a></td>
                                            <td>{{$data->comment}}</td>
                                            <td class="align-center" id="img_status_{{$data->id}}">
                                                {!! \App\Helper\VtHelper::checkStatusIcon($data->is_active)!!}
                                            </td>
                                            <td class="align-center">
                                                @php $status = $data->is_active == 1 ? 'Deactivated' : 'Active'; @endphp
                                                <span class="{{$data->is_active == 1 ? 'text-danger' : 'text-success'}} changeStatus" data-id="{{$data->id}}" id="status_{{$data->id}}" value="{{$status}}">{{$status}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $datas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
    @include('scripts.datatables')
    @include('scripts.save-modal-script')
    <script type="text/javascript">
        $('.changeStatus').click(function(){
            $('#waiting').show();
            var idstt = this.id;
            var id = $(this).data("id");
            var data = {
                id: id,
                model: 'job-type',
            };
            $.ajax({
                type : 'POST',
                url  : '{{route('change.status')}}',
                data : data,
                success  : function (data) {
                    $('#waiting').hide();
                    if(data.msg === 0){
                        $('#'+idstt).removeClass('text-success');
                        $('#'+idstt).addClass('text-danger');
                        $('#'+idstt).text('Deactivated');
                        $('#img_'+idstt).html('<img class="status-icon" src="/images/Active.png">');
                    }else{
                        $('#'+idstt).removeClass('text-danger');
                        $('#'+idstt).addClass('text-success');
                        $('#'+idstt).text('Active');
                        $('#img_'+idstt).html('<img class="status-icon" src="/images/Deactivated.png">');
                    }
                }
            });
        });
    </script>
@endsection