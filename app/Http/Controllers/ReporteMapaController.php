<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TablaRegistroErroresMapa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReporteMapaController extends Controller
{

public function index()
{
    try {
        $reportes = TablaRegistroErroresMapa::with('usuario')
            ->orderBy('Fecha_Sys', 'desc')
            ->get()
            ->map(function ($r) {
                return [
                    'Usuario' => $r->usuario 
                        ? $r->usuario->Nombres . ' ' . $r->usuario->Apellidos 
                        : 'Desconocido',
                    'Error'   => $r->Error,
                    'Aspecto' => $r->Aspecto,
                    'Punto'   => $r->Punto,
                    'Fecha'   => $r->Fecha_Sys 
                        ? Carbon::parse($r->Fecha_Sys)->format('Y-m-d H:i:s')
                        : 'Sin fecha',
                ];
            });

        return response()->json([
            'success' => true,
            'reportes' => $reportes
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener reportes',
            'error'   => $e->getMessage()
        ], 500);
    }
}

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        $request->validate([
            'error' => 'required|string',
            'aspecto' => 'required|string',
            'punto' => 'nullable|string'
        ]);

        try {
            TablaRegistroErroresMapa::create([
                'IdUsuario' => Auth::user()->IdUsuario,
                'Error' => $request->error,
                'Aspecto' => $request->aspecto,
                'Punto' => $request->punto ?? 'No especificado',
                'Fecha_Sys' => now(),
            ]);

            return response()->json(['message' => 'Reporte enviado correctamente'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al guardar el reporte', 'error' => $e->getMessage()], 500);
        }
    }
}
