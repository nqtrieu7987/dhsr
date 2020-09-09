<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Models\Theme;
use App\Models\Job;
use App\Models\AllJob;
use App\Models\JobType;
use App\Traits\CaptureIpTrait;
use Illuminate\Support\Facades\Hash;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use jeremykenedy\LaravelRoles\Models\Role;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Image, File, View;

class UsersManagementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::orderBy('created_at', 'DESC');
        if($request->has('keyword') && $request->get('keyword') != ''){
            $keyword = trim($request->get('keyword'));
            $users = $users->whereRaw("userName LIKE '%$keyword%' OR emergencyContactName LIKE '%$keyword%' OR userNRIC LIKE '%$keyword%' OR contactNo LIKE '%$keyword%'");

            //$users = $users->whereRaw('MATCH(userName, email, currentSchool, address1, address2, emergencyContactName, emergencyContactNo, relationToEmergencyContact, bankName,referralCode )AGAINST("'.$request->get('keyword').'" IN NATURAL LANGUAGE MODE)');
        }
        switch ($request->type) {
            case 'all':
                break;
            case 'attire':
                $users = $users->whereRaw('(userPantsApproved = 0 OR userPantsApproved IS NULL OR userShoesApproved = 0 OR userShoesApproved IS NULL)')->whereNotNull('userPants')->whereNotNull('userShoes');
                break;
            case 'uniform':
                $users = $users->where('userConfirmed', 1);
                break;
            case 'failed':
                $users = $users->where('userConfirmed', 2);
                break;
            case 'blacklist':
                $users = $users->where('userConfirmed', 3);
                break;
            case 'ic':
                $users = $users->whereRaw('NRICFront is not null AND NRICBack is not null');
                break;
            default:
                $request->merge(['type' => 'all']);
                break;
        }
        //dd($users->toSql());
        $users = $users->paginate(30);
        
        $roles = Role::all();

        $link_url = ['url' => '/users/create', 'title' => 'Add', 'icon' =>'fa fa-plus-circle'];
        return View('usersmanagement.show-users', compact('users', 'roles','link_url'))->with('site', 'Users');
    }

    public function approvalAttire(Request $request)
    {
        $users = User::orderBy('created_at', 'DESC');
        if($request->has('keyword') && $request->get('keyword') != ''){
            $keyword = trim($request->get('keyword'));
            $users = $users->whereRaw("userName LIKE '%$keyword%' OR emergencyContactName LIKE '%$keyword%' OR userNRIC LIKE '%$keyword%' OR contactNo LIKE '%$keyword%'");
        }
        $users = $users->whereRaw('(userPantsApproved = 0 OR userPantsApproved IS NULL OR userShoesApproved = 0 OR userShoesApproved IS NULL)')->whereNotNull('userPants')->whereNotNull('userShoes');
        
        $users = $users->paginate(30);
        
        $roles = Role::all();

        $link_url = ['url' => '/users/create', 'title' => 'Add', 'icon' =>'fa fa-plus-circle'];
        return View('usersmanagement.approval-attire', compact('users', 'roles','link_url'))->with('site', 'approval-attire');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();

        $data = [
            'roles' => $roles,
        ];
        $link_url = ['url' => '/users', 'title' => 'Back', 'icon' =>'fa fa-reply'];
        return view('usersmanagement.create-user', compact('link_url'))->with($data)->with('site', 'Users');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'userName'              => 'required|max:255|unique:users',
                'first_name'            => '',
                'last_name'             => '',
                'email'                 => 'required|email|max:255|unique:users',
                'password'              => 'required|min:6|max:20|confirmed',
                'password_confirmation' => 'required|same:password',
                'role'                  => 'required',
            ],
            [
                'name.unique'         => trans('auth.userNameTaken'),
                'name.required'       => trans('auth.userNameRequired'),
                'first_name.required' => trans('auth.fNameRequired'),
                'last_name.required'  => trans('auth.lNameRequired'),
                'email.required'      => trans('auth.emailRequired'),
                'email.email'         => trans('auth.emailInvalid'),
                'password.required'   => trans('auth.passwordRequired'),
                'password.min'        => trans('auth.PasswordMin'),
                'password.max'        => trans('auth.PasswordMax'),
                'role.required'       => trans('auth.roleRequired'),
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ipAddress = new CaptureIpTrait();
        $profile = new Profile();

        $user = User::create([
            'userName'             => $request->input('userName'),
            'first_name'       => $request->input('first_name'),
            'last_name'        => $request->input('last_name'),
            'email'            => $request->input('email'),
            'password'         => bcrypt($request->input('password')),
            'token'            => str_random(64),
            'admin_ip_address' => $ipAddress->getClientIp(),
            'activated'        => 1,
        ]);

        $user->profile()->save($profile);
        $user->attachRole($request->input('role'));
        $user->save();

        return redirect('users')->with('success', trans('usersmanagement.createSuccess'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        return view('usersmanagement.show-user')->withUser($user)->with('site', 'Users');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $currentRole = $roles[1];
        foreach ($user->roles as $user_role) {
            $currentRole = $user_role;
        }
    
        $data = [
            'user'        => $user,
            'roles'       => $roles,
            'currentRole' => $currentRole,
        ];

        return view('usersmanagement.edit-user')->with($data)->with('site', 'Users');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $currentRole = $roles[1];
        foreach ($user->roles as $user_role) {
            $currentRole = $user_role;
        }

        $themes = Theme::where('status', 1)
                        ->orderBy('name', 'asc')
                        ->get();
    
        $data = [
            'user'        => $user,
            'roles'       => $roles,
            'themes'       => $themes,
            'currentRole' => $currentRole,
        ];

        $jobType = JobType::pluck('name', 'id')->toArray();
        $status = config('app.job_status');
        $color_status = config('app.color_status');

        $jobsPrev = AllJob::leftJoin('job', function($join) {
                $join->on('all_jobs.job_id', '=', 'job.id');
            })
            ->where('all_jobs.user_id', $id)
            ->where('job.start_date','<',date('Y-m-d'))
            ->paginate(20);

        $jobsOngoing = AllJob::leftJoin('job', function($join) {
                $join->on('all_jobs.job_id', '=', 'job.id');
            })
            ->where('all_jobs.user_id', $id)
            ->where('job.start_date','>=',date('Y-m-d'))
            ->paginate(20);
        
        $link_url = ['url' => '/users', 'title' => 'Back', 'icon' =>'fa fa-reply'];
        return view('usersmanagement.update-user', compact('jobsPrev','jobsOngoing','jobType','status','color_status','link_url'))->with($data)->with('site', 'Users: '.$id);
    }

    public function editUserPost(Request $request, $id){
        $user = User::find($id);
        $validator = Validator::make($request->all(), [
            'userName'     => 'required|max:255',
            //'userNRIC'     => 'required|min:6|max:200',
            'userBirthday' => 'required',
            'contactNo' => 'required',
            'address1' => 'required',
            'jobsDone' => 'integer',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user->userName = $request->input('userName');
        $user->userNRIC = $request->input('userNRIC');
        $user->userBirthday = $request->input('userBirthday');
        $user->contactNo = $request->input('contactNo');
        $user->address1 = $request->input('address1');
        $user->userGender = $request->input('userGender');
        $user->activated = $request->input('activated');
        $user->dyedHair = $request->input('dyedHair');
        $user->visibleTattoo = $request->input('visibleTattoo');
        $user->jobsDone = $request->input('jobsDone');
        $user->bankName = $request->input('bankName');
        $user->accountNo = $request->input('accountNo');
        $user->emergencyContactName = $request->input('emergencyContactName');
        $user->emergencyContactNo = $request->input('emergencyContactNo');
        $user->relationToEmergencyContact = $request->input('relationToEmergencyContact');
        $user->address2 = $request->input('address2');
        $user->studentType = $request->input('studentType');
        $user->studentStatus = $request->input('studentStatus');
        $user->currentSchool = $request->input('currentSchool');
        $user->feedback = $request->input('feedback');
        $user->isFavourite = $request->input('isFavourite');
        $user->isWarned = $request->input('isWarned');
        $user->TCC = $request->input('TCC');
        $user->isDiamond = $request->input('isDiamond');
        $user->isW = $request->input('isW');
        $user->isMO = $request->input('isMO');
        $user->isMC = $request->input('isMC');
        $user->isRWS = $request->input('isRWS');
        $user->isKempinski = $request->input('isKempinski');
        $user->isHilton = $request->input('isHilton');
        $user->isGWP = $request->input('isGWP');
        $user->save();

        Session::flash('success', 'Update success!');
        return back()->with('success', trans('usersmanagement.updateSuccess'));

    }

    public function updateComment(Request $request){
        $validator = Validator::make($request->all(), [
            'comments'     => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::find($request->id);
        $data = [date('Y-m-d H:i:s', time()) => $request->comments];
        $comments =[];
        if($user->comments){
            $comments = json_decode($user->comments, true);
            $comments[date('Y-m-d H:i:s', time())] = $request->comments;
            $user->comments = json_encode($comments);
        }else{
            $user->comments = json_encode($data);
        }
        $user->save();

        /*Session::flash('success', 'Update success!');
        return back()->with('success', trans('usersmanagement.updateSuccess'));*/
        return response()->json(['comments' => $request->comments, 'time' => date('Y-m-d H:i:s', time())], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $currentUser = Auth::user();
        $user = User::find($id);
        $emailCheck = ($request->input('email') != '') && ($request->input('email') != $user->email);
        $ipAddress = new CaptureIpTrait();

        if ($emailCheck) {
            $validator = Validator::make($request->all(), [
                'userName'     => 'required|max:255|unique:users',
                'email'    => 'email|max:255|unique:users',
                'password' => 'present|confirmed|min:6',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'userName'     => 'required|max:255|unique:users,name,'.$id,
                'password' => 'nullable|confirmed|min:6',
            ]);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->name = $request->input('userName');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');

        if ($emailCheck) {
            $user->email = $request->input('email');
        }

        if ($request->input('password') != null) {
            $user->password = bcrypt($request->input('password'));
        }

        $userRole = $request->input('role');
        if ($userRole != null) {
            $user->detachAllRoles();
            $user->attachRole($userRole);
        }

        $user->updated_ip_address = $ipAddress->getClientIp();

        switch ($userRole) {
            case 3:
                $user->activated = 0;
                break;

            default:
                $user->activated = 1;
                break;
        }

        $user->save();

        return back()->with('success', trans('usersmanagement.updateSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currentUser = Auth::user();
        $user = User::findOrFail($id);
        $ipAddress = new CaptureIpTrait();

        if ($user->id != $currentUser->id) {
            $user->deleted_ip_address = $ipAddress->getClientIp();
            $user->save();
            $user->delete();

            return redirect('users')->with('success', trans('usersmanagement.deleteSuccess'));
        }

        return back()->with('error', trans('usersmanagement.deleteSelfError'));
    }

    /**
     * Method to search the users.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('user_search_box');
        $searchRules = [
            'user_search_box' => 'required|string|max:255',
        ];
        $searchMessages = [
            'user_search_box.required' => 'Search term is required',
            'user_search_box.string'   => 'Search term has invalid characters',
            'user_search_box.max'      => 'Search term has too many characters - 255 allowed',
        ];

        $validator = Validator::make($request->all(), $searchRules, $searchMessages);

        if ($validator->fails()) {
            return response()->json([
                json_encode($validator),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // Fulltext search
        /*ALTER TABLE ds_users ADD FULLTEXT(userName, email, currentSchool, address1, address2, emergencyContactName, emergencyContactNo, relationToEmergencyContact, bankName,referralCode)

SELECT * FROM ds_users  WHERE MATCH(userName, email, currentSchool, address1, address2, emergencyContactName, emergencyContactNo, relationToEmergencyContact, bankName,referralCode )AGAINST('Tee1' IN NATURAL LANGUAGE MODE)*/

        /*$results = User::where('id', 'like', $searchTerm.'%')
                        ->orWhere('userName', 'like', $searchTerm.'%')
                        ->orWhere('email', 'like', $searchTerm.'%')->get();*/
        $results = User::whereRaw('MATCH(userName, email, currentSchool, address1, address2, emergencyContactName, emergencyContactNo, relationToEmergencyContact, bankName,referralCode )AGAINST("'.$searchTerm.'" IN NATURAL LANGUAGE MODE)')->get();
        // Attach roles to results
        foreach ($results as $result) {
            $roles = [
                'roles' => $result->roles,
            ];
            $result->push($roles);
        }

        return response()->json([
            json_encode($results),
        ], Response::HTTP_OK);
    }

    public function upload(Request $request)
    {
        if (Input::hasFile('file')) {
            $currentUser = User::find($request->id);
            $avatar = Input::file('file');
            $filename = $avatar->getClientOriginalName();
            $save_path = public_path().'/uploads/users/id/'.$currentUser->id.'/';
            $path = $save_path.$filename;
            $public_path = '/uploads/users/id/'.$currentUser->id.'/'.$filename;

            // Make the user a folder and set permissions
            File::makeDirectory($save_path, $mode = 0755, true, true);

            // Save the file to the server
            //Image::make($avatar)->resize(300, 300)->save($path);
            Image::make($avatar)->save($path);

            // Save the public image path
            switch ($request->type) {
                case 'NRICBack':
                    $currentUser->NRICBack = $public_path;
                    break;
                case 'NRICFront':
                    $currentUser->NRICFront = $public_path;
                    break;
                case 'userPants':
                    $currentUser->userPants = $public_path;
                    break;
                case 'userShoes':
                    $currentUser->userShoes = $public_path;
                    break;
                case 'studentCardFront':
                    $currentUser->studentCardFront = $public_path;
                    break;
                case 'studentCardBack':
                    $currentUser->studentCardBack = $public_path;
                    break;
                case 'workPassPhoto':
                    $currentUser->workPassPhoto = $public_path;
                    break;
                default:
                    # code...
                    break;
            }
            $currentUser->save();

            return response()->json(['path' => $path], 200);
        } else {
            return response()->json(false, 200);
        }
    }

    public function approvedPantShose(Request $request){
        $userPantsApproved = $request->userPantsApproved;
        $userShoesApproved = $request->userShoesApproved;

        $user = User::findOrFail($request->id);
        $user->userPantsApproved = $userPantsApproved;
        $user->userShoesApproved = $userShoesApproved;
        $user->save();

        // Session::flash('messageSS', 'Update success!');
        // return redirect()->back()->with('success', trans('profile.updateAccountSuccess'));
        return 1;
    }

    public function approvedByType(Request $request){
        $user = User::find($request->id);
        if($request->type == 1){
            $user->userPantsApproved = $request->checked;
        }elseif($request->type == 2){
            $user->userShoesApproved = $request->checked;
        }elseif($request->type == 3){
            $user->studentStatus = $request->checked;
        }
        $user->save();

        return response('success');
    }

    public function resetpass($id)
    {
        $user = User::findOrFail($id);
        $birthday = str_replace('/', '', $user->userBirthday);
        $birthday = str_replace('-', '', $birthday);
        $password = $user->userNRIC.$birthday;
        if($user){
            $user->password = Hash::make($password);
            $user->save();

            return redirect('users')->with('success', 'Reset password default: '.$password.' email: '.$user->email);
        }

        return back()->with('error', trans('usersmanagement.deleteSelfError'));
    }
}
