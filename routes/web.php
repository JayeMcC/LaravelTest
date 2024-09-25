<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/swagger/openapi.yml', function () {
    $path = resource_path('swagger/openapi.yml');
    return Response::file($path, [
        'Content-Type' => 'application/x-yaml',
    ]);
});

Route::get('/swagger/{filename}', function ($filename) {
    $path = resource_path('swagger/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return Response::file($path, [
        'Content-Type' => 'application/x-yaml',
    ]);
})->where('filename', '.*');
