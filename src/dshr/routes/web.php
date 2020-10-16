<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| Middleware options can be located in `app/Http/Kernel.php`
|
*/

// Homepage Route
Route::group(['middleware' => ['web', 'checkblocked']], function () {
    Route::get('/', 'UserController@index')->name('public.home');
});

// Authentication Routes
Auth::routes();

// Public Routes
Route::group(['middleware' => ['web', 'activity', 'checkblocked']], function () {

    // Activation Routes
    Route::get('/activate', ['as' => 'activate', 'uses' => 'Auth\ActivateController@initial']);

    Route::get('/activate/{token}', ['as' => 'authenticated.activate', 'uses' => 'Auth\ActivateController@activate']);
    Route::get('/activation', ['as' => 'authenticated.activation-resend', 'uses' => 'Auth\ActivateController@resend']);
    Route::get('/exceeded', ['as' => 'exceeded', 'uses' => 'Auth\ActivateController@exceeded']);

    // Socialite Register Routes
    Route::get('/social/redirect/{provider}', ['as' => 'social.redirect', 'uses' => 'Auth\SocialController@getSocialRedirect']);
    Route::get('/social/handle/{provider}', ['as' => 'social.handle', 'uses' => 'Auth\SocialController@getSocialHandle']);

    // Route to for user to reactivate their user deleted account.
    Route::get('/re-activate/{token}', ['as' => 'user.reactivate', 'uses' => 'RestoreUserController@userReActivate']);
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'activity', 'checkblocked']], function () {

    // Activation Routes
    Route::get('/activation-required', ['uses' => 'Auth\ActivateController@activationRequired'])->name('activation-required');
    Route::get('/logout', ['uses' => 'Auth\LoginController@logout'])->name('logout');
});

//  Homepage Route - Redirect based on user role is in controller.
Route::get('/home', ['as' => 'public.home',   'uses' => 'UserController@index']);

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'check_roles']], function () {


    // Show users profile - viewable by other users.
    Route::get('profile/{username}', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@show',
    ]);

    Route::resource('banner', 'BannerController');
    Route::resource('article', 'ArticleController');
    Route::resource('page', 'PageController');
    Route::resource('hotel', 'HotelController');
    Route::resource('bank', 'BankController');
    Route::resource('view-type', 'ViewTypeController');
    Route::resource('job-type', 'JobTypeController');
    Route::resource('job', 'JobController');
    Route::get('report/job', ['as' => 'report.job', 'uses' => 'JobController@reportJob']);
    Route::get('job-create-multi', ['as' => 'job.createMulti', 'uses' => 'JobController@createMulti']);
    Route::post('job-create-multi', ['as' => 'job.createMultiPost', 'uses' => 'JobController@createMultiPost']);

    Route::get('job-export-excel', ['as' => 'job.export-excel', 'uses' => 'JobController@exportExcel']);
    Route::get('all-jobs', ['as' => 'job.all-jobs', 'uses' => 'JobController@AllJobs']);
    Route::delete('job/approved/{id}', ['as' => 'job.approved', 'uses' => 'JobController@approved']);
    Route::get('job/in-out/{id}', ['as' => 'job.inOut', 'uses' => 'JobController@inOut']);
    Route::post('job/in-out/{id}', ['as' => 'job.inOut', 'uses' => 'JobController@inOutPost']);
    Route::post('change/status', ['as' => 'change.status', 'uses' => 'HomeController@changeStatus']);
    Route::post('change/job_status', ['as' => 'changeJobStatus', 'uses' => 'HomeController@changeJobStatus']);
    Route::post('change/statusUser', ['as' => 'change.statusUser', 'uses' => 'HomeController@changeStatusUser']);

    Route::get('change/update_status', ['as' => 'changeUpdateStatus', 'uses' => 'HomeController@changeUpdateStatus']);

    Route::get('clocking', ['as' => 'clocking', 'uses' => 'HomeController@clocking']);
    Route::get('chartjs.html', ['as' => 'chartjs', 'uses' => 'HomeController@chartjs']);

    Route::get('pagination/fetch_data', ['as' => 'pagination.fetch_data', 'uses' => 'AjaxController@fetch_data']);
    Route::get('pagination/fetch_ongoing', ['as' => 'pagination.fetch_ongoing', 'uses' => 'AjaxController@fetch_ongoing']);


    Route::get('/list-users', 'HomeController@listUsers')->name('admin.listUsers');
    Route::get('/admin-edit/{id}/edit', 'HomeController@adminEdit')->name('admin.adminEdit');
    Route::put('/admin-edit/{id}/edit', 'HomeController@update')->name('admin.update');
    Route::delete('/admin-delete/{id}', 'HomeController@adminDelete')->name('admin.adminDelete');
});

// Registered, activated, and is current user routes.
Route::group(['middleware' => ['auth', 'check_roles', 'currentUser', 'activity', 'twostep', 'checkblocked']], function () {

    // User Profile and Account Routes
    Route::resource(
        'profile',
        'ProfilesController', [
            'only' => [
                'show',
                'edit',
                'update',
                'create',
            ],
        ]
    );
    Route::put('profile/{username}/updateUserAccount', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@updateUserAccount',
    ]);
    Route::put('profile/{username}/updateUserPassword', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@updateUserPassword',
    ]);
    Route::delete('profile/{username}/deleteUserAccount', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@deleteUserAccount',
    ]);

    // Route to show user avatar
    Route::get('images/profile/{id}/avatar/{image}', [
        'uses' => 'ProfilesController@userProfileAvatar',
    ]);

    // Route to upload user avatar.
    Route::post('avatar/upload', ['as' => 'avatar.upload', 'uses' => 'ProfilesController@upload']);
    Route::post('upload/image', ['as' => 'upload.image', 'uses' => 'UsersManagementController@upload']);
    Route::post('approved/pant-shose', ['as' => 'user.approved-pant-shose', 'uses' => 'UsersManagementController@approvedPantShose']);
    Route::post('user/updateComment', ['as' => 'user.updateComment', 'uses' => 'UsersManagementController@updateComment']);
    Route::post('user/approvedByType', ['as' => 'user.approvedByType', 'uses' => 'UsersManagementController@approvedByType']);
});

// Registered, activated, and is admin routes.
Route::group(['middleware' => ['auth', 'check_roles', 'role:admin', 'activity', 'twostep', 'checkblocked']], function () {
    Route::resource('/users/deleted', 'SoftDeletesController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('users', 'UsersManagementController', [
        'names' => [
            'index'   => 'users',
            'destroy' => 'user.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::get('users/edit/{id}', ['as' => 'user.edit', 'uses' => 'UsersManagementController@editUser']);
    Route::post('users/edit/{id}', ['as' => 'user.edit', 'uses' => 'UsersManagementController@editUserPost']);
    Route::get('users/resetpass/{id}', ['as' => 'user.resetpass', 'uses' => 'UsersManagementController@resetpass']);
    Route::get('approval-attire', ['as' => 'user.approvalAttire', 'uses' => 'UsersManagementController@approvalAttire']);


    Route::post('search-users', 'UsersManagementController@search')->name('search-users');

    Route::resource('themes', 'ThemesManagementController', [
        'names' => [
            'index'   => 'themes',
            'destroy' => 'themes.destroy',
        ],
    ]);

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('routes', 'AdminDetailsController@listRoutes');
    Route::get('active-users', 'AdminDetailsController@activeUsers');
});

Route::post('job/in-out-aj', ['as' => 'job.inOutAJ', 'uses' => 'AjaxController@inOutPost']);

Route::redirect('/php', '/phpinfo', 301);

Route::get('map_status_data', ['as' => 'user.mapStatusData', 'uses' => 'UsersManagementController@mapStatusData']);


Route::prefix('admin')->group(function() {
    Route::get('/', 'AdminController@index')->name('admin.home');
    Route::get('/login', 'AuthAdmin\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'AuthAdmin\LoginController@login')->name('admin.login.submit');
    Route::post('/logout', 'AuthAdmin\LoginController@logout')->name('admin.logout');
    Route::get('/password/reset', 'AuthAdmin\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('/password/email', 'AuthAdmin\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('/password/reset/{token}', 'AuthAdmin\ResetPasswordController@showResetForm')->name('admin.password.reset');
    Route::post('/password/reset', 'AuthAdmin\ResetPasswordController@reset');
    Route::get('/register', 'AuthAdmin\RegisterController@showRegistrationForm')->name('admin.register');
    Route::post('/register', 'AuthAdmin\RegisterController@register')->name('admin.register.submit');

    Route::get('clocking', ['as' => 'admin.clocking', 'uses' => 'AdminController@clocking']);

    Route::get('report/job', ['as' => 'admin.report.job', 'uses' => 'AdminController@reportJob']);

    Route::post('job/in-out-aj', ['as' => 'admin.job.inOutAJ', 'uses' => 'AdminController@inOutPost']);

    Route::get('change/update_status', ['as' => 'admin.changeUpdateStatus', 'uses' => 'AdminController@changeUpdateStatus']);

    //Route::resource('job', 'JobAdminController');
    Route::get('/job', ['as' => 'admin.job.index', 'uses' => 'JobAdminController@index']);
    Route::get('/job/create', ['as' => 'admin.job.create', 'uses' => 'JobAdminController@create']);
    Route::post('/job/store', ['as' => 'admin.job.store', 'uses' => 'JobAdminController@store']);
    Route::get('/job/{id}/edit', ['as' => 'admin.job.edit', 'uses' => 'JobAdminController@edit']);
    Route::put('/job/{id}/update', ['as' => 'admin.job.update', 'uses' => 'JobAdminController@update']);
    Route::get('job/in-out/{id}', ['as' => 'admin.job.inOut', 'uses' => 'JobAdminController@inOut']);
    Route::post('job/in-out/{id}', ['as' => 'admin.job.inOut', 'uses' => 'JobAdminController@inOutPost']);
});