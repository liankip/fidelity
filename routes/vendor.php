<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestForQuotationController;
use App\Http\Controllers\Supplier\VendorRegsitrationController;

Route::domain('vendor.' . env('APP_URL'))->group(function () {
    Route::get('/', VendorRegsitrationController::class)->name('vendors.register');
    Route::post('register', [VendorRegsitrationController::class, 'store'])->name('vendors.store');
    Route::get('register-success', [VendorRegsitrationController::class, 'success'])->name('vendors.register.success');

    Route::get('penawaran-harga/{id}', RequestForQuotationController::class)->name('request-for-quotation');
    Route::post('penawaran-harga/{id}', [RequestForQuotationController::class, 'store'])->name('request-for-quotation.store');


    /*
    |--------------------------------------------------------------------------
    | Disabled Routes
    |--------------------------------------------------------------------------
    |
    | Route::middleware(['auth'])->group(function () {
    |    Route::get('dashboard', VendorDashboardController::class)->name('vendors.dashboard');
    |    Route::get('profile', [VendorCRUDController::class, 'profile'])->name('vendors.profile');
    |    Route::patch('profile', [VendorCRUDController::class, 'updateProfile'])->name('vendors.profile.update');
    |    Route::get('items', VendorItems::class)->name('vendors.items');
    |    Route::post('items', [VendorItems::class, 'store'])->name('vendors.items.store');
    | });
    |
    */
});
