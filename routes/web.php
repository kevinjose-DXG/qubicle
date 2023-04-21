<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\VendorFlyerController;
use App\Http\Controllers\DesignedController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SampleProductController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ComboController;
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
    Route::any('/', [UserController::class, 'showRegistration'])->name('user.registration');
    Route::any('/user/register', [UserController::class, 'saveUserRegistration'])->name('save.user.registration');
    Route::group(['prefix' => 'admin'], function () {
        Route::any('/', [AdminController::class, 'showLogin'])->name('admin.login');
        Route::group(['middleware' => ['auth:web']], function () {
        Route::any('/dashboard', [AdminController::class, 'showDashboard'])->name('dashboard');
        Route::any('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');
        //user
        Route::any('/user/list', [AdminController::class, 'showUser'])->name('user.list');
        });
    });
require __DIR__.'/auth.php';