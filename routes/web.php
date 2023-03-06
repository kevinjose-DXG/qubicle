<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BusinessController;
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
    Route::any('/', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::group(['middleware' => ['auth:web']], function () {
        Route::any('/dashboard', [AdminController::class, 'showDashboard'])->name('dashboard');
        Route::any('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');
        //category
        Route::any('/category', [CategoryController::class, 'showCategory'])->name('showCategory');
        Route::any('/category/save', [CategoryController::class, 'saveCategory'])->name('saveCategory');
        Route::any('/category/edit/{id}', [CategoryController::class, 'editCategory'])->name('editCategory');
        Route::any('/category/change/status', [CategoryController::class, 'changeCategoryStatus'])->name('changeCategoryStatus');
        //Sub category
        Route::any('/subcategory', [SubCategoryController::class, 'showSubCategory'])->name('showSubCategory');
        Route::any('/subcategory/save', [SubCategoryController::class, 'saveSubCategory'])->name('saveSubCategory');
        Route::any('/subcategory/edit/{id}', [SubCategoryController::class, 'editSubCategory'])->name('editSubCategory');
        Route::any('/subcategory/change/status', [SubCategoryController::class, 'changeSubCategoryStatus'])->name('changeSubCategoryStatus');
        //Brand
        Route::any('/brand', [BrandController::class, 'showBrand'])->name('showBrand');
        Route::any('/brand/save', [BrandController::class, 'saveBrand'])->name('saveBrand');
        Route::any('/brand/edit/{id}', [BrandController::class, 'editBrand'])->name('editBrand');
        Route::any('/brand/change/status', [BrandController::class, 'changeBrandStatus'])->name('changeBrandStatus');
        //Model
        Route::any('model', [ModelController::class, 'showModel'])->name('showModel');
        Route::any('model/save', [ModelController::class, 'saveModel'])->name('saveModel');
        Route::any('model/edit/{id}', [ModelController::class, 'editModel'])->name('editModel');
        Route::any('model/change/status', [ModelController::class, 'changeModelStatus'])->name('changeModelStatus');
       //Orders
        Route::any('/order', [OrderController::class, 'showOrder'])->name('showOrder');
        Route::any('/order/save', [OrderController::class, 'saveOrder'])->name('saveOrder');
        Route::any('/order/view/{id}', [OrderController::class, 'viewOrder'])->name('viewOrder');
        Route::any('/order/change/status', [OrderController::class, 'changeOrderstatus'])->name('changeOrderstatus');
        Route::any('/order/filter', [OrderController::class, 'filterOrder'])->name('filterOrder');
        Route::any('/order/approval', [OrderController::class, 'flyerApprovalByAdmin'])->name('flyerApprovalByAdmin');
        Route::any('/order/delete', [OrderController::class, 'flyerDeleteByAdmin'])->name('flyerDeleteByAdmin');
        Route::any('/order/flyer/status', [OrderController::class, 'flyerStatusChange'])->name('flyerStatusChange');
        Route::any('/order/admin/delete', [OrderController::class, 'deleteFlyerByAdmin'])->name('deleteFlyerByAdmin');
        //plan master
        Route::any('/plan', [PlanController::class, 'showPlan'])->name('showPlan');
        Route::any('/plan/create', [PlanController::class, 'createPlan'])->name('createPlan');
        Route::any('/plan/save', [PlanController::class, 'savePlan'])->name('savePlan');
        Route::any('/plan/edit/{id}', [PlanController::class, 'editPlan'])->name('editPlan');
        Route::any('/plan/change/status', [PlanController::class, 'changePlanStatus'])->name('changePlanStatus');
        //vendor master
        Route::any('/vendor', [VendorController::class, 'showVendor'])->name('showVendor');
        Route::any('/vendor/create', [VendorController::class, 'createVendor'])->name('createVendor');
        Route::any('/vendor/save', [VendorController::class, 'saveVendor'])->name('saveVendor');
        Route::any('/vendor/edit/{id}', [VendorController::class, 'editVendor'])->name('editVendor');
        Route::any('/vendor/change/status', [VendorController::class, 'changeVendorStatus'])->name('changeVendorStatus');
        Route::any('/vendor/approval/status', [VendorController::class, 'vendorAdminApproval'])->name('vendorAdminApproval');
        Route::any('/vendor/payment/status', [VendorController::class, 'adminApprovalVendorPayment'])->name('adminApprovalVendorPayment');
        Route::any('/vendor/filter', [VendorController::class, 'filterVendor'])->name('filterVendor');
        Route::any('/vendor/view/{id}', [VendorController::class, 'viewVendor'])->name('viewVendor');
        Route::any('/vendor/edit/{id}', [VendorController::class, 'editVendor'])->name('editVendor');
        Route::any('/vendor/fetch/district', [VendorController::class, 'fetchVendorDistrict'])->name('fetchVendorDistrict');
        Route::any('/vendor/reject/reason', [VendorController::class, 'saveRejectReason'])->name('saveRejectReason');
        //State
        Route::any('/state', [StateController::class, 'showState'])->name('showState');
        Route::any('/state/save', [StateController::class, 'saveState'])->name('saveState');
        Route::any('/state/edit/{id}', [StateController::class, 'editState'])->name('editState');
        Route::any('/state/change/status', [StateController::class, 'changeStateStatus'])->name('changeStateStatus');
        //District
        Route::any('/district', [DistrictController::class, 'showDistrict'])->name('showDistrict');
        Route::any('/district/save', [DistrictController::class, 'saveDistrict'])->name('saveDistrict');
        Route::any('/district/edit/{id}', [DistrictController::class, 'editDistrict'])->name('editDistrict');
        Route::any('/district/change/status', [DistrictController::class, 'changeDistrictStatus'])->name('changeDistrictStatus');
        Route::any('/district/fetch/district', [DistrictController::class, 'fetchDistrict'])->name('fetchDistrict');
        //location
        Route::any('/location', [LocationController::class, 'showLocation'])->name('showLocation');
        Route::any('/location/save', [LocationController::class, 'saveLocation'])->name('saveLocation');
        Route::any('/location/edit/{id}', [LocationController::class, 'editLocation'])->name('editLocation');
        Route::any('/location/change/status', [LocationController::class, 'changeLocationStatus'])->name('changeLocationStatus');
        Route::any('/location/fetch/district', [LocationController::class, 'fetchLocation'])->name('fetchLocation');
        //vendor flyers
        Route::any('/vendor/flyer', [VendorFlyerController::class, 'showVendorFlyer'])->name('showVendorFlyer');
        Route::any('/vendor/flyer/save', [VendorFlyerController::class, 'saveVendorFlyer'])->name('saveVendorFlyer');
        Route::any('/vendor/flyer/view/{id}', [VendorFlyerController::class, 'viewVendorFlyer'])->name('viewVendorFlyer');
        Route::any('/vendor/flyer/change/status', [VendorFlyerController::class, 'changeVendorFlyerStatus'])->name('changeVendorFlyerStatus');
        Route::any('/vendor/flyer/approval', [VendorFlyerController::class, 'flyerApprovalByAdmin'])->name('flyerApprovalByAdmin');
        Route::any('/vendor/flyer/delete', [VendorFlyerController::class, 'flyerDeleteByAdmin'])->name('flyerDeleteByAdmin');
        Route::any('/vendor/change/flyer/status', [VendorController::class, 'flyerStatusChange'])->name('flyerStatusChange');
        Route::any('/vendor/flyer/admin/delete', [VendorController::class, 'deleteFlyerByAdmin'])->name('deleteFlyerByAdmin');
        //Designed
        Route::any('/designed', [DesignedController::class, 'showDesigned'])->name('showDesigned');
        Route::any('/designed/save', [DesignedController::class, 'saveDesigned'])->name('saveDesigned');
        Route::any('/designed/edit/{id}', [DesignedController::class, 'editDesigned'])->name('editDesigned');
        Route::any('/designed/change/status', [DesignedController::class, 'changeDesignedStatus'])->name('changeDesignedStatus');
        //location
        Route::any('/slider', [SliderController::class, 'showSlider'])->name('showSlider');
        Route::any('/slider/save', [SliderController::class, 'saveSlider'])->name('saveSlider');
        Route::any('/slider/edit/{id}', [SliderController::class, 'editSlider'])->name('editSlider');
        Route::any('/slider/change/status', [SliderController::class, 'changeSliderStatus'])->name('changeSliderStatus');
        //Reports
        Route::any('/report/transaction', [AdminController::class, 'transactionReport'])->name('transactionReport');
        Route::any('/report/filter/transaction', [AdminController::class, 'filterTransactionDetails'])->name('filterTransactionDetails');
        //pending vendors
        Route::any('/vendor/pending', [AdminController::class, 'showPendingVendor'])->name('showPendingVendor');
        Route::any('/vendor/pending/filter', [AdminController::class, 'filterPendingVendor'])->name('filterPendingVendor');
        Route::any('/vendor/pending/payment', [AdminController::class, 'showPendingVendorPayment'])->name('showPendingVendorPayment');
        //customers
        Route::any('/customer/list', [AdminController::class, 'showCustomer'])->name('showCustomer');
        Route::any('/customer/filter', [AdminController::class, 'filterCustomer'])->name('filterCustomer');
        Route::any('/customer/change/status', [AdminController::class, 'changeCustomerStatus'])->name('changeCustomerStatus');
        //Sample Images
        Route::any('/sampleproduct', [SampleProductController::class, 'showSampleProduct'])->name('showSampleProduct');
        Route::any('/sampleproduct/save', [SampleProductController::class, 'saveSampleProduct'])->name('saveSampleProduct');
        Route::any('/sampleproduct/edit/{id}', [SampleProductController::class, 'editSampleProduct'])->name('editSampleProduct');
        Route::any('/sampleproduct/change/status', [SampleProductController::class, 'changeSampleProductStatus'])->name('changeSampleProductStatus');
        //support
        Route::any('/support', [SupportController::class, 'showSupport'])->name('showSupport');
        Route::any('/support/save', [SupportController::class, 'saveSupport'])->name('saveSupport');
        Route::any('/support/edit/{id}', [SupportController::class, 'editSupport'])->name('editSupport');
        Route::any('/support/change/status', [SupportController::class, 'changeSupportStatus'])->name('changeSupportStatus');
        //Policy
        Route::any('/policy', [PolicyController::class, 'showPolicy'])->name('showPolicy');
        Route::any('/policy/save', [PolicyController::class, 'savePolicy'])->name('savePolicy');
        Route::any('/policy/edit/{id}', [PolicyController::class, 'editPolicy'])->name('editPolicy');
        Route::any('/policy/change/status', [PolicyController::class, 'changePolicyStatus'])->name('changePolicyStatus');
        //image download for order details
        Route::get('/download-image/{path}', function ($path) {
            $image_path = storage_path('/app/public/brand/' . $path);
            return response()->download($image_path);
        })->name('download-image');

    });
require __DIR__.'/auth.php';