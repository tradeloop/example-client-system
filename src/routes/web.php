<?php

use App\Http\Handlers\Devices\Documents\Store;
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

Route::get('/', 'SiteController@welcome');
//Route::get('/devices/{obit_did}', 'SiteController@deviceObitDetail');
Route::get('/devices/{device_id}/edit', 'SiteController@editDevice');

//Route::get('/obits', 'SiteController@obitsList');
//Route::get('/obits/{obit_id}', 'SiteController@obitDetail');
Route::get('/retrieve/obit', 'SiteController@retrieveObit');

Route::namespace('\App\Http\Handlers\Obits')
    ->name('obits.')
    ->prefix('obits')
    ->group(function () {
        Route::get('/', \Index::class)->name('index');
        Route::get('/load-all', \LoadAll::class)->name('load-all');
        Route::post('/', \Store::class)->name('store');
        Route::get('/{key}', \Show::class)->name('show');
        Route::get('/{key}/load', \Load::class)->name('load');
        Route::get('/{key}/to-chain', \ToChain::class)->name('to-chain');
        Route::get('/{key}/from-chain', \FromChain::class)->name('from-chain');
    });

Route::namespace('\App\Http\Handlers\Devices')
    ->name('devices.')
    ->prefix('devices')
    ->group(function () {
        Route::get('/create', Create::class)->name('create');
        Route::get('/load-all', LoadAll::class)->name('load-all');
        Route::get('/{usn}', Show::class)->name('show');
        Route::get('/{usn}/load', Load::class)->name('load');
        Route::get('/', Index::class)->name('index');
        Route::post('/', Save::class)->name('save');

        Route::namespace('Documents')
            ->name('documents.')
            ->prefix('documents')
            ->group(function () {
                Route::post('/', Store::class)->name('store');
            });
    });

Route::namespace('\App\Http\Handlers\Generate')
    ->name('generate.')
    ->prefix('generate')
    ->group(function () {

        // USN generation tool routes
        Route::namespace('Usn')
            ->name('usn.')
            ->prefix('usn')
            ->group(function () {
                Route::get('/', Index::class)->name('index');
                Route::post('/', Compute::class)->name('compute');
            });

        // Checksum generation tool routes
        Route::namespace('Checksum')
            ->name('checksum.')
            ->prefix('checksum')
            ->group(function () {
                Route::get('/', Index::class)->name('index');
                Route::post('/', Compute::class)->name('compute');
            });
    });



