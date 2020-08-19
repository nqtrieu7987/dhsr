<table class="table table-striped table-condensed data-table">
    <thead>
        <tr>
            <th>ID</th>
            @if($site === 'users')
                <th>Hotel</th>
            @else
                <th>Username</th>
            @endif
            <th>Job</th>
            <th class="hidden-xs">Status</th>
            <th class="hidden-xs">Start time</th>
            <th class="hidden-xs">End time</th>
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
                <td><a href="{{route('hotel.edit', $jobpv->Jobs()->hotel_id)}}">{{$jobpv->Jobs()->Hotels()->name}}</a></td>
            @else
                <td><a href="{{route('user.edit', $jobpv->Users()->id)}}">{{$jobpv->Users()->userName}}</a></td>
            @endif
            <td>{{ $jobpv->Jobs() != null ? array_get($jobType, $jobpv->Jobs()['job_type_id']): ''}}</td>
            <td>
                <span class="btn text-{{array_get($color_status, $jobpv->status)}} btn-sm">{{array_get($status, $jobpv->status)}}</span>
            </td>
            <td class="align-center">{{$jobpv->real_start}}</td>
            <td class="align-center">{{$jobpv->real_end}}</td>
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