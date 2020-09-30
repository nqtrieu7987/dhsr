<?php

namespace App\Http\Controllers\AuthAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Traits\ActivationTrait;
use App\Traits\CaptchaTrait;
use App\Traits\CaptureIpTrait;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use jeremykenedy\LaravelRoles\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use ActivationTrait;
    use CaptchaTrait;
    //use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/activate';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except(['logout']);
    }

    public function showRegistrationForm()
    {
        return view('authAdmin.register');
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['captcha'] = $this->captchaCheck();

        if (!config('settings.reCaptchStatus')) {
            $data['captcha'] = true;
        }

        return Validator::make($data,
            [
                'username'              => 'required|min:6|max:255|unique:adminusers',
                'password'              => 'required|min:6|max:50',
                //'email'                 => 'required|email|max:255|unique:users',
            ],
            [
                'username.unique'               => trans('auth.userNameTaken'),
                'username.required'             => trans('auth.userNameRequired'),
                'password.required'             => trans('auth.passwordRequired'),
                'password.min'                  => trans('auth.PasswordMin'),
                'password.max'                  => trans('auth.PasswordMax'),
                /*'email.required'                => trans('auth.emailRequired'),
                'email.email'                   => trans('auth.emailInvalid'),*/
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        $user = Admin::create([
                'name'          => $data['username'],
                'username'          => $data['username'],
                'password'          => Hash::make($data['password']),
                'adminType'          => 'hotel',
                //'email'             => $data['email'],
                //'token'             => str_random(64),
                /*'signup_ip_address' => $ipAddress->getClientIp(),
                'activated'         => !config('settings.activation'),*/
            ]);

            /*$user->attachRole($role);
            $this->initiateEmailActivation($user);*/
            $credential = [
                'username' => $data['username'],
                'password' => $data['password']
            ];

            // Attempt to log the user in
            if (Auth::guard('admin')->attempt($credential, null)){
                // If login succesful, then redirect to their intended location
                return redirect()->route('admin.home');
            }
        //return $user;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));
        /*$this->guard()->login($user);
        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());*/

        $credential = [
            'username' => $request->username,
            'password' => $request->password
        ];
        if (Auth::guard('admin')->attempt($credential, $request->member)){
            // If login succesful, then redirect to their intended location
            return redirect()->route('admin.home');
        }

    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }

    public function redirectPath()
    {
        return redirect()->route('admin.home');
    }
}
