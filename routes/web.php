<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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
Auth::routes();

Route::get('/', function () { return view('system.dashboard'); })->name('dashboard');

Route::resource('career', CareerController::class);
Route::resource('course', CourseController::class);
Route::resource('document', DocumentController::class);


Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('admin/home', [HomeController::class, 'adminHome'])->name('admin.home')->middleware('is_admin');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('user-edit', [HomeController::class, 'edit'])->name('edit_user');
Route::put('user-edit/{id}', [HomeController::class, 'update_user'])->name('update_user');
