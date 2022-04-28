<?php

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
Route::get('/devices', 'SiteController@deviceList');
Route::get('/devices/{obit_did}', 'SiteController@deviceObitDetail');
Route::get('/devices/{device_id}/edit', 'SiteController@editDevice');

Route::get('/obits', 'SiteController@obitsList');
Route::get('/obits/{obit_id}', 'SiteController@obitDetail');

Route::get('/generate/hashing', 'SiteController@generateHashing');
Route::get('/retrieve/obit', 'SiteController@retrieveObit');

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
        }


    });



