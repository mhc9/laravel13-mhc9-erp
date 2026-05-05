<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Middleware\EnsureClientIsResourceOwner;
use App\Http\Controllers\AuthController;
use App\Models\User;

Route::prefix('auth')->group(function() {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::prefix('m2m')->group(function() {
    /** System */
    Route::get('system', [App\Http\Controllers\SystemController::class, 'getAll']);

    /** Password */
    Route::post( '/change-password', [App\Http\Controllers\ResetPasswordController::class, 'changePassword']);

    /** Users */
    Route::get('/user-for-client', function (Request $request) {
        return User::all();
    });

    /** Employees */
    Route::get('/employees', [App\Http\Controllers\EmployeeController::class, 'getAll']);
    Route::get('/employees/search', [App\Http\Controllers\EmployeeController::class, 'search']);
    Route::get('/employees/{id}', [App\Http\Controllers\EmployeeController::class, 'getById']);
    // Route::get('/employees/init/form', [App\Http\Controllers\EmployeeController::class, 'getInitialFormData']);
    // Route::post('/employees', [App\Http\Controllers\EmployeeController::class, 'store']);
    // Route::post('/employees/{id}/update', [App\Http\Controllers\EmployeeController::class, 'update']);
    // Route::post('/employees/{id}/delete', [App\Http\Controllers\EmployeeController::class, 'destroy']);
    // Route::post('/employees/{id}/upload', [App\Http\Controllers\EmployeeController::class, 'uploadAvatar']);
    // Route::post('/employees/{id}/update/descriptor', [App\Http\Controllers\EmployeeController::class, 'updateDescriptor']);

    /** Departments */
    Route::get('/departments', 'App\Http\Controllers\DepartmentController@getAll');
    Route::get('/departments/{id}', 'App\Http\Controllers\DepartmentController@getById');
    // Route::post('/departments', 'App\Http\Controllers\DepartmentController@store');
    // Route::post('/departments/{id}/update', 'App\Http\Controllers\DepartmentController@update');
    // Route::post('/departments/{id}/delete', 'App\Http\Controllers\DepartmentController@destroy');

    /** Divisions */
    Route::get('/divisions', 'App\Http\Controllers\DivisionController@getAll');
    Route::get('/divisions/{id}', 'App\Http\Controllers\DivisionController@getById');
    // Route::get('/divisions/init/form', 'App\Http\Controllers\DivisionController@getInitialFormData');
    // Route::post('/divisions', 'App\Http\Controllers\DivisionController@store');
    // Route::post('/divisions/{id}/update', 'App\Http\Controllers\DivisionController@update');
    // Route::post('/divisions/{id}/delete', 'App\Http\Controllers\DivisionController@destroy');

    /** Members */
    Route::get('/members', 'App\Http\Controllers\MemberController@getAll');
    Route::get('/members/search', 'App\Http\Controllers\MemberController@search');
    Route::get('/members/{id}', 'App\Http\Controllers\MemberController@getById');
    Route::get('/members/employee/{employeeId}', 'App\Http\Controllers\MemberController@getByEmployee');
    // Route::get('/members/init/form', 'App\Http\Controllers\MemberController@getInitialFormData');
    // Route::post('/members', 'App\Http\Controllers\MemberController@store');

    /** Calendar events */
    Route::get( '/events', [App\Http\Controllers\EventController::class, 'getAll']);

    /** Leaves */
    Route::get( '/leaves', [App\Http\Controllers\LeaveController::class, 'getAll']);
})->middleware(EnsureClientIsResourceOwner::class);
