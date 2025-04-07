<?php

use App\Http\Controllers\Api\GetItems;
use App\Http\Controllers\Api\GetItemsSelectTo;
use App\Http\Controllers\Api\GetUsers;
use App\Http\Controllers\Api\Items;
use App\Http\Controllers\Api\ItemUnits;
use App\Http\Controllers\Api\PurchaseOrders;
use App\Http\Controllers\GanttControlller;
use App\Http\Controllers\LinkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/getitems", [GetItems::class, "index"]);
Route::get('/getallitems', [GetItems::class, "getAllItems"]);
Route::get('/get-item-price/{itemId}', [GetItems::class, "getItemPrice"]);
Route::get("/getitemsselect2/{project}", [GetItemsSelectTo::class, "index"]);
Route::get("/getusers", [GetUsers::class, "index"]);
Route::get('/units/select', [ItemUnits::class, 'select'])->name('units.select');
Route::get("/purchaseorders", [PurchaseOrders::class, "getSelect"])->name("purchase-orders.getselect");

Route::prefix('v1')->as('v1:')->group(static function (): void {
    Route::prefix('items')->as('items:')->group(static function (): void {
        Route::get('/', [Items::class, 'index'])->name('items.index');
        Route::get('/dropdown', [Items::class, 'dropdown'])->name('items.dropdown');
    });
});

Route::get('task/{id}', [GanttControlller::class, 'get']);
Route::resource('task', GanttControlller::class);
Route::resource('link', LinkController::class);