<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clocking;
use App\Models\JobType;
use App\Models\AllJob;
use App\Models\Job;
use App\Models\Hotel;
use App\Models\Bank;
use App\Models\User;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Helper\VtHelper;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function clocking(Request $request){
		$datas = Clocking::orderBy('created_at', 'DESC')->orderBy('user_id', 'DESC')->paginate(50);
		$jobType = JobType::pluck('name', 'id')->toArray();
        $hotels = Hotel::pluck('name', 'id')->toArray();
    	return view('home.clocking', compact('datas','jobType','hotels'))
                    ->with('site', 'Clocking');
    }

    public function chartjs(){
    	$datas = AllJob::selectRaw("user_id, COUNT(*) AS 'tong'")->whereRaw('user_id > 200 and user_id < 300')->groupBy('user_id')->get()->toArray();
    	$users = User::pluck('userName', 'id')->toArray();

    	return view('home.chartjs', compact('datas','users'))
                    ->with('page', 'index');
    }

    public function changeStatus(Request $request){
        $type = $request->type;
        switch ($request->model) {
            case 'job':
                $data = Job::find($request->id);
                $data->is_active = abs($data->is_active - 1);
                $msg = abs($data->is_active - 1);
                break;
            case 'job-type':
                $data = JobType::find($request->id);
                $data->is_active = abs($data->is_active - 1);
                $msg = abs($data->is_active - 1);
                break;
            case 'hotel':
                $data = Hotel::find($request->id);
                $data->is_active = abs($data->is_active - 1);
                $msg = abs($data->is_active - 1);
                break;
            case 'bank':
                $data = Bank::find($request->id);
                $data->is_active = abs($data->is_active - 1);
                $msg = abs($data->is_active - 1);
                break;
            case 'user':
                $data = User::find($request->id);
                if($type == 'userPantsApproved'){
                    $data->userPantsApproved = $request->value;
                    $msg = $request->value;
                    if($request->value == 0){
                        $data->userPants = null;
                        $data->userPantsApproved = null;
                    }
                }else if($type == 'userShoesApproved'){
                    $data->userShoesApproved = $request->value;
                    $msg = $request->value;
                    if($request->value == 0){
                        $data->userShoes = null;
                        $data->userShoesApproved = null;
                    }
                }else{
                    $data->activated = abs($data->activated - 1);
                    $msg = abs($data->activated - 1);
                }
                break;
            default:
                # code...
                break;
        }
        $data->save();
        return response()->json([
            'msg' => (int)$msg,
            'status' => '200',
            'type' => $type,
        ]);
    }

    public function changeStatusUser(Request $request){
        $data = User::find($request->id);
        switch ($request->type) {
            case 'isFavourite':
                $data->update(['isFavourite' => abs($data->isFavourite - 1)]);
                $status = $data->isFavourite;
                break;
            case 'isWarned':
                $data->update(['isWarned' => abs($data->isWarned - 1)]);
                $status = $data->isWarned;
                break;
            case 'isDiamond':
                $data->update(['isDiamond' => abs($data->isDiamond - 1)]);
                $status = $data->isDiamond;
                break;
            case 'isW':
                $data->update(['isW' => abs($data->isW - 1)]);
                $status = $data->isW;
                break;
            case 'isMO':
                $data->update(['isMO' => abs($data->isMO - 1)]);
                $status = $data->isMO;
                break;
            case 'isMC':
                $data->update(['isMC' => abs($data->isMC - 1)]);
                $status = $data->isMC;
                break;
            case 'isRWS':
                $data->update(['isRWS' => abs($data->isRWS - 1)]);
                $status = $data->isRWS;
                break;
            case 'isKempinski':
                $data->update(['isKempinski' => abs($data->isKempinski - 1)]);
                $status = $data->isKempinski;
                break;
            case 'isHilton':
                $data->update(['isHilton' => abs($data->isHilton - 1)]);
                $status = $data->isHilton;
                break;
            case 'TCC':
                $data->update(['TCC' => abs($data->TCC - 1)]);
                $status = $data->TCC;
                break;
            case 'isGWP':
                $data->update(['isGWP' => abs($data->isGWP - 1)]);
                $status = $data->isGWP;
                break;
            default:
                # code...
                break;
        }
        $statusName = 'active';
        if($status == 0){
            $statusName = 'deactive';
        }
        return response()->json([
            'status' => $status,
            'img' => '/images/status/'.$request->stt.'-'.$statusName.'.png'
        ]);
    }

    public function changeJobStatus(Request $request){
        $data = AllJob::find($request->id);
        if($request->type == 'approve'){
            $status = 1;
            Log::info($data->Jobs()->slot.' - '.$data->Jobs()->current_slot);
            if($data->Jobs()->current_slot >= $data->Jobs()->slot){
                return response()->json([
                    'msg' => 'Job full slot',
                    'status' => 201
                ]);
            }else{
                // Cập nhật current_slot của job lên 1 đơn vị
                if(in_array($data->status, [0,4,5])){
                    $data->status = $status;
                    $data->Jobs()->update(['current_slot' => $data->Jobs()->current_slot + 1]);
                    $msg = 'Approve Successfully!';
                    $stt = 200;
                }else{
                    $msg = 'Failed!';
                    $stt = 203;
                }
            }
        }else{
            $status = 4;
            if(in_array($data->status, [1,2,3]) && $data->Jobs()->current_slot > 0){
                $data->Jobs()->update(['current_slot' => $data->Jobs()->current_slot - 1]);
            }
            $data->status = $status;
            $msg = 'Cancel Successfully!';
            $stt = 202;
        }
        $data->save();
        return response()->json([
            'msg' => $msg,
            'status' => $stt
        ]);
    }

    public function changeUpdateStatus(Request $request){
        $data = AllJob::find($request->id);
        if($request->type == 'approve'){
            $status = 1;
            Log::info($data->Jobs()->slot.' - '.$data->Jobs()->current_slot);
            if($data->Jobs()->current_slot >= $data->Jobs()->slot){
                return response()->json([
                    'msg' => 'Job full slot',
                    'status' => 201
                ]);
            }else{
                // Cập nhật current_slot của job lên 1 đơn vị
                if(in_array($data->status, [0,4,5])){
                    $data->status = $status;
                    $data->Jobs()->update(['current_slot' => $data->Jobs()->current_slot + 1]);
                    $msg = 'Approve Successfully!';
                    $stt = 200;
                }else{
                    $msg = 'Failed!';
                    $stt = 203;
                }
            }
        }else{
            $status = 4;
            if(in_array($data->status, [1,2,3]) && $data->Jobs()->current_slot > 0){
                $data->Jobs()->update(['current_slot' => $data->Jobs()->current_slot - 1]);
            }
            $data->status = $status;
            $msg = 'Cancel Successfully!';
            $stt = 202;

            $data->userPants = null;
            $data->userShoes = null;
            $data->userPantsApproved = 0;
            $data->userShoesApproved = 0;
        }
        $data->save();
        return response()->json([
            'msg' => $msg,
            'status' => $stt
        ]);
    }
}