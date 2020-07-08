<?php

use App\Http\Controllers\Api;
use Illuminate\Http\Response;
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

Route::get(
    '/health',
    function () {
        return response()->json(
            [
                'version' => env('CI_COMMIT_TAG', 'dev'),
                'sha' => env('CI_COMMIT_SHA', 'dev'),
            ],
            Response::HTTP_OK
        );
    }
);

Route::post(
    '/flatten/{flatFilename}',
    Api\FlattenController::class
);

Route::post(
    '/unflatten/{unflatFilename}',
    Api\UnflattenController::class
);

Route::post(
    '/convert/{convertedFilename}',
    Api\ConvertController::class
);
