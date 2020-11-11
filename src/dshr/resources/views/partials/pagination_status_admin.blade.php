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
            <th>In/Out</th>
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
            <td>
                @if($jobpv->status > 0)
                    <a href="{{route('admin.job.inOut', $jobpv->id)}}" target="_blank" class="btn btn-warning btn-sm">In/Out</a>
                @endif
            </td>
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