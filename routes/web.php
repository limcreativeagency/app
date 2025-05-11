<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalRegistrationController;

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
});

Route::get('language/{lang}', [App\Http\Controllers\LanguageController::class, 'switchLang'])->name('language.switch');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/register/step1', [HospitalRegistrationController::class, 'step1'])->name('register.step1');
Route::post('/register/step1', [HospitalRegistrationController::class, 'step1Submit']);

Route::get('/register/step2', [HospitalRegistrationController::class, 'step2'])->name('register.step2');
Route::post('/register/step2', [HospitalRegistrationController::class, 'step2Submit']);

Route::get('/register/step3', [HospitalRegistrationController::class, 'step3'])->name('register.step3');
Route::post('/register/step3', [HospitalRegistrationController::class, 'step3Submit']);
