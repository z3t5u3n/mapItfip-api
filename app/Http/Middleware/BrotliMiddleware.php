<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BrotliMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $path = public_path($request->path());

        if (file_exists($path) && str_ends_with($path, '.br')) {
            $response->headers->set('Content-Encoding', 'br');

            if (str_ends_with($path, '.js.br')) {
                $response->headers->set('Content-Type', 'application/javascript');
            } elseif (str_ends_with($path, '.wasm.br')) {
                $response->headers->set('Content-Type', 'application/wasm');
            }
        }

        return $response;
    }
}
