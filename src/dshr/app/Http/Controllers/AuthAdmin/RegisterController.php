<?php

namespace App\Http\Controllers\AuthAdmin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Traits\ActivationTrait;
use App\Traits\CaptchaTrait;
use App\Traits\CaptureIpTrait;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use jeremykenedy\LaravelRoles\Models\Role;

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
    use RegistersUsers;

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
        $this->middleware('guest', [
            'except' => 'logout',
        ]);
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
                'username'              => 'required|min:6|max:255|unique:users',
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
        $ipAddress = new CaptureIpTrait();
        $role = Role::where('slug', '=', 'hotel')->first();

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

        return $user;
    }
}
