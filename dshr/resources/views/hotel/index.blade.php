@extends('layouts.master')

@section('template_title')
  List Hotel
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
                            <strong>List Hotels</strong>
                            <div class="btn-group pull-right btn-group-xs">
                                <a class="btn btn-blue-light" href="/hotel/create">
                                    <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                                    Create new hotel
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
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th class="no-search no-sort">Logo</th>
                                        <th class="no-search no-sort">Image</th>
                                        <th class="no-search no-sort">Status</th>
                                        <th class="no-search no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hotels as $hotel)
                                        <tr>
                                            <td>{{$hotel->id}}</td>
                                            <td><a href="{{ URL::to('hotel/' . $hotel->id . '/edit') }}">{{$hotel->name}}</a></td>
                                            <td>{{$hotel->phone}}</td>
                                            <td>{{$hotel->address}}</td>
                                            <td class="align-center">
                                                @if($hotel->logo)
                                                    <img onclick="showModalImage(this)" src="{{$hotel->logo}}" height="50">
                                                @endif
                                            </td>
                                            <td class="align-center">
                                                @if($hotel->image)
                                                    <img onclick="showModalImage(this)" src="{{$hotel->image}}" alt="{{$hotel->name}}" height="50">
                                                @endif
                                            </td>
                                            <td class="align-center" id="img_status_{{$hotel->id}}">
                                                {!! \App\Helper\VtHelper::checkStatusIcon($hotel->is_active)!!}
                                            </td>
                                            <td class="align-center">
                                                @php $status = $hotel->is_active == 1 ? 'Deactivated' : 'Active'; @endphp
                                                <span class="{{$hotel->is_active == 1 ? 'text-danger' : 'text-success'}} changeStatus" data-id="{{$hotel->id}}" id="status_{{$hotel->id}}" value="{{$status}}">{{$status}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $hotels->links() }}
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
    @include('scripts.image-modal-script')
    <script type="text/javascript">
        $('.changeStatus').click(function(){
            $('#waiting').show();
            var idstt = this.id;
            var id = $(this).data("id");
            var data = {
                id: id,
                model: 'hotel',
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