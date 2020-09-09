<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        table, th, td {  
            border: 1px solid black !important;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
@php
date_default_timezone_set('Asia/Ho_Chi_Minh');
$date = getdate();
@endphp
<table>
    <tbody>
        <tr>
            @php
            if($hotel->logo != ''){
                $img = file_get_contents(MEDIADOMAIN.$hotel->logo);
                $image = base64_encode($img);
            }
            @endphp
            <td colspan="3" rowspan="3" style="width: 100px; height: 100px">@if($hotel->logo != '')<img style="width: 100px; height: 100px" src="{!! \App\Helper\VtHelper::base64_to_jpeg('data:image/'.strtolower(pathinfo(MEDIADOMAIN.$hotel->logo, PATHINFO_EXTENSION)).';base64,'.$image, public_path('uploads/abc.png'))!!}">@endif</td>
            <td colspan="15" style="height: 60px;text-align: center; margin: 0 auto;position: relative;top: -20px;">
                <b>{{strtoupper($job->name)}} OPERATIONS <br>
                CASUAL LABOUR ATTENDANCE LIST</b>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" colspan="2"><b>Date:</b></td>
            <td style="text-align: center;" colspan="13"><b>{{$date['weekday'].', '.$date['mday'].'-'.$date['month'].' '.$date['year']}}</b></td>
        </tr>
        <tr>
            <td style="text-align: center;" colspan="2"><b>Venue:</b></td>
            <td style="text-align: center;" colspan="13"><b>{{$hotel->name}}</b></td>
        </tr>
        <tr>
            <td style="background-color: #FEFD4E; text-align: center;font-size: 14px;" colspan="8">Completed by Agency</td>
            <td style="background-color: #FEFD4E; text-align: center;font-size: 14px;" colspan="5">Completed by STAFF</td>
            <td style="background-color: #FEFD4E; text-align: center;font-size: 14px;" colspan="5">Completed by {{strtoupper($job->name)}} OPS</td>
        </tr>
        <tr>
        @foreach($data[0] as $key => $value)
          <td style="text-align: center;border: 1px solid black;"><b>{{ ucfirst($key) }}</b></td>
        @endforeach
        </tr>

        @foreach($data as $row)
        	<tr>
            @foreach ($row as $value)
        	    <td style="border: 1px solid black">{{ $value }}</td>
            @endforeach        
    	</tr>
        @endforeach
    </tbody>
</table>
</body>