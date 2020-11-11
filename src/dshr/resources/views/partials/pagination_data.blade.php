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
        @foreach($data as $jobpv)
        <tr>
            <td>{{(20 * (request()->get('page', 1) - 1)) + $j++}}</td>
            @if(\Route::current()->getName() === "user.edit")
                <td><a href="{{route('hotel.edit', $jobpv->jobs->hotel_id)}}">{{$jobpv->jobs->hotels->name}}</a></td>
            @else
                <td><a href="{{route('user.edit', $jobpv->users->id)}}">{{$jobpv->users->userName}}</a></td>
            @endif
            <td>{{ $jobpv->jobs != null ? array_get($jobType, $jobpv->jobs['job_type_id']): ''}}</td>
            <td>
                <span class="btn text-{{array_get($color_status, $jobpv->status)}} btn-sm">{{array_get($status, $jobpv->status)}}</span>
            </td>
            <td class="align-center">{{$jobpv->real_start}}</td>
            <td class="align-center">{{$jobpv->real_end}}</td>
            <td class="align-center">{{$jobpv->start_date}}</td>
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
<div id="preview">
    @if($data){{ $data->links() }}@endif
</div>