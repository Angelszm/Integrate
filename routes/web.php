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
*/

Route::get('/', function () {
    return view('first');
});
Route::get('/signin', function () {
    return view('signin');
});
Route::get('/signup', function () {
    return view('signup');
});
Route::get('/menu', function () {
    return view('mainmenu');
});
Route::get('/setting', function () {
    return view('new');
});
//Auth::routes();
//Route::get('/home','HomeController@index')->name('home');
//Route::get('/verify-user/{code}', 'Auth\RegisterController@activationForm');

Route::get('/verify-admin/{code}', function($code) {
    return view('admin.admin-auth.admin-activate', ['code'=>$code]);
});

Route::post('/verify-admin/{code}', 'Admin\AdminAuth\AdminActivationController@activateAdmin')->name('activate.admin');

Route::group(['namespace'=>'Vehicle', 'prefix'=>'vehicles', 'middleware'=>'admin'], function() {
    Route::get('/', 'VehicleController@index');
    Route::get('create', 'VehicleController@create');
    Route::get('{vehicle}/edit', 'VehicleController@edit');
    Route::post('/', 'VehicleController@store');
    Route::post('{vehicle}', 'VehicleController@update');
    Route::delete('{vehicle}', 'VehicleController@destroy');
});

Route::group(['middleware'=>'driver', 'namespace'=>'Driver'], function () {
    Route::get('/driver-edit', 'DriverController@edit');
    Route::get('/driver-profile', 'DriverController@index');
    Route::post('/driver-profile/', 'DriverController@update');
});

Route::group(['namespace'=>'Driver\DriverAuth'], function () {
    Route::get('driver-login', 'DriverLoginController@showLoginForm')->name('driver.login');
    Route::post('driver-login', 'DriverLoginController@login')->name('driver.login.save');
    Route::get('driver-password/reset', 'DriverForgotPasswordController@showLinkResetForm')->name('driver-password.request');
    Route::post('driver-password/email', 'DriverForgotPasswordController@sendResetLinkEmail')->name('driver-password.email');
    Route::post('driver-password/reset', 'DriverResetPasswordController@reset');
    Route::get('driver-password/reset/{token}', 'DriverResetPasswordController@showResetForm')->name('driver-password.reset');
});

Route::group(['namespace'=>'Driver', 'prefix'=>'drivers', 'middleware'=>'admin'], function () {
    Route::get('create', 'DriverAuth\DriverRegisterController@showRegistrationForm');
    Route::post('/', 'DriverAuth\DriverRegisterController@store');
});

Route::get('/verify-driver/{code}', function($code) {
    return view('driver.driver-auth.driver-activate', ['code'=>$code]);
})->name('activate.driver');

Route::post('/verify-driver/{code}', 'Driver\DriverAuth\DriverActivationController@activateDriver');

Route::group(['namespace'=>'Admin\AdminAuth'], function () {
    Route::get('register', 'AdminRegisterController@showRegistrationForm')->name('admin.register');
    Route::post('register', 'AdminRegisterController@register')->name('admin.register.save');
    Route::get('login', 'AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'AdminLoginController@login')->name('admin.login.save');
    //Route::get('logout', 'AdminLoginController@logout')->name('admin.logout');
	Route::post('logout', 'AdminLoginController@logout')->name('admin.logout');
    Route::get('password/reset', 'AdminForgotPasswordController@showLinkResetForm')->name('password.request');
    Route::post('password/email', 'AdminForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('password/reset', 'AdminResetPasswordController@reset');
    Route::get('password/reset/{token}', 'AdminResetPasswordController@showResetForm')->name('password.reset');
});

Route::group(['namespace'=>'Admin', 'middleware'=>'admin'], function () {
    Route::get('/drivers','AdminDriverController@index');
    Route::delete('/drivers/{driver}', 'AdminDriverController@destroy');
});

Route::view('/admin/home', 'admin.admin-home')->middleware('admin')->name('admin.home');

Route::view('/driver/home', 'driver.driver-home')->middleware('driver')->name('driver.home');