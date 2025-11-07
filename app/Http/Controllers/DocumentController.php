<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TablaDocumento;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    /**
     * Obtiene todos los tipos de documento.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $documentos = TablaDocumento::all();
            Log::info('Documentos extraídos:', $documentos->toArray());
            return response()->json($documentos, 200);
        } catch (\Exception $e) {
            // ✅ CÓDIGO DE DEPURACIÓN: Devuelve el mensaje de error exacto de la DB.
            Log::error('Error al extraer documentos:', [
                'error' => $e->getMessage(), 
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error al cargar tipos de documento.',
                'debug_error' => $e->getMessage() // <--- LÍNEA DE DEPURACIÓN
            ], 500);
        }
    }
}
