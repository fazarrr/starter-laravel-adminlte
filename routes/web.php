<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    HomeController,
    UserController,
};

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login/authentication', [AuthController::class, 'Authentication'])->name('authentication');

Route::middleware(['auth'])->group(function () {
    Route::resource('/', HomeController::class);
    Route::get('login/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('login/logout-nonaktif', [AuthController::class, 'logoutNonAktif'])->name('logout-nonaktif');

    Route::resource('user', UserController::class);
    Route::get('user/data/json', [UserController::class, 'json']);
});
