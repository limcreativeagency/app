<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalRegistrationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\EmergencyContactController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\TreatmentPhotoController;
use App\Http\Controllers\MedicationPlanController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\TreatmentNoteController;

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

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/clinic/dashboard', [App\Http\Controllers\ClinicController::class, 'dashboard'])
    ->middleware(['auth', 'role:2,3,4'])
    ->name('clinic.dashboard');

Route::get('/clinic/hospital/edit', [App\Http\Controllers\ClinicController::class, 'editHospital'])
    ->middleware(['auth', 'role:2'])
    ->name('clinic.hospital.edit');

Route::put('/clinic/hospital/update', [App\Http\Controllers\ClinicController::class, 'updateHospital'])
    ->middleware(['auth', 'role:2'])
    ->name('clinic.hospital.update');

Route::prefix('users')->middleware('auth')->group(function () {
    Route::get('/doctors', [App\Http\Controllers\DoctorRepresentativeController::class, 'index'])->defaults('type', 'doctor')->name('users.index.doctor');
    Route::get('/representatives', [App\Http\Controllers\DoctorRepresentativeController::class, 'index'])->defaults('type', 'representative')->name('users.index.representative');
    Route::get('/create/{type}', [App\Http\Controllers\DoctorRepresentativeController::class, 'create'])->name('users.create');
    Route::post('/store/{type}', [App\Http\Controllers\DoctorRepresentativeController::class, 'store'])->name('users.store');
    Route::get('/{type}/{user}/edit', [App\Http\Controllers\DoctorRepresentativeController::class, 'edit'])->name('users.edit');
    Route::put('/{type}/{user}', [App\Http\Controllers\DoctorRepresentativeController::class, 'update'])->name('users.update');
    Route::delete('/{type}/{user}', [App\Http\Controllers\DoctorRepresentativeController::class, 'destroy'])->name('users.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Patient Routes
    Route::get('patients/treatments', [PatientController::class, 'treatments'])->name('patients.treatments');
    Route::resource('patients', PatientController::class);
    Route::get('patients/{userId}/verify', [PatientController::class, 'showVerification'])->name('patients.verify');
    Route::post('patients/{userId}/verify', [PatientController::class, 'verify'])->name('patients.verify.submit');
    Route::delete('emergency-contacts/{emergencyContact}', [EmergencyContactController::class, 'destroy'])->name('emergency-contacts.destroy');
    
    // Treatment Routes
    Route::resource('treatments', TreatmentController::class);
    Route::post('treatments/{treatment}/photos', [TreatmentPhotoController::class, 'store'])->name('treatments.photos.upload');
    Route::post('treatments/{treatment}/photos/download-zip', [\App\Http\Controllers\TreatmentPhotoController::class, 'downloadZip'])->name('treatments.photos.downloadZip');

    // Treatment Notes Routes
    Route::post('treatments/{treatment}/notes', [TreatmentNoteController::class, 'store'])->name('treatments.notes.store');
    Route::delete('treatment-notes/{note}', [TreatmentNoteController::class, 'destroy'])->name('treatments.notes.destroy');
    Route::get('treatments/{treatment}/messages', [TreatmentNoteController::class, 'messages'])->name('treatments.messages');
    Route::get('messages/unread', [App\Http\Controllers\TreatmentNoteController::class, 'unreadMessages'])->name('messages.unread');

    Route::controller(TreatmentPhotoController::class)->prefix('treatments/{treatment}/photos')->name('treatment.photos.')->group(function () {
        Route::get('preop', 'showPreopPhotos')->name('preop');
        Route::get('day/{day}', 'showDayPhotos')->name('day');
        Route::get('month/{month}', 'showMonthPhotos')->name('month');
        Route::get('longterm', 'showLongTermPhotos')->name('longterm');
    });

    // Medication Plan Routes
    Route::get('/medication-plans', [MedicationPlanController::class, 'index'])->name('medication-plans.index');
    Route::get('/medication-plans/create/{treatment?}', [MedicationPlanController::class, 'create'])->name('medication-plans.create');
    Route::post('/medication-plans', [MedicationPlanController::class, 'store'])->name('medication-plans.store');
    Route::get('/medication-plans/{plan}/edit', [MedicationPlanController::class, 'edit'])->name('medication-plans.edit');
    Route::put('/medication-plans/{plan}', [MedicationPlanController::class, 'update'])->name('medication-plans.update');
    Route::delete('/medication-plans/{plan}', [MedicationPlanController::class, 'destroy'])->name('medication-plans.destroy');

    // Photo Routes
    Route::get('/photos', [TreatmentPhotoController::class, 'index'])->name('photos.index');
    Route::get('/photos/{treatment}/upload', [TreatmentPhotoController::class, 'create'])->name('photos.create');
    Route::post('/photos/{treatment}', [TreatmentPhotoController::class, 'store'])->name('photos.store');
});
