<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
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
Route::get('/build/{path}', function ($path) {
    $file = public_path('build/' . $path);
    if (file_exists($file)) {
        return response()->file($file);
    }
    abort(404);
})->where('path', '.*');

Route::get('/Unity/Build/{file}', function ($file) {
    $path = public_path("Unity/Build/{$file}");
    if (!File::exists($path)) {
        abort(404);
    }

    $extension = pathinfo($file, PATHINFO_EXTENSION);

    $mime = 'application/octet-stream';
    if (str_ends_with($file, '.js.br')) {
        $mime = 'application/javascript';
    } elseif (str_ends_with($file, '.wasm.br')) {
        $mime = 'application/wasm';
    } elseif (str_ends_with($file, '.data.br')) {
        $mime = 'application/octet-stream';
    }

    $response = response(File::get($path), 200)
        ->header('Content-Type', $mime)
        ->header('Cache-Control', 'public, max-age=31536000')
        ->header('Content-Encoding', 'br'); // 🔥 importante

    return $response;
});
///////////////////////
Route::get('/', function () {
    return view('welcome');
});
