<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Este archivo ahora estará vacío, ya que el frontend se sirve por separado.
| Todas las rutas de la API están en routes/api.php
|
*/

// VACÍO. NO DEBES TENER NINGUNA RUTA AQUÍ.
Route::get('/unity-build/{file}', function ($file) {
    // Apuntamos a donde decidas almacenar temporalmente los archivos o los consumes por URL
    $path = public_path('Unity/Build/' . $file); 
    
    if (!File::exists($path)) {
        abort(404);
    }

    $fileContent = File::get($path);
    $response = Response::make($fileContent, 200);

    // Inyectamos las cabeceras nativas para que el navegador móvil lo procese al instante
    if (str_ends_with($file, '.br')) {
        $response->header('Content-Encoding', 'br');
    }

    if (str_ends_with($file, '.js.br') || str_ends_with($file, '.js')) {
        $response->header('Content-Type', 'application/javascript');
    } elseif (str_ends_with($file, '.wasm.br') || str_ends_with($file, '.wasm')) {
        $response->header('Content-Type', 'application/wasm');
    } elseif (str_ends_with($file, '.data.br') || str_ends_with($file, '.data')) {
        $response->header('Content-Type', 'application/octet-stream');
    }

    // Cacheamos agresivamente por 1 año para que el celular no lo vuelva a descargar nunca más
    $response->header('Cache-Control', 'public, max-age=31536000, immutable');

    return $response;
});
