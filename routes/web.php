<?php

use App\Http\Controllers\CuttingController;
use App\Http\Controllers\DispatchController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MillingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderProcessCardController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TurningController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::redirect('/', 'login', 301);

/* Auth Routes */
Auth::routes(['register' => false, 'reset' => false, 'verify' => false, 'confirm' => false]);
/* Auth Routes */

/* Common Routes */
Route::middleware('auth')->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::get('order-process-card', [OrderProcessCardController::class, 'index'])->name('order-process-card.index');

    /* Role routes */
    Route::get('role/check-name-unique/{role?}', [RoleController::class, 'checkNameUnique'])->name('role.check-name-unique');
    Route::resource('role', RoleController::class)->except(['show']);
    /* Role routes */

    /* User routes */
    Route::get('user/check-email-unique/{user?}', [UserController::class, 'checkEmailUnique'])->name('user.check-email-unique');
    Route::get('user/check-phone-unique/{user?}', [UserController::class, 'checkPhoneUnique'])->name('user.check-phone-unique');
    Route::resource('user', UserController::class)->except(['show']);
    /* User routes */

    /* Order routes */
    Route::get('order/check-work-order-no-unique/{order?}', [OrderController::class, 'checkWorkOrderNoUnique'])->name('order.check-work-order-no-unique');
    Route::get('order/import', [OrderController::class, 'import_orders'])->name('import.orders');
    Route::post('order/import', [OrderController::class, 'import_orders_store'])->name('import.orders.store');
    Route::get('order/{order}/make/finish', [OrderController::class, 'finish'])->name('order.make.finish');
    Route::get('order/{order}/make/unfinish', [OrderController::class, 'unfinish'])->name('order.make.unfinish');
    Route::resource('order', OrderController::class)->except(['show']);
    /* Order routes */

    /* Cutting routes */
    Route::resource('cutting', CuttingController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::post('cutting/import', [CuttingController::class, 'import_store'])->name('cutting.import.store');
    /* Cutting routes */

    /* Turning routes */
    Route::resource('turning', TurningController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::post('turning/import', [TurningController::class, 'import_store'])->name('turning.import.store');
    /* Turning routes */

    /* Milling routes */
    Route::resource('milling', MillingController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::post('milling/import', [MillingController::class, 'import_store'])->name('milling.import.store');
    /* Milling routes */

    /* Other routes */
    Route::resource('other', OtherController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::post('other/import', [OtherController::class, 'import_store'])->name('other.import.store');
    /* Other routes */

    /* Dispatch routes */
    Route::resource('dispatch', DispatchController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::post('dispatch/import', [DispatchController::class, 'import_store'])->name('dispatch.import.store');
    /* Dispatch routes */

    /* Profile */
    Route::post('profile/update-password', [ProfileController::class, 'update_password'])->name('profile.update-password');
    Route::resource('profile', ProfileController::class)->only(['index', 'update']);
    /* Profile */
});
/* Common Routes */

/* Global Routes Without Auth */
Route::get('states', function (Request $request) {
    return response()->json(["status" => true, "data" => getStates($request->id)]);
})->name("globals.states");

Route::get('cities', function (Request $request) {
    return response()->json(["status" => true, "data" => getCities($request->id)]);
})->name("globals.cities");
/* Global Routes Without Auth */
