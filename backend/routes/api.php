<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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

// Auth ...
Route::post('/login', \App\Http\Controllers\LoginController::class);
Route::post('/register', \App\Http\Controllers\RegisterController::class);
Route::post('/logout', \App\Http\Controllers\LogoutController::class);

// Tags...
Route::get('/tags', \App\Http\Controllers\TagController::class);

// User ...
Route::get('/user', \App\Http\Controllers\UserController::class)->middleware(['auth:sanctum']);

// Companies...
Route::get('/companies', [\App\Http\Controllers\CompanyController::class, 'index']);
Route::get('/companies/{company}', [\App\Http\Controllers\CompanyController::class, 'show']);
Route::post('/companies', [\App\Http\Controllers\CompanyController::class, 'create'])->middleware(['auth:sanctum', 'verified']);
Route::put('/companies/{company}', [\App\Http\Controllers\CompanyController::class, 'update'])->middleware(['auth:sanctum', 'verified']);
Route::delete('/companies/{company}', [\App\Http\Controllers\CompanyController::class, 'delete'])->middleware(['auth:sanctum', 'verified']);

// Company Photos...
Route::post('/companies/{company}/images', [\App\Http\Controllers\CompanyImageController::class, 'store'])->middleware(['auth:sanctum', 'verified']);
Route::delete('/companies/{company}/images/{image:id}', [\App\Http\Controllers\CompanyImageController::class, 'delete'])->middleware(['auth:sanctum', 'verified']);

// User Subscriptions...
Route::get('/subscriptions', [\App\Http\Controllers\UserSubscriptionController::class, 'index'])->middleware(['auth:sanctum', 'verified']);
Route::post('/subscriptions', [\App\Http\Controllers\UserSubscriptionController::class, 'create'])->middleware(['auth:sanctum', 'verified']);
Route::delete('/subscriptions/{subscription}', [\App\Http\Controllers\UserSubscriptionController::class, 'cancel'])->middleware(['auth:sanctum', 'verified']);

Route::get('/test', function () {
    try {
        DB::connection()->getPdo();

        return 'DB connected';
    } catch (\Exception $e) {
        $db_connection = env('DB_CONNECTION');
        $db_database = env('DB_DATABASE');
        $db_host = env('DB_HOST');
        $db_port = env('DB_PORT');
        $db_username = env('DB_USERNAME');
        $db_password = env('DB_PASSWORD');
        return "DB disconnected: {$e->getMessage()}, DB_CONNECTION={$db_connection}&DB_DATABASE={$db_database}&DB_HOST={$db_host}&DB_PORT={$db_port}&DB_USERNAME=${db_username}&DB_PASSWORD={$db_password}";
    }
});
