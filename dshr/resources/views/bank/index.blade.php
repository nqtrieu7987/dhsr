@extends('layouts.master')

@section('template_title')
  List Bank
@endsection

@section('template_linked_css')
  @include('partials.dataTableStyle')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card panel-default">
                    {{-- <div class="card-header">

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong>List Banks</strong>
                            <div class="btn-group pull-right btn-group-xs">
                                <a class="btn btn-blue-light" href="/bank/create">
                                    <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                                    Create new bank
                                </a>
                            </div>
                        </div>
                    </div> --}}

                    <div class="card-body">
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th class="no-search no-sort">Logo</th>
                                        <th class="hidden-xs no-search no-sort">Status</th>
                                        <th class="no-search no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($banks as $bank)
                                        <tr>
                                            <td>{{$bank->id}}</td>
                                            <td><a href="{{ URL::to('bank/' . $bank->id . '/edit') }}">{{$bank->name}}</a></td>
                                            <td class="align-center">
                                                @if($bank->logo)
                                                    <img src="{{$bank->logo}}">
                                                @endif
                                            </td>
                                            <td class="align-center" id="img_status_{{$bank->id}}">
                                                {!! \App\Helper\VtHelper::checkStatusIcon($bank->is_active)!!}
                                            </td>
                                            <td class="align-center">
                                                @php $status = $bank->is_active == 1 ? 'Deactivated' : 'Active'; @endphp
                                                <span class="{{$bank->is_active == 1 ? 'text-danger' : 'text-success'}} changeStatus" data-id="{{$bank->id}}" id="status_{{$bank->id}}" value="{{$status}}">{{$status}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $banks->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.modal-delete')

@endsection

@section('footer_scripts')
    @include('scripts.datatables')

    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    @include('scripts.image-modal-script')
<script type="text/javascript">
    $('.changeStatus').click(function(){
        $('#waiting').show();
        var idstt = this.id;
        var id = $(this).data("id");
        var data = {
            id: id,
            model: 'bank',
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