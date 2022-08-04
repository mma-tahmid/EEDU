<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', 'UserController@SignUp');

Route::post('/login', 'UserController@Login');
Route::get('/view_profile', 'UserController@ViewProfile');


Route::get('/paymentlist', 'UserController@Payment_List');
Route::get('/add_payment', 'UserController@Payment_Slip');

Route::post('/add_payment', 'UserController@Payment_Slip');
Route::get('/edit_payment/{id}', 'UserController@Edit');
Route::put('/update_payment/{id}', 'UserController@Update');
Route::delete('/delete_payment/{id}', 'UserController@DestroyPayment');

//===================Student Course=================//
Route::get('/view_courses', 'UserController@StudentCourse');


//=============================Report===============//
Route::get('/add_report', 'UserController@Add_Report');

Route::post('/add_report', 'UserController@Add_Report');
Route::get('/reportlist', 'UserController@ReportList');
Route::get('/edit_report/{id}', 'UserController@Edit_Report');
Route::put('/update_report/{id}', 'UserController@Update_Report');
Route::delete('/delete_report/{id}', 'UserController@DestroyReport');






Route::get('/student_profile', 'UserController@StudentProfile');


//==========================Mail==================//

Route::post('send/email', 'UserController@mail');
//Route::get('send/email','UserController@mail'); 
//Route::post('send/email', [App\Http\Controllers\UserController::class, 'mail'])->name('email');





//=================================FeedBack=========================//

Route::get('/feedbacklist', 'UserController@Feedback_List');
Route::get('/add_feedback', 'UserController@give_feedback');
Route::post('/add_feedback', 'UserController@give_feedback');
Route::get('/edit_feedback/{id}', 'UserController@Edit_Feedback');
Route::put('/update_feedback/{id}', 'UserController@Update_Feedback');
Route::delete('/delete_feedback/{id}', 'UserController@DestroyFeedback');







Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
