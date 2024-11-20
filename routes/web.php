<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyJobController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobCandidateController;
use App\Models\JobCandidate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/details/{company_job:slug}', [HomeController::class, 'details'])->name('home.details');
Route::get('/category/{category:slug}', [HomeController::class, 'category'])->name('home.category');
Route::get('/search/jobs/', [HomeController::class, 'search'])->name('home.search');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('can:apply job')->group(function () {
        Route::get('/apply/success', [HomeController::class, 'success_apply'])->name('home.apply.success');

        Route::get('/apply/{company_job:slug}', [HomeController::class, 'apply'])->name('home.apply');
        Route::post('/apply/{company_job:slug}/submit', [HomeController::class, 'apply_store'])->name('home.apply.store');
    });

    Route::prefix('dashboard')->name('dashboard.')->group(function () {

        Route::middleware('can:apply job')->group(function () {
            Route::get('my-applications', [DashboardController::class, 'my_applications'])->name('my.applications');
            Route::get('my-applications/{job_candidate}', [DashboardController::class, 'my_applications_details'])->name('my.application.details');
        });
    });

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::middleware('can:manage categories')->group(function () {
            Route::resource('categories', CategoryController::class);
        });

        Route::middleware('can:manage company')->group(function () {
            Route::resource('company', CompanyController::class);
        });

        Route::middleware('can:manage jobs')->group(function () {
            Route::resource('company_jobs', CompanyJobController::class);
        });

        Route::middleware('can:manage candidates')->group(function () {
            Route::resource('job_candidates', JobCandidateController::class);
            Route::get('/candidate/{job_candidate}/resume/download', [JobCandidateController::class, 'download_file'])->name('download_resume');
        });
    });
});

require __DIR__ . '/auth.php';
