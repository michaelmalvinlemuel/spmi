<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use Illuminate\Http\Response;


//header('Access-Control-Allow-Origin: *');

//header('Access-Control-Allow-Credentials: true');




Route::get('/', ['as' => 'main', function () {
	if (App::environment('local')) {
		return redirect()->to('http://localhost:3000');
	} else if (App::environment('production')) {
		return redirect()->to('http://spmi.umn.ac.id');
	}
	
}]);

Route::get('authenticate', 'AuthenticateController@index'); 		//for get current user logged in
Route::post('authenticate', 'AuthenticateController@authenticate'); //for login
Route::post('register', 'RegisterController@register'); //for register
Route::get('register/confirm/{token}', 'RegisterController@confirm')
	->where('token', '(.*)'); //for register
Route::get('register/resend', 'RegisterController@resend');

//Route::get('/user', 'UserController@check');
Route::post('/user/login', 'UserController@login');
Route::post('user/validating/nik', 'UserController@validatingNik');
Route::post('user/validating/email', 'UserController@validatingEmail');
Route::get('/user/logout', 'UserController@logout');



Route::group(['middleware'=> ['jwt.auth']], function(){
	
    
    
    
	Route::get('user', 'UserController@index');
	Route::get('user/jobs', 'UserController@jobs'); //for generate subordinate hierarchy
	Route::get('user/lite/{id}', 'UserController@show');
    Route::post('user/reset', 'UserController@reset');
    
	Route::get('job/users/{id}', 'JobController@users');
	Route::get('job/subs/{id}', 'JobController@subs');
	Route::get('job/lite/{id}', 'JobController@show');
    
	Route::get('standard/all', 'StandardController@all');
    
	Route::get('groupJob/users', 'GroupJobController@users');
	Route::get('groupJob/jobs/{id}', 'GroupJobController@jobs');

	Route::get('work/lite/{id}', 'WorkController@show');
	Route::get('work/users/{id}', 'WorkController@users');
	
    
    
	Route::get('task/retrive/{userId}/{jobId}/{display}/{progress}/{complete}/{overdue}', 'TaskController@retrive');	//for retrive task completness by their subordinate
	Route::resource('task', 'TaskController',
		['except' => [ 'create', 'edit', 'delete']]); //task is open for users
	
	Route::get('template/project/paginate/{display}', 'ProjectTemplateController@paginate');
	Route::resource('template/project', 'ProjectTemplateController', 
		['except' => ['create', 'edit']]);
	
    //Route::get('project/node/{nodeId}/upload', 'ProjectNodeUploadController@index');
    Route::post('project/node/upload', 'ProjectNodeUploadController@store');
    Route::get('project/node/upload/{id}', 'ProjectNodeUploadController@show');
    Route::patch('project/node/upload/{id}', 'ProjectNodeUploadController@update');
    Route::delete('project/node/upload/{id}', 'ProjectNodeUploadController@destroy');
    
	Route::post('project/node/delegate', 'ProjectNodeController@delegate'); //for delegation user by project leader
	Route::get('project/node/lock/{id}/{lockStatus}', 'ProjectNodeController@lock'); //lock node by project manager to ready for grading if partial assessment method
	Route::get('project/node/assess/{nodeId}', 'ProjectNodeController@assess'); //retrive assessment history for some node
	Route::post('project/node/score', 'ProjectNodeController@score'); //scoring project by admin or assessor
	
	Route::get('project/last/{id}', 'ProjectController@showLast');  //shpw project with last uploaded form	
	Route::get('project/form/{id}', 'ProjectController@form'); //custom selection for uploaded project document
	Route::get('project/leader/{id}', 'ProjectController@leader'); //retrive the leader of the project
	
	Route::patch('project/mark/{id}', 'ProjectController@mark'); //admin mark project as completed or terminated
	Route::post('project/upload', 'ProjectController@upload'); //users upload project form
	Route::get('project/lock/{id}/{lockStatus}', 'ProjectController@lock'); //lock all project form
	Route::post('project/validating/name', 'ProjectController@validatingName'); //validate project name for prevent duplication
	Route::get('project/user/{dislplay}/{initiation}/{preparation}/{progress}/{grading}/{conplete}/{terminated}', 'ProjectController@user'); 	//show project that involved by user	
	
    
	Route::get('project/{dislplay}/{initiation}/{preparation}/{progress}/{grading}/{conplete}/{terminated}', 'ProjectController@index');//retrive project by filter
	Route::resource('project', 'ProjectController',
		['except' => ['index', 'create', 'edit']]); //PROJECT IS DOUBTFULL CAN BE USERS OR ADMIN

	Route::group(['middleware' => ['role']], function() {

		/**
		 * ORGANIZATION REST API ROUTE
		*/

		Route::post('university/validating', 'UniversityController@validating');
		Route::resource('university', 'UniversityController',
			['except' => ['create', 'edit']]);

		Route::get('department/university/{id}', 'DepartmentController@university');
		Route::post('department/validating', 'DepartmentController@validating');
		Route::resource('department', 'DepartmentController',
			['except' => ['create', 'edit']]);
        
		Route::get('job/university/{id}', 'JobController@university');
		Route::get('job/department/{id}', 'JobController@department');
		Route::post('job/validating', 'JobController@validating');
		Route::resource('job', 'JobController',
			['except' => ['create', 'edit']]);
        
        
		/**
		 * SPMI DOCUMENT REST API ROUTE
		 */
         
         
		Route::post('standard/validating', 'StandardController@validating');
		Route::resource('standard', 'StandardController', 
			['except' => ['create', 'edit']]);

		Route::get('standardDocument/standard/{id}', 'StandardDocumentController@standard');

		Route::post('standardDocument/validating/no', 'StandardDocumentController@validatingNo');
		Route::post('standardDocument/validating/description', 'StandardDocumentController@validatingDescription');
		Route::resource('standardDocument', 'StandardDocumentController',
			['except'=> ['create', 'edit']]);

		Route::get('guide/standardDocument/{id}', 'GuideController@standardDocument');

		Route::post('guide/validating/no', 'GuideController@validatingNo');
		Route::post('guide/validating/description', 'GuideController@validatingDescription');
		Route::resource('guide', 'GuideController', 
			['except'=> ['create', 'edit']]);

		Route::get('instruction/guide/{id}', 'InstructionController@guide');

		Route::post('instruction/validating/no', 'InstructionController@validatingNo');
		Route::post('instruction/validating/description', 'InstructionController@validatingDescription');
		Route::resource('instruction', 'InstructionController',
			['except' => ['create', 'edit']]);

		Route::get('form/instruction/{id}', 'FormController@instruction');

		Route::post('form/validating/no', 'FormController@validatingNo');
		Route::post('form/validating/description', 'FormController@validatingDescription');
		Route::resource('form', 'FormController',
			['except' => ['create', 'edit']]);
        
        
		/**
		 * WORK AND PROJECT REST API ROUTE
		 */
         
        
		Route::get('work/execute/all', 'WorkController@executeAllWork');
		Route::get('work/execute/{id}', 'WorkController@execute');
		Route::get('work/event/start', 'WorkController@startAllEvent');
		Route::get('work/event/pause', 'WorkController@pauseAllEvent');
		Route::get('work/event/{id}', 'WorkController@eventToggle');
		
		Route::post('work/validating/name', 'WorkController@validatingName');
		Route::resource('work', 'WorkController',
			['except' => ['create', 'edit']]);
		Route::resource('work.form', 'WorkFormController', 
			['only' => ['store', 'update', 'destroy']]);
        
        
		/**
		 * MISC REST API ROUTE (USER, SEMESTER, GROUBJOB)
		 */
         
         
		Route::get('user/administrator', 'UserController@administrator');//dummy service for checking if users is administrator
		Route::resource('user', 'UserController',
			['except' => ['index', 'create', 'edit']]);
		
		Route::resource('user.job', 'UserJobController',
			['only' => ['store', 'update', 'destroy']]);

		Route::get('semester/intersect/{date}/{id?}', 'SemesterController@intersect');
		Route::get('semester/included/{dateStart}/{dateEnded}/{id?}', 'SemesterController@included');
		Route::resource('semester', 'SemesterController',
			['except' => ['create', 'edit']]);

		Route::post('groupJob/validating/name', 'GroupJobController@validatingName');
		Route::resource('groupJob', 'GroupJobController',
			['except' => ['create', 'edit']]);
		Route::resource('groupJob.job', 'GroupJobDetailController', 
			['only' => ['store', 'show', 'update', 'destroy']]);

	});

    

});
