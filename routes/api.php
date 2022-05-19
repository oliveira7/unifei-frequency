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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group( function () {
    Route::controller(StudentController::class)->group( function () {
        Route::get('/students', 'index');
        Route::get('/students/{id}', 'show');
        Route::post('/students', 'store');
        Route::put('/students/{id}', 'update');
        Route::delete('/students/{id}', 'delete');
    });

    Route::controller(TeacherController::class)->group( function () {
        Route::get('/teachers', 'index');
        Route::get('/teachers/{id}', 'show');
        Route::post('/teachers', 'store');
        Route::put('/teachers/{id}', 'update');
        Route::delete('/teachers/{id}', 'delete');
    });

    Route::controller(TeacherController::class)->group( function () {
        Route::get('/teachers', 'index');
        Route::get('/teachers/{id}', 'show');
        Route::post('/teachers', 'store');
        Route::put('/teachers/{id}', 'update');
        Route::delete('/teachers/{id}', 'delete');
    });

    Route::controller(ActivityController::class)->group( function () {
        Route::get('/activities', 'index');
        Route::get('/activities/{id}', 'show');
        Route::post('/activities', 'store');
        Route::put('/activities/{id}', 'update');
        Route::delete('/activities/{id}', 'delete');
    });

    Route::controller(RegistrationController::class)->group( function () {
        Route::get('/activities', 'index');
        Route::get('/activities/{id}', 'show');
        Route::post('/activities', 'store');
        Route::put('/activities/{id}', 'update');
        Route::delete('/activities/{id}', 'delete');
    });

    Route::controller(EnrollmentController::class)->group( function () {
        Route::get('/enrollments', 'index');
        Route::get('/enrollments/{id}', 'show');
        Route::post('/enrollments', 'store');
        Route::put('/enrollments/{id}', 'update');
        Route::delete('/enrollments/{id}', 'delete');
    });

    Route::controller(EnrollmentController::class)->group( function () {
        Route::get('/enrollments', 'index');
        Route::get('/enrollments/{id}', 'show');
        Route::post('/enrollments', 'store');
        Route::put('/enrollments/{id}', 'update');
        Route::delete('/enrollments/{id}', 'delete');
    });

    Route::controller(FrequencyController::class)->group( function () {
        Route::get('/frequencies', 'index');
        Route::get('/frequencies/{id}', 'show');
        Route::post('/frequencies', 'store');
        Route::put('/frequencies/{id}', 'update');
        Route::delete('/frequencies/{id}', 'delete');
    });
});