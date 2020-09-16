<?php

/**
 * Paginate Collect
 */
if (! function_exists('paginate_collect')) {
    function paginate_collect($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
        return new \Illuminate\Pagination\LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}

if ( !function_exists('checkStatusIcon') ) {
    function checkStatusIcon($id, $status)
    {
        if($status == 1){
            return '<img class="status-icon changeStatus" data-id="'.$id.'" id="status_'.$id.'" src="/images/Active.png">';
        }else{
            return '<img class="status-icon changeStatus" data-id="'.$id.'" id="status_'.$id.'" src="/images/Deactivated.png">';
        }
    }
}

if ( !function_exists('checkStatusIcon') ) {
    function checkStatusIcon($id, $status)
    {
        if($status == 1){
            return '<img class="status-icon changeStatus" data-id="'.$id.'" id="status_'.$id.'" src="/images/Active.png">';
        }else{
            return '<img class="status-icon changeStatus" data-id="'.$id.'" id="status_'.$id.'" src="/images/Deactivated.png">';
        }
    }
}

if ( !function_exists('getHours') ) {
    function getHours($start_time, $end_time, $breakTime = 0)
    {
        $start_time = date("H:i", strtotime($start_time));
        $end_time = date("H:i", strtotime($end_time));

        $timeDiff = timeDiff($start_time, $end_time);
        if($timeDiff < 0){
            $time1 = timeDiff($start_time, '24:00');
            $time2 = timeDiff('00:00', $end_time);
            $totalHours = round(($time1 + $time2)/3600, 2) - $breakTime;
        }else{
            $totalHours = round($timeDiff/3600, 2) - $breakTime;
        }

        return $totalHours;
    }
}

if ( !function_exists('timeDiff') ) {
    function timeDiff($firstTime,$lastTime) {
        $firstTime=strtotime($firstTime);
        $lastTime=strtotime($lastTime);
        $timeDiff=$lastTime-$firstTime;
        return $timeDiff;
    }
}