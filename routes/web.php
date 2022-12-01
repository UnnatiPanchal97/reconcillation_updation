<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\CompaniesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
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
// Route::get('/', function () {
//     return view('welcome');
// });
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::post('/create-organisation', [CompaniesController::class, 'store'])->name('sign-up.store');
//company information(profile) routes
Route::get('new-company/basic-information', [SettingsController::class, 'newCompanyInfo'])->name('new-company.info');
Route::post('new-company/basic-information/save', [SettingsController::class, 'saveBasicCompanyInfo'])->name('new-company.info.save');
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile-show', [ProfileController::class, 'show'])->name('profile.show');
    Route::resource('/user', UserController::class);
});
require __DIR__.'/auth.php';
