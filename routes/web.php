<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CertificateVerifyController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CurriculumController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\CertificateController as AdminCertificate;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboard;
use App\Http\Controllers\Organization\DashboardController as OrganizationDashboard;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\LearnController;
use App\Http\Controllers\Student\CertificateController as StudentCertificate;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Public certificate verification
Route::get('/verify/{number}', CertificateVerifyController::class)->name('certificate.verify');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Portal
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('courses', CourseController::class);

    Route::get('courses/{course}/curriculum', [CurriculumController::class, 'index'])->name('curriculum.index');
    Route::post('courses/{course}/sections', [CurriculumController::class, 'storeSection'])->name('curriculum.sections.store');
    Route::put('sections/{section}', [CurriculumController::class, 'updateSection'])->name('curriculum.sections.update');
    Route::delete('sections/{section}', [CurriculumController::class, 'destroySection'])->name('curriculum.sections.destroy');
    Route::post('sections/{section}/lessons', [CurriculumController::class, 'storeLesson'])->name('curriculum.lessons.store');
    Route::put('lessons/{lesson}', [CurriculumController::class, 'updateLesson'])->name('curriculum.lessons.update');
    Route::delete('lessons/{lesson}', [CurriculumController::class, 'destroyLesson'])->name('curriculum.lessons.destroy');

    Route::get('courses/{course}/lessons/{lesson}/quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::put('lessons/{lesson}/pass-mark', [QuizController::class, 'updatePassMark'])->name('quiz.passmark');
    Route::post('lessons/{lesson}/questions', [QuizController::class, 'storeQuestion'])->name('quiz.questions.store');
    Route::delete('questions/{question}', [QuizController::class, 'destroyQuestion'])->name('quiz.questions.destroy');

    // Certificates
    Route::get('certificates', [AdminCertificate::class, 'index'])->name('certificates.index');
    Route::get('certificate-settings', [AdminCertificate::class, 'settings'])->name('certificate-settings.edit');
    Route::put('certificate-settings', [AdminCertificate::class, 'updateSettings'])->name('certificate-settings.update');
});

/*
|--------------------------------------------------------------------------
| Instructor Portal
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:instructor'])->prefix('instructor')->group(function () {
    Route::get('/dashboard', [InstructorDashboard::class, 'index'])->name('instructor.dashboard');
});

/*
|--------------------------------------------------------------------------
| Organization Portal
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:organization'])->prefix('organization')->group(function () {
    Route::get('/dashboard', [OrganizationDashboard::class, 'index'])->name('organization.dashboard');
});

/*
|--------------------------------------------------------------------------
| Student Portal
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {
    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('student.dashboard');

    Route::post('courses/{course}/enroll', [LearnController::class, 'enroll'])->name('student.enroll');

    Route::get('courses/{course}/learn', [LearnController::class, 'learn'])->name('student.learn');
    Route::get('courses/{course}/learn/{lesson}', [LearnController::class, 'learn'])->name('student.learn.lesson');

    Route::post('lessons/{lesson}/complete', [LearnController::class, 'complete'])->name('student.lesson.complete');
    Route::post('lessons/{lesson}/quiz', [LearnController::class, 'submitQuiz'])->name('student.quiz.submit');

    // Certificate (view / print)
    Route::get('courses/{course}/certificate', [StudentCertificate::class, 'show'])->name('student.certificate');
});
