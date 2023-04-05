<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/customer/to/vendor', [ApiController::class, 'changeCustomerToVendor']);
Route::post('check/plan', [ApiController::class, 'checkPlan']);
Route::post('send/sms', [ApiController::class, 'sendSms']);
Route::post('/state/district/location', [ApiController::class, 'getStateDistrictLocation']);
Route::prefix('vendor')->group(function () {
    Route::post('/login', [ApiController::class, 'vendorLogin'])->name('vendorLogin');
    Route::post('/registration', [ApiController::class, 'vendorRegistration'])->name('vendorRegistration');
    Route::post('/business/details', [ApiController::class, 'vendorBusinessDetail'])->name('vendorBusinessDetail')->middleware(['auth:api']);
    Route::get('/details', [ApiController::class, 'vendorDetail'])->middleware(['auth:api']);
    Route::get('/category/business', [ApiController::class, 'getBusinessCategoryList'])->middleware(['auth:api']);
    Route::post('/category/shop', [ApiController::class, 'getCategoryList'])->middleware(['auth:api']);
    Route::post('/plan/save', [ApiController::class, 'saveVendorPlanDetail'])->middleware(['auth:api']);
    Route::get('/plan', [ApiController::class, 'getPlanDetails'])->middleware(['auth:api']);
    Route::post('/plan/details', [ApiController::class, 'getPlanDetailsOfPlan'])->middleware(['auth:api']);
    Route::post('/plan/activate', [ApiController::class, 'acivateVendorPlan'])->middleware(['auth:api']);
    Route::post('/flyers/add', [ApiController::class, 'addFlyers'])->middleware(['auth:api']);
    Route::post('/plan/payment', [ApiController::class, 'planPayment'])->middleware(['auth:api']);
    Route::get('/states', [ApiController::class, 'getStates'])->middleware(['auth:api']);
    Route::post('/distirct/by/state', [ApiController::class, 'getDistrictByState'])->middleware(['auth:api']);
    Route::post('/location/by/district', [ApiController::class, 'getLocationByDistrict'])->middleware(['auth:api']);
    Route::get('/flyer/list', [ApiController::class, 'getFlyers'])->middleware(['auth:api']);
    Route::post('/flyer/details', [ApiController::class, 'getFlyersDetails'])->middleware(['auth:api']);
    Route::post('/flyer/status/change', [ApiController::class, 'flyerStatusChange'])->middleware(['auth:api']);
    Route::get('/designed/by/list', [ApiController::class, 'getDesignedBy'])->middleware(['auth:api']);
    Route::get('/transaction/details', [ApiController::class, 'getTransactionDetails'])->middleware(['auth:api']);
    Route::get('/plan/history', [ApiController::class, 'getPlanHistory'])->middleware(['auth:api']);
    Route::post('/flyer/delete', [ApiController::class, 'deleteVendorFlyer'])->middleware(['auth:api']);
    
});
Route::prefix('customer')->group(function () { 
    Route::post('/login', [ApiController::class, 'customerLogin'])->name('customerLogin');
    Route::post('/registration', [ApiController::class, 'customerRegistration'])->name('customerRegistration');
    Route::post('/category/list', [ApiController::class, 'getCategoryList'])->middleware(['auth:api']);
    Route::post('subcategory/list', [ApiController::class, 'getSubCategoryLisByCatid'])->middleware(['auth:api']);
    Route::post('brand/list', [ApiController::class, 'getBrandList'])->middleware(['auth:api']);
    Route::post('model/list', [ApiController::class, 'getModelListByBrandId'])->middleware(['auth:api']);
    Route::post('add/to/cart', [ApiController::class, 'addToCart'])->middleware(['auth:api']);
    Route::get('cart/list', [ApiController::class, 'getCartList'])->middleware(['auth:api']);
    Route::post('cart/update', [ApiController::class, 'updateCart'])->middleware(['auth:api']);
    Route::post('cart/delete', [ApiController::class, 'deleteCartItem'])->middleware(['auth:api']);
    Route::any('order/create', [ApiController::class, 'createOrder'])->middleware(['auth:api']);
    Route::post('response', [ApiController::class, 'response'])->middleware(['auth:api']);
    Route::get('order/list', [ApiController::class, 'orderList'])->middleware(['auth:api']);
    Route::post('order/details', [ApiController::class, 'orderDetails'])->middleware(['auth:api']);
    Route::post('order/change/status', [ApiController::class, 'orderChangeStatus'])->middleware(['auth:api']);
    Route::post('order/list/status', [ApiController::class, 'orderListByStatus'])->middleware(['auth:api']);
    Route::get('/details', [ApiController::class, 'getCustomerDetails'])->middleware(['auth:api']);
    Route::get('/sliders', [ApiController::class, 'getSliders'])->middleware(['auth:api']);
    Route::post('address/save', [ApiController::class, 'saveAddress'])->middleware(['auth:api']);
    Route::get('address/list', [ApiController::class, 'getAddress'])->middleware(['auth:api']);
    Route::post('product/sample/list', [ApiController::class, 'getSampleProduct'])->middleware(['auth:api']);
    Route::get('support/details', [ApiController::class, 'getSupportDetails'])->middleware(['auth:api']);
    Route::get('policy/details', [ApiController::class, 'getPolicyDetails'])->middleware(['auth:api']);
    Route::get('token/detail', [ApiController::class, 'getTokenId']);
    Route::get('transaction/status', [ApiController::class, 'transactionRequest']);
});