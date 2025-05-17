<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalRegistrationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\EmergencyContactController;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/register/step1', [HospitalRegistrationController::class, 'step1'])->name('register.step1');
Route::post('/register/step1', [HospitalRegistrationController::class, 'step1Submit']);

Route::get('/register/step2', [HospitalRegistrationController::class, 'step2'])->name('register.step2');
Route::post('/register/step2', [HospitalRegistrationController::class, 'step2Submit']);

Route::get('/register/step3', [HospitalRegistrationController::class, 'step3'])->name('register.step3');
Route::post('/register/step3', [HospitalRegistrationController::class, 'step3Submit']);

Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/clinic/dashboard', [App\Http\Controllers\ClinicController::class, 'dashboard'])
    ->middleware(['auth', 'role:2'])
    ->name('clinic.dashboard');

Route::get('/clinic/hospital/edit', [App\Http\Controllers\ClinicController::class, 'editHospital'])
    ->middleware(['auth', 'role:2'])
    ->name('clinic.hospital.edit');

Route::put('/clinic/hospital/update', [App\Http\Controllers\ClinicController::class, 'updateHospital'])
    ->middleware(['auth', 'role:2'])
    ->name('clinic.hospital.update');

Route::prefix('users')->middleware('auth')->group(function () {
    Route::get('/doctors', [App\Http\Controllers\DoctorRepresentativeController::class, 'index'])->defaults('role', 'doctor')->name('users.index.doctor');
    Route::get('/representatives', [App\Http\Controllers\DoctorRepresentativeController::class, 'index'])->defaults('role', 'representative')->name('users.index.representative');
    Route::get('/create/{role}', [App\Http\Controllers\DoctorRepresentativeController::class, 'create'])->name('users.create');
    Route::post('/store/{role}', [App\Http\Controllers\DoctorRepresentativeController::class, 'store'])->name('users.store');
    Route::get('/{role}/{user}/edit', [App\Http\Controllers\DoctorRepresentativeController::class, 'edit'])->name('users.edit');
    Route::put('/{role}/{user}', [App\Http\Controllers\DoctorRepresentativeController::class, 'update'])->name('users.update');
    Route::delete('/{role}/{user}', [App\Http\Controllers\DoctorRepresentativeController::class, 'destroy'])->name('users.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Patient Routes
    Route::resource('patients', PatientController::class);
    Route::get('patients/{userId}/verify', [PatientController::class, 'showVerification'])->name('patients.verify');
    Route::post('patients/{userId}/verify', [PatientController::class, 'verify'])->name('patients.verify.submit');
    Route::delete('emergency-contacts/{emergencyContact}', [EmergencyContactController::class, 'destroy'])->name('emergency-contacts.destroy');
});
