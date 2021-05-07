<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($hostname) {
    Route::domain($hostname->fqdn)->group(function() {
        Route::middleware('auth:api')->group(function() {
            Route::post('documents', 'Tenant\Api\DocumentController@store');
//            Route::get('services/ruc/{number}', 'Tenant\Api\ServiceController@ruc');
//            Route::get('services/dni/{number}', 'Tenant\Api\ServiceController@dni');
        });
        Route::post('services/validate_cpe', 'Tenant\Api\ServiceController@validateCpe');
        Route::post('services/consult_status', 'Tenant\Api\ServiceController@consultStatus');
        Route::post('services/consult_cdr_status', 'Tenant\Api\ServiceController@consultCdrStatus');

    });
}