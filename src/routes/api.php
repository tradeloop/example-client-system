<?php

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

Route::middleware('api')->group(function(){
    Route::namespace('\App\Http\Handlers')->group(function () {
        Route::prefix('data')->group(function () {
            Route::get('obits', AllObits::class)->name('api.obits');
            Route::get('devices', AllDevices::class)->name('api.devices');
        });

        Route::prefix('internal')
            ->group(function () {
                Route::post('usn', GenerateUsn::class)->name('generate.usn');

                Route::namespace('Obit')->prefix('obit')
                    ->group(function () {
                        Route::post('download', Download::class)->name('obit.download');
                        Route::post('device', MapToDevice::class)->name('obit.mapToDevice');
                    });

                Route::namespace('Device')->prefix('device')
                    ->group(function (){
                        Route::post('/', Save::class)->name('device.save');
                        Route::get('/{obit_did}', Get::class)->name('device.get');
                    });
            });
    });


    Route::get('/internal/devices', 'ServiceController@getDevices');
    Route::get('/internal/device/id/{device_id}', 'DeviceController@getDeviceWithId');
    Route::get('/internal/obit/{obit_did}', 'DeviceController@getObit');
    Route::get('/internal/blockchain/obit/{obit_did}', 'DeviceController@getBlockchainObit');
    Route::get('/internal/obit/{usn}/history', 'ServiceController@getObitHistoryByUsn');

    Route::post('internal/document/upload', 'DeviceController@uploadDocument');
    Route::post('internal/device/obit', 'DeviceController@createObit');
    Route::post('internal/obit/upload', 'DeviceController@uploadObit');
    Route::post('internal/device/metadata', 'ServiceController@saveDeviceMetadata');
    Route::post('internal/device/document', 'ServiceController@saveDeviceDocument');
    Route::post('internal/device/structured_data', 'ServiceController@saveDeviceStructuredData');

    Route::delete('/internal/device/{device_id}', 'ServiceController@removeDevice');
    Route::delete('/internal/device/{device_id}/metadata/{metadata_id}', 'ServiceController@removeDeviceMetadata');
    Route::delete('/internal/device/{device_id}/document/{document_id}', 'ServiceController@removeDeviceDocument');
    Route::delete('/internal/device/{device_id}/structured_data/{structured_data_id}', 'ServiceController@removeDeviceStructuredData');
});
