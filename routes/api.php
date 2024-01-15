<?php

use App\Http\Controllers\API\GuestbookController;
use App\Http\Middleware\JsonContentType;
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

/**
 * Routes for the guestbook API
 */
Route::group([
    'prefix' => '/guestbook',
    'as' => 'guestbook.',
], function () {

    Route::get('/', [GuestbookController::class, 'index'])->name('index');

    Route::get('/my', [GuestbookController::class, 'my'])->name('my');

    Route::get('/{entry}', [GuestbookController::class, 'get'])->name('get');

    Route::delete('/{entry}', [GuestbookController::class, 'delete'])->name('delete');

    Route::post('/sign', [GuestbookController::class, 'sign'])->name('sign')->middleware(JsonContentType::class);

    Route::post('/{entry}', [GuestbookController::class, 'update'])->name('update')->middleware('auth');
});
