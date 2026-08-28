<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\DocumentTypeController;
use App\Http\Controllers\Api\ExperienceLevelController;
use App\Http\Controllers\Api\InterviewController;
use App\Http\Controllers\Api\InterviewStageController;
use App\Http\Controllers\Api\JobApplicationController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\JobDepartmentController;
use App\Http\Controllers\Api\QualificationController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('sign/in', [AuthController::class, 'postSignIn'])->name('login');
    Route::post('sign/up', [AuthController::class, 'postSignUp']);
    Route::post('password/forgot', [AuthController::class, 'postForgotPassword']);
    Route::post('password/reset', [AuthController::class, 'postResetPassword']);
    Route::post('email/verify', [AuthController::class, 'postVerification']);
    Route::post('email/resend', [AuthController::class, 'postResendVerification']);

    Route::middleware('auth:api')->group(function () {
        Route::get('logout', [AuthController::class, 'getLogout']);
        Route::get('user', [AuthController::class, 'getUser']);
    });
});

Route::get('dashboard/infoboxes', [DashboardController::class, 'getInfoBoxes']);
Route::get('dashboard/today/expires/jobs', [DashboardController::class, 'getTodayExpiresJobs']);
Route::get('dashboard/latest/job/applications', [DashboardController::class, 'getLatestJobApplication']);
Route::get('dashboard/company/job/revenue', [DashboardController::class, 'getCompanyJobRevenue']);
Route::get('dashboard/job/application/monthly/revenue', [DashboardController::class, 'getJobApplicationMonthlyRevenue']);

Route::post('users/profile', [UserController::class, 'postProfileByGuid'])->middleware('auth:api');
Route::prefix('users')->middleware(['auth:api', 'userAuth'])->group(function () {
    Route::get('/', [UserController::class, 'getList']);
    Route::get('detail/{id}', [UserController::class, 'getDetailByGuid']);
    Route::post('create', [UserController::class, 'postCreate']);
    Route::post('update', [UserController::class, 'postUpdateByGuid']);
    Route::post('delete', [UserController::class, 'postDeleteByGuid']);
});

Route::prefix('experience/levels')->middleware(['auth:api', 'userAuth'])->group(function () {
    Route::get('/', [ExperienceLevelController::class, 'getList']);
    Route::get('detail/{id}', [ExperienceLevelController::class, 'getDetailByGuid']);
    Route::post('create', [ExperienceLevelController::class, 'postCreate']);
    Route::post('update', [ExperienceLevelController::class, 'postUpdateByGuid']);
    Route::post('delete', [ExperienceLevelController::class, 'postDeleteByGuid']);
});

Route::prefix('qualifications')->middleware(['auth:api', 'userAuth'])->group(function () {
    Route::get('/', [QualificationController::class, 'getList']);
    Route::get('detail/{id}', [QualificationController::class, 'getDetailByGuid']);
    Route::post('create', [QualificationController::class, 'postCreate']);
    Route::post('update', [QualificationController::class, 'postUpdateByGuid']);
    Route::post('delete', [QualificationController::class, 'postDeleteByGuid']);
});

Route::get('job/departments', [JobDepartmentController::class, 'getList']);
Route::prefix('job/departments')->middleware(['auth:api', 'userAuth'])->group(function () {
    Route::get('detail/{id}', [JobDepartmentController::class, 'getDetailByGuid']);
    Route::post('create', [JobDepartmentController::class, 'postCreate']);
    Route::post('update', [JobDepartmentController::class, 'postUpdateByGuid']);
    Route::post('delete', [JobDepartmentController::class, 'postDeleteByGuid']);
});

Route::prefix('interview/stages')->middleware(['auth:api', 'userAuth'])->group(function () {
    Route::get('/', [InterviewStageController::class, 'getList']);
    Route::get('detail/{id}', [InterviewStageController::class, 'getDetailByGuid']);
    Route::post('create', [InterviewStageController::class, 'postCreate']);
    Route::post('update', [InterviewStageController::class, 'postUpdateByGuid']);
    Route::post('delete', [InterviewStageController::class, 'postDeleteByGuid']);
});

Route::prefix('companies')->middleware(['auth:api', 'userAuth'])->group(function () {
    Route::get('/', [CompanyController::class, 'getList']);
    Route::get('detail/{id}', [CompanyController::class, 'getDetailByGuid']);
    Route::post('create', [CompanyController::class, 'postCreate']);
    Route::post('update', [CompanyController::class, 'postUpdateByGuid']);
    Route::post('delete', [CompanyController::class, 'postDeleteByGuid']);
});

Route::get('jobs', [JobController::class, 'getList']);
Route::prefix('jobs')->middleware(['auth:api', 'userAuth'])->group(function () {
    Route::get('detail/{id}', [JobController::class, 'getDetailByGuid']);
    Route::post('create', [JobController::class, 'postCreate']);
    Route::post('update', [JobController::class, 'postUpdateByGuid']);
    Route::post('archive', [JobController::class, 'postArchiveByGuid']);
});

Route::prefix('job/applications')->middleware(['auth:api'])->group(function () {
    Route::get('own', [JobApplicationController::class, 'getOwnList']);
    Route::post('create', [JobApplicationController::class, 'postCreate']);
});

Route::prefix('job/applications')->middleware(['auth:api', 'userAuth'])->group(function () {
    Route::get('/', [JobApplicationController::class, 'getList']);
    Route::get('detail/{id}', [JobApplicationController::class, 'getDetailByGuid']);
    Route::post('update', [JobApplicationController::class, 'postUpdateByGuid']);
    Route::post('delete', [JobApplicationController::class, 'postDeleteByGuid']);
});

Route::prefix('interviews')->middleware(['auth:api', 'userAuth'])->group(function () {
    Route::get('/', [InterviewController::class, 'getList']);
    Route::get('detail/{id}', [InterviewController::class, 'getDetailByGuid']);
    Route::post('create', [InterviewController::class, 'postCreate']);
    Route::post('update', [InterviewController::class, 'postUpdateByGuid']);
    Route::post('delete', [InterviewController::class, 'postDeleteByGuid']);
});

Route::get('document/types', [DocumentTypeController::class, 'getList'])->middleware('auth:api');
Route::prefix('document/types')->middleware(['auth:api', 'userAuth'])->group(function () {
    Route::get('detail/{id}', [DocumentTypeController::class, 'getDetailByGuid']);
    Route::post('create', [DocumentTypeController::class, 'postCreate']);
    Route::post('update', [DocumentTypeController::class, 'postUpdateByGuid']);
    Route::post('delete', [DocumentTypeController::class, 'postDeleteByGuid']);
});

Route::prefix('document/uploads')->group(function () {
    Route::get('users', [DocumentController::class, 'getOwnList']);
    Route::middleware(['auth:api'])->group(function () {
        Route::post('/', [DocumentController::class, 'getList'])->middleware('userAuth');
        Route::post('create', [DocumentController::class, 'postCreate']);
        Route::post('miscellaneous', [DocumentController::class, 'postMiscellaneousCreate']);
        Route::post('delete', [DocumentController::class, 'postDeleteByGuid']);
    });
});

Route::prefix('reports')->middleware(['auth:api', 'userAuth'])->group(function () {
    Route::get('candidate/overview', [ReportController::class, 'getCandidateOverview']);
    Route::get('job/overview', [ReportController::class, 'getJobOverview']);
});

Route::prefix('settings')->group(function () {
    Route::get('/', [SettingController::class, 'getList']);

    Route::middleware(['auth:api', 'userAuth'])->group(function () {
        Route::post('/', [SettingController::class, 'postCreate']);
    });
});
