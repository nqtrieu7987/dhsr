<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Nicolaslopezj\Searchable\SearchableTrait;

class User extends Authenticatable
{
    use HasRoleAndPermission;
    use Notifiable;
    use SoftDeletes;
    use SearchableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         * userName, email, currentSchool, address1, address2, emergencyContactName, emergencyContactNo, relationToEmergencyContact, bankName,referralCode
         * @var array
         */
        'columns' => [
            'users.userName' => 10,
            'users.emergencyContactName' => 10,
            'users.currentSchool' => 9,
            'users.address1' => 8,
            'users.address2' => 7,
            'users.email' => 6,
            'users.emergencyContactNo' => 5,
            'users.relationToEmergencyContact' => 4,
            'users.bankName' => 3,
            'users.referralCode' => 2,
        ]
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userName',
        'contactNo',
        'avatar',
        'email',
        'password',
        'activated',
        'token',
        'signup_ip_address',
        'signup_confirmation_ip_address',
        'signup_sm_ip_address',
        'admin_ip_address',
        'updated_ip_address',
        'deleted_ip_address',
        'timeStamp', 'visitTimeStamp', 'studentType', 'userNRIC', 'userBirthday', 'userGender', 'studentStatus', 'currentSchool', 'address1', 'address2', 'emergencyContactNo', 'emergencyContactName', 'relationToEmergencyContact', 'bankName', 'asWaiter', 'dyedHair', 'visibleTattoo', 'workPassPhoto', 'studentCardFront', 'studentCardBack', 'NRICFront', 'NRICBack', 'jobsDone', 'userConfirmed', 'userPants', 'userShoes', 'userShoesApproved', 'userPantsApproved', 'isFavourite', 'isWarned', 'verifyCode', 'user_id', 'chatURL', '__v', 'comments', 'feedback', 'TCC', 'isDiamond', 'referralCode', 'expiryDateOfStudentPass', 'is_active', 'isW', 'isMO', 'isMC', 'isRWS', 'isKempinski', 'isHilton', 'isGWP','accountNo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'activated',
        'token',
    ];

    protected $dates = [
        'deleted_at',
    ];

    /**
     * Build Social Relationships.
     *
     * @var array
     */
    public function social()
    {
        return $this->hasMany('App\Models\Social');
    }

    /**
     * User Profile Relationships.
     *
     * @var array
     */
    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }

    // User Profile Setup - SHould move these to a trait or interface...

    public function profiles()
    {
        return $this->belongsToMany('App\Models\Profile')->withTimestamps();
    }

    public function hasProfile($name)
    {
        foreach ($this->profiles as $profile) {
            if ($profile->name == $name) {
                return true;
            }
        }

        return false;
    }

    public function assignProfile($profile)
    {
        return $this->profiles()->attach($profile);
    }

    public function removeProfile($profile)
    {
        return $this->profiles()->detach($profile);
    }
}
