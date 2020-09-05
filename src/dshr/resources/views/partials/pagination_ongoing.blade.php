<table class="table table-striped table-condensed data-table">
    <thead>
        <tr>
            <th>ID</th>
            @if(\Route::current()->getName() === "user.edit")
                <th>Hotel</th>
            @else
                <th>Username</th>
            @endif
            <th>Job</th>
            <th>Status</th>
            <th>Start time</th>
            <th>End time</th>
            <th>Start date</th>
            <th>Paid TimeIn</th>
            <th>Paid TimeOut</th>
            <th>BreakTime</th>
            <th>Total Hours</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @if($data)
        @php $j = 1; @endphp
        @foreach($data as $jobon)
        <tr>
            <td>{{(20 * (request()->get('page', 1) - 1)) + $j++}}</td>
            @if(\Route::current()->getName() === 'user.edit')
                <td><a href="{{route('hotel.edit', $jobon->Jobs()->hotel_id)}}">{{$jobon->Jobs()->Hotels()->name}}</a></td>
            @else
                <td><a href="{{route('user.edit', $jobon->Users()->id)}}">{{$jobon->Users()->userName}}</a></td>
            @endif
            <td>{{ $jobon->Jobs() != null ? array_get($jobType, $jobon->Jobs()['job_type_id']): ''}}</td>
            <td>
                <span class="btn text-{{array_get($color_status, $jobon->status)}} btn-sm">{{array_get($status, $jobon->status)}}</span>
            </td>
            <td class="align-center">{{$jobon->real_start}}</td>
            <td class="align-center">{{$jobon->real_end}}</td>
            <td class="align-center">{{$jobon->start_date}}</td>
            <td class="align-center">{{$jobon->paidTimeIn}}</td>
            <td class="align-center">{{$jobon->paidTimeOut}}</td>
            <td>{{$jobon->breakTime}}</td>
            <td>{{$jobon->totalHours}}</td>
            <td>{{$jobon->remarks}}</td>
        </tr>
       @endforeach
       @endif
    </tbody>
</table>
<div id="ongoing">
    @if($data){{ $data->links() }}@endif
</div>