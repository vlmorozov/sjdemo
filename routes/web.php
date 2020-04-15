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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/register/school', 'Registrations\SchoolController@showRegistrationForm')->name('register.school');
Route::post('/register/school', 'Registrations\SchoolController@register');

Route::group(['middleware' => ['auth']], function() {
    Route::get('/debug', 'DebugController@index')->name('debug');

    Route::get('/classes/{classId}/add-pupil', 'SchoolClassController@bindPupil')->name('classes.bindPupil');
    Route::post('/classes/{classId}/store-pupil', 'SchoolClassController@storePupil')->name('classes.storePupil');
    Route::delete('/classes/{classId}/delete-pupil/{id}', 'SchoolClassController@destroyPupil')->name('classes.destroyPupil');



    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
    Route::resource('permissions','PermissionController');
    Route::resource('schools','SchoolController');
    Route::resource('classes', 'SchoolClassController');
    Route::resource('pupils', 'PupilController');
    Route::resource('subjects', 'SubjectsController');
    Route::resource('cabinets', 'CabinetsController');
    Route::resource('teachers', 'TeachersController');
    Route::resource('schedule', 'ScheduleController');
});
