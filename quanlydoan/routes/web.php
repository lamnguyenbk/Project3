<?php
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\User;
use App\GroupScheduel;
use App\ContentGroupScheduel;
use App\ContentSubjectScheduel;
use App\SubjectScheduel;
use App\Group;
use App\Student;
use App\GroupStudent;
use App\EvalutionCriteria;
use Carbon\Carbon;

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
})->name('/');

Route::group(['prefix' => 'admin'], function() {
  
	Route::get('password/{username}','ProfilesController@showpassword');
	Route::post('password/{username}/edit','ProfilesController@updateUserPassword');
	Route::get('/', function() {
		return Redirect::to('admin/project/listProject/1');
	});
});

// Route for teacher
Route::group(['prefix' => 'teacher'], function() {

	Route::get('/', function() {
		return view('teacher.listProject');
	});
	
	Route::get('profile/{username}','ProfilesController@showProfile');
    Route::post('profile/{username}/edit','ProfilesController@updateUserAccount');
	Route::get('password/{username}','ProfilesController@showpassword');
	Route::post('password/{username}/edit','ProfilesController@updateUserPassword');
	
	// Route::get('/search', 'ProjectController@getListProject');
	Route::group(['prefix' => 'project'], function() {
		Route::get('/', 'ProjectController@getListProject');
		Route::get('createGroup', 'ProjectController@getCreateProject');
		Route::post('createGroup', 'ProjectController@createProject');
		
		Route::get('/{id_group}', 'ProjectController@getProjectDetail');

		// Route for scheduel
		Route::get('/{id_group}/scheduel', 'ScheduelController@getScheduel');
		Route::post('/{id_group}/scheduel', 'ScheduelController@addScheduelContent');
		Route::post('/{id_group}/scheduel/delete', 'ScheduelController@delScheduelContent');
		Route::get('/{id_group}/scheduel/update/{id_content}', 'ScheduelController@getUpdateScheduelContent');
		Route::post('/{id_group}/scheduel/update/{id_content}', 'ScheduelController@postUpdateScheduelContent');
	
		// Route for evaluation
		Route::get('/{id_group}/evaluation', 'EvaluationController@getEvaluation');
		Route::post('/{id_group}/evaluation', 'EvaluationController@addEvaluation');
		Route::post('/{id_group}/evaluation/delete', 'EvaluationController@delEvaluation');
		Route::get('/{id_group}/evaluation/update/{id}', 'EvaluationController@getUpdateEvaluation');
		Route::post('/{id_group}/evaluation/update/{id}', 'EvaluationController@postUpdateEvaluation');

		// Route for listStudent
		Route::get('/{id_group}/listStudent', 'StudentOfGroupController@getListStudent');
		Route::post('/{id_group}/listStudent', 'StudentOfGroupController@addStudentOfGroup');
		Route::post('/{id_group}/listStudent/delete', 'StudentOfGroupController@delStudentOfGroup');
        
        // Router for file
         Route::get('/{id_group}/document', 'DocumentController@getDocument');
        Route::post( '/{id_group}/document','DocumentController@uploadFile');
        Route::get( '/{id_group}/document/{id_document}/download/','DocumentController@downloadFile');
        Route::get( '/{id_group}/document/{id_document}/delete/','DocumentController@deleteFile');
		Route::get('/{id_group}/document/{id_document}/evaluate/{point}', 'DocumentController@evaluateFile');
	});
});

// /Route for student
Route::group(['prefix' => 'student'], function() {

	Route::get('/', function() {
		return view('student.listProject');
	});

    Route::get('profile/{username}','ProfilesController@showProfile');
    Route::post('profile/{username}/edit','ProfilesController@updateUserAccount');
	Route::get('password/{username}','ProfilesController@showpassword');
	Route::post('password/{username}/edit','ProfilesController@updateUserPassword');

	
	// Route::get('/search', 'ProjectController@getListProject');
	Route::group(['prefix' => 'project'], function() {
		Route::get('/', 'ProjectController@getListProject');
		Route::get('/{id_group}', 'ProjectController@getProjectDetail');

		// Route for scheduel
		Route::get('/{id_group}/scheduel', 'ScheduelController@getScheduel');
	
		// Route for evaluation
		Route::get('/{id_group}/evaluation', 'EvaluationController@getEvaluation');

		// Route for listStudent
		Route::get('/{id_group}/listStudent', 'StudentOfGroupController@getListStudent');
		Route::post('/{id_group}/listStudent', 'StudentOfGroupController@addStudentOfGroup');
       
        Route::get('/{id_group}/document', 'DocumentController@getDocument');
        Route::post( '/{id_group}/document','DocumentController@uploadFile');
        Route::get( '/{id_group}/document/{id_document}/download/','DocumentController@downloadFile');
        Route::get( '/{id_group}/document/{id_document}/delete/','DocumentController@deleteFile');



	});
});


// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');



Route::group(['prefix'=>'admin'], function(){
	Route::group(['prefix'=>'project'], function(){
		Route::get('listProject/{id_subject}', 'ProjectController@getAdListProject');
		Route::get('listProject/{id_subject}/search', 'ProjectController@getAdSearchListProject');

	});

	Route::group(['prefix'=>'scheduel'], function(){
		// chua co
		Route::get('show/{id_subject}', 'AdScheduelController@getAdScheduel');
		Route::post('addScheduel/{id_subject}', 'AdScheduelController@postAdAddScheduel');
		Route::get('show/{id_subject}/search', 'AdScheduelController@postSearch');
		Route::get('updateScheduel/{id_subject}/{id_subject_scheduel}/{id_content_sub_scheduel}', 'AdScheduelController@getUpdateScheduelContent');
		Route::post('updateScheduel/{id_subject}/{id_subject_scheduel}/{id_content_sub_scheduel}', 'AdScheduelController@postUpdateScheduelContent');
		Route::get('deleteScheduel/{id_subject}/{id_subject_scheduel}/{id_content_sub_scheduel}', 'AdScheduelController@getDeleteScheduel');
	});

	Route::group(['prefix'=>'user'], function(){
		// danh sach user
		Route::get('listAdmin', 'AdUserController@getListAdmin');
		Route::get('listAdmin/search', 'AdUserController@postSearch');
		Route::get('listAdmin/searchPagination', 'AdUserController@getSearchPagination');
		Route::get('listTeacher', 'AdTeacherController@getListTeacher');
		Route::get('listTeacher/search', 'AdTeacherController@postSearch');
		Route::get('listTeacher/searchPagination', 'AdTeacherController@getSearchPagination');
		Route::get('listStudent', 'AdStudentController@getListStudent');
		Route::get('listStudent/search', 'AdStudentController@postSearch');
		Route::get('listStudent/searchPagination', 'AdStudentController@getSearchPagination');

		//sua thong tin user
		Route::get('updateAdmin/{username}', 'AdUserController@getUpdateAdmin');
		Route::post('updateAdmin/{username}', 'AdUserController@postUpdateAdmin');
		Route::get('updateTeacher/{id_teacher}', 'AdTeacherController@getUpdateTeacher');
		Route::post('updateTeacher/{id_teacher}', 'AdTeacherController@postUpdateTeacher');
		Route::get('updateStudent/{id_student}', 'AdStudentController@getUpdateStudent');
		Route::post('updateStudent/{id_student}', 'AdStudentController@postUpdateStudent');

		// them user
		// Route::get('addAdmin', 'AdUserController@getAddAdmin');
		//dang can sua cho search de xuong duoi form, dung ajax de search
		Route::post('addAdmin', 'AdUserController@postAddAdmin');
		Route::post('addTeacher', 'AdTeacherController@postAddTeacher');
		Route::post('addStudent', 'AdStudentController@postAddStudent');

		//tim kiem user
		Route::get('searchAdmin', 'AdUserController@getSearchAdmin');
		Route::get('searchTeacher', 'AdTeacherController@getSearchTeacher');
		Route::get('searchStudent', 'AdStudentController@getSearchStudent');

		//xoa user
		Route::get('deleteAdmin/{username}', 'AdUserController@getDeleteAdmin');
		Route::get('deleteTeacher/{id_teacher}', 'AdTeacherController@getDeleteTeacher');
		Route::get('deleteStudent/{id_student}', 'AdStudentController@getDeleteStudent');

	});
});
