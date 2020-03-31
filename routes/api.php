<?php

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

Route::group(['namespace' => 'Api'], function () {
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
        Route::post('sign/in', 'AuthController@postSignIn')->name('login');
        Route::post('sign/up', 'AuthController@postSignUp');
        Route::post('password/forgot', 'AuthController@postForgotPassword');
        Route::post('password/reset', 'AuthController@postResetPassword');
        Route::post('email/verify', 'AuthController@postVerification');
        Route::post('email/resend', 'AuthController@postResendVerification');

        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('logout', 'AuthController@getLogout');
            Route::get('user', 'AuthController@getUser');
        });
    });

    Route::get('dashboard/infoboxes', 'DashboardController@getInfoBoxes');
    Route::get('dashboard/today/expires/jobs', 'DashboardController@getTodayExpiresJobs');
    Route::get('dashboard/latest/job/applications', 'DashboardController@getLatestJobApplication');
    Route::get('dashboard/company/job/revenue', 'DashboardController@getCompanyJobRevenue');
    Route::get('dashboard/job/application/monthly/revenue', 'DashboardController@getJobApplicationMonthlyRevenue');

    Route::post('users/profile', 'UserController@postProfileByGuid')->middleware('auth:api');
    Route::group(['prefix' => 'users', 'middleware' => ['auth:api', 'userAuth']], function () {
        Route::get('/', 'UserController@getList');
        Route::get('detail/{id}', 'UserController@getDetailByGuid');
        Route::post('create', 'UserController@postCreate');
        Route::post('update', 'UserController@postUpdateByGuid');
        Route::post('delete', 'UserController@postDeleteByGuid');
    });

    Route::group(['prefix' => 'experience/levels', 'middleware' => ['auth:api', 'userAuth']], function () {
        Route::get('/', 'ExperienceLevelController@getList');
        Route::get('detail/{id}', 'ExperienceLevelController@getDetailByGuid');
        Route::post('create', 'ExperienceLevelController@postCreate');
        Route::post('update', 'ExperienceLevelController@postUpdateByGuid');
        Route::post('delete', 'ExperienceLevelController@postDeleteByGuid');
    });

    Route::group(['prefix' => 'qualifications', 'middleware' => ['auth:api', 'userAuth']], function () {
        Route::get('/', 'QualificationController@getList');
        Route::get('detail/{id}', 'QualificationController@getDetailByGuid');
        Route::post('create', 'QualificationController@postCreate');
        Route::post('update', 'QualificationController@postUpdateByGuid');
        Route::post('delete', 'QualificationController@postDeleteByGuid');
    });

    Route::get('job/departments', 'JobDepartmentController@getList');
    Route::group(['prefix' => 'job/departments', 'middleware' => ['auth:api', 'userAuth']], function () {
        Route::get('detail/{id}', 'JobDepartmentController@getDetailByGuid');
        Route::post('create', 'JobDepartmentController@postCreate');
        Route::post('update', 'JobDepartmentController@postUpdateByGuid');
        Route::post('delete', 'JobDepartmentController@postDeleteByGuid');
    });

    Route::group(['prefix' => 'interview/stages', 'middleware' => ['auth:api', 'userAuth']], function () {
        Route::get('/', 'InterviewStageController@getList');
        Route::get('detail/{id}', 'InterviewStageController@getDetailByGuid');
        Route::post('create', 'InterviewStageController@postCreate');
        Route::post('update', 'InterviewStageController@postUpdateByGuid');
        Route::post('delete', 'InterviewStageController@postDeleteByGuid');
    });

    Route::group(['prefix' => 'companies', 'middleware' => ['auth:api', 'userAuth']], function () {
        Route::get('/', 'CompanyController@getList');
        Route::get('detail/{id}', 'CompanyController@getDetailByGuid');
        Route::post('create', 'CompanyController@postCreate');
        Route::post('update', 'CompanyController@postUpdateByGuid');
        Route::post('delete', 'CompanyController@postDeleteByGuid');
    });

    Route::get('jobs', 'JobController@getList');
    Route::group(['prefix' => 'jobs', 'middleware' => ['auth:api', 'userAuth']], function () {
        Route::get('detail/{id}', 'JobController@getDetailByGuid');
        Route::post('create', 'JobController@postCreate');
        Route::post('update', 'JobController@postUpdateByGuid');
//        Route::post('delete', 'JobController@postDeleteByGuid');
        Route::post('archive', 'JobController@postArchiveByGuid');
    });

    Route::group(['prefix' => 'job/applications', 'middleware' => ['auth:api']], function () {
        Route::get('own', 'JobApplicationController@getOwnList');
        Route::post('create', 'JobApplicationController@postCreate');
    });

    Route::group(['prefix' => 'job/applications', 'middleware' => ['auth:api', 'userAuth']], function () {
        Route::get('/', 'JobApplicationController@getList');
        Route::get('detail/{id}', 'JobApplicationController@getDetailByGuid');
        Route::post('update', 'JobApplicationController@postUpdateByGuid');
        Route::post('delete', 'JobApplicationController@postDeleteByGuid');
    });

    Route::group(['prefix' => 'interviews', 'middleware' => ['auth:api', 'userAuth']], function () {
        Route::get('/', 'InterviewController@getList');
        Route::get('detail/{id}', 'InterviewController@getDetailByGuid');
        Route::post('create', 'InterviewController@postCreate');
        Route::post('update', 'InterviewController@postUpdateByGuid');
        Route::post('delete', 'InterviewController@postDeleteByGuid');
    });

    Route::get('document/types', 'DocumentTypeController@getList')->middleware('auth:api');
    Route::group(['prefix' => 'document/types', 'middleware' => ['auth:api', 'userAuth']], function () {
        Route::get('detail/{id}', 'DocumentTypeController@getDetailByGuid');
        Route::post('create', 'DocumentTypeController@postCreate');
        Route::post('update', 'DocumentTypeController@postUpdateByGuid');
        Route::post('delete', 'DocumentTypeController@postDeleteByGuid');
    });

    Route::group(['prefix' => 'document/uploads'], function () {
        Route::get('users', 'DocumentController@getOwnList');
        Route::group(['middleware' => ['auth:api']], function () {
            Route::post('/', 'DocumentController@getList')->middleware('userAuth');
            Route::post('create', 'DocumentController@postCreate');
            Route::post('miscellaneous', 'DocumentController@postMiscellaneousCreate');
            Route::post('delete', 'DocumentController@postDeleteByGuid');
        });
    });

    Route::group(['prefix' => 'reports'], function () {
        Route::group(['middleware' => ['auth:api', 'userAuth']], function () {
            Route::get('candidate/overview', 'ReportController@getCandidateOverview');
            Route::get('job/overview', 'ReportController@getJobOverview');
        });
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', 'SettingController@getList');

        Route::group(['middleware' => ['auth:api', 'userAuth']], function () {
            Route::post('/', 'SettingController@postCreate');
        });
    });
});
