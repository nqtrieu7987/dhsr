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
use App\Models\Admin;
use App\Models\ViewType;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Helper\VtHelper;
use Illuminate\Support\Facades\Log;
use Auth;

class HomeController extends Controller
{
    public function clocking(Request $request){
		$datas = Clocking::with('jobs', 'users')->orderBy('created_at', 'DESC')->orderBy('user_id', 'DESC')->paginate(50);
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
                $data = Job::findOrFail($request->id);
                $data->is_active = abs($data->is_active - 1);
                $msg = abs($data->is_active - 1);
                break;
            case 'job-type':
                $data = JobType::findOrFail($request->id);
                $data->is_active = abs($data->is_active - 1);
                $msg = abs($data->is_active - 1);
                break;
            case 'hotel':
                $data = Hotel::findOrFail($request->id);
                $data->is_active = abs($data->is_active - 1);
                $msg = abs($data->is_active - 1);
                break;
            case 'bank':
                $data = Bank::findOrFail($request->id);
                $data->is_active = abs($data->is_active - 1);
                $msg = abs($data->is_active - 1);
                break;
            case 'user':
                $data = User::findOrFail($request->id);
                $status = true;
                if($type == 'userPantsApproved'){
                    $data->userPantsApproved = $request->value;
                    $msg = $request->value;
                    if($request->value == 0){
                        //Xoa file khi reject
                        /*if(file_exists(public_path().$data->userPants) && $data->userPants != ''){
                            unlink(public_path().$data->userPants);
                            $thumb = str_replace('.png', '_thumb.png', $data->userPants);
                            if(file_exists(public_path().$thumb)){
                                unlink(public_path().$thumb);
                            }
                        }
                        $data->userPants = null;*/
                        $data->userPantsApproved = null;
                        $status = false;
                    }

                    $body = array('email' => $data->email,'type' => true,'status' => $status);
                    try {
                        $res = config('app.service')->post('user/notify_pants_shoes', [
                            'form_params' => $body
                        ]);
                        \Log::channel('approvalImage')->info("User: ".Auth::user()->id. " data=". json_encode($body));
                    } catch (\GuzzleHttp\Exception\ClientException $e) {}
                }else if($type == 'userShoesApproved'){
                    $data->userShoesApproved = $request->value;
                    $msg = $request->value;
                    if($request->value == 0){
                        //Xoa file khi reject
                        /*if(file_exists(public_path().$data->userShoes) && $data->userShoes != ''){
                            unlink(public_path().$data->userShoes);
                            $thumb = str_replace('.png', '_thumb.png', $data->userShoes);
                            if(file_exists(public_path().$thumb)){
                                unlink(public_path().$thumb);
                            }
                        }
                        $data->userShoes = null;*/
                        $data->userShoesApproved = null;
                        $status = false;
                    }
                    $body = array('email' => $data->email,'type' => false,'status' => $status);
                    try {
                        $res = config('app.service')->post('user/notify_pants_shoes', [
                            'form_params' => $body
                        ]);
                        \Log::channel('approvalImage')->info("User: ".Auth::user()->id. " data=". json_encode($body));
                    } catch (\GuzzleHttp\Exception\ClientException $e) {}
                }else{
                    $data->activated = abs($data->activated - 1);
                    $msg = abs($data->activated - 1);

                    if($data->status_data == null || $data->status_data ==''){
                        $viewTypes = ViewType::where('is_active', 1)->pluck('name','id')->toArray();
                        foreach ($viewTypes as $key => $value) {
                            $status_data[$key] = 0;
                        }
                        $data->status_data = json_encode($status_data);
                    }
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
        $user = User::findOrFail($request->id);
        if($user->status_data == null || $user->status_data ==''){
            $viewTypes = ViewType::where('is_active', 1)->pluck('name','id')->toArray();
            foreach ($viewTypes as $key => $value) {
                $status_data[$key] = 0;
            }
            $user->status_data = json_encode($status_data);
        }
        $status_data = json_decode($user->status_data, true);
        $status = $request->stt;
        $type = $request->type;
        $status_data[$type] = abs($status - 1);
        $user->update(['status_data' => json_encode($status_data)]);

        $statusName = 'deactive';
        if($status == 0){
            $statusName = 'active';
        }
        return response()->json([
            'status' => $status,
            'img' => MEDIADOMAIN.'/uploads/images/view-type/'.$type.'-'.$statusName.'.png',
            'stt' => $type
        ]);
    }

    public function changeJobStatus(Request $request){
        $data = AllJob::findOrFail($request->id);
        if($request->type == 'approve'){
            $status = 1;
            Log::info($data->jobs->slot.' - '.$data->jobs->current_slot);
            if($data->jobs->current_slot >= $data->jobs->slot){
                return response()->json([
                    'msg' => 'Job full slot',
                    'status' => 201
                ]);
            }else{
                // Cập nhật current_slot của job lên 1 đơn vị
                if(in_array($data->status, [0,4,5])){
                    $data->status = $status;
                    $data->jobs->update(['current_slot' => $data->jobs->current_slot + 1]);
                    $msg = 'Approve Successfully!';
                    $stt = 200;

                    //Push notify approved job
                    $body = array('email' => $data->users->email,'status' => 1,'job_name' => $data->jobs->types->name,'hotel_name' => $data->jobs->hotels->name);
                    try {
                        $res = config('app.service')->post('user/notify_job_status', [
                            'form_params' => $body
                        ]);
                    } catch (\GuzzleHttp\Exception\ClientException $e) {}
                }else{
                    $msg = 'Failed!';
                    $stt = 203;
                }
            }
        }else{
            $status = 4;
            if(in_array($data->status, [1,2,3]) && $data->jobs->current_slot > 0){
                $data->jobs->update(['current_slot' => $data->jobs->current_slot - 1]);
            }
            $data->status = $status;
            $msg = 'Cancel Successfully!';
            $stt = 202;

            //Push notify cancel job
            if($data->status != 4){
                $body = array('email' => $data->users->email,'status' => 4,'job_name' => $data->jobs->types->name,'hotel_name' => $data->jobs->hotels->name);
                try {
                    $res = config('app.service')->post('user/notify_job_status', [
                        'form_params' => $body
                    ]);
                } catch (\GuzzleHttp\Exception\ClientException $e) {}
            }
        }
        $data->save();
        return response()->json([
            'msg' => $msg,
            'status' => $stt
        ]);
    }

    public function changeUpdateStatus(Request $request){
        $data = AllJob::findOrFail($request->id);
        if($request->type == 'approve'){
            $status = 1;
            Log::info($data->jobs->slot.' - '.$data->jobs->current_slot);
            if($data->jobs->current_slot >= $data->jobs->slot){
                return response()->json([
                    'msg' => 'Job full slot',
                    'status' => 201
                ]);
            }else{
                // Cập nhật current_slot của job lên 1 đơn vị
                if(in_array($data->status, [0,4,5])){
                    $data->status = $status;
                    $data->jobs->update(['current_slot' => $data->jobs->current_slot + 1]);
                    $msg = 'Approve Successfully!';
                    $stt = 200;

                    //Push notify approved job
                    $body = array('email' => $data->users->email,'status' => 1,'job_name' => $data->jobs->types->name,'hotel_name' => $data->jobs->hotels->name);
                    try {
                        $res = config('app.service')->post('user/notify_job_status', [
                            'form_params' => $body
                        ]);
                    } catch (\GuzzleHttp\Exception\ClientException $e) {}
                }else{
                    $msg = 'Failed!';
                    $stt = 203;
                }
            }
        }else{
            $status = 4;
            if(in_array($data->status, [1,2,3]) && $data->jobs->current_slot > 0){
                $data->jobs->update(['current_slot' => $data->jobs->current_slot - 1]);
            }
            $data->status = $status;
            $msg = 'Cancel Successfully!';
            $stt = 202;

            /*$data->userPants = null;
            $data->userShoes = null;
            $data->userPantsApproved = 0;
            $data->userShoesApproved = 0;*/

            //Push notify cancel job
            if($data->status != 4){
                $body = array('email' => $data->users->email,'status' => 4,'job_name' => $data->jobs->types->name,'hotel_name' => $data->jobs->hotels->name);
                try {
                    $res = config('app.service')->post('user/notify_job_status', [
                        'form_params' => $body
                    ]);
                } catch (\GuzzleHttp\Exception\ClientException $e) {}
            }
        }
        $data->save();
        return response()->json([
            'msg' => $msg,
            'status' => $stt
        ]);
    }


    public function listUsers(Request $request){
        $users = Admin::paginate(20);

        return view('admin.list-users', compact('users'))->with('site','HotelAdmin');
    }

    public function adminEdit(Request $request, $id){
        $user = Admin::findOrFail($id);
        $hotels = Hotel::pluck('name', 'id')->toArray();

        $link_url = ['url' => route('admin.listUsers'), 'title' => 'Back', 'icon' =>'fa fa-reply'];

        return view('admin.admin-edit', compact('user','hotels','link_url'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required'
        ]);

        $data = Admin::findOrFail($id)->update([
            'hotel_id' => $request->hotel_id,
            'name' => $request->name,
            'email' => $request->email
        ]);

        Session::flash('success', 'Update success!');
        return back()->with('success', 'Update success!');
    }

    public function adminDelete($id){
        Admin::findOrFail($id)->delete();
        return redirect()->route('admin.listUsers')->with('success', 'Delete successfully!');
    }
}