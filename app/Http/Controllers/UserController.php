<?php

namespace App\Http\Controllers;

use App\Models\TablaHistorialRegistro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\TablaUsuario;
use App\Models\TablaEstudiante;
use App\Models\TablaProfesor;
use App\Models\TablaCarrera;
use App\Models\TablaSemestre;
use App\Models\TablaRol;
use Illuminate\Support\Facades\Log; // Añadido para logging

class UserController extends Controller
{
    // ===================================
    // 🔹 Mostrar usuario autenticado
    // ===================================
    public function showAuthenticatedUser(Request $request)
    {
        return response()->json($request->user()->load('documento', 'rol'));
    }

    // ===================================
    // 🔹 Rutas de Consulta con manejo de errores (Corregidas)
    // ===================================

    public function historialRegistro()
    {
        try {
            $historial = TablaHistorialRegistro::with('usuario')
                ->orderBy('Fecha_Sys', 'desc')
                ->get();
            return response()->json($historial, 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener historial de registro: ' . $e->getMessage());
            return response()->json(['message' => 'Error al cargar historial de registro.'], 500);
        }
    }

    public function usuarioRegistro()
    {
        try {
            return response()->json(TablaUsuario::all(), 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener usuarios registrados: ' . $e->getMessage());
            return response()->json(['message' => 'Error al cargar usuarios.'], 500);
        }
    }

    public function usuarioEstudiante()
    {
        try {
            return response()->json(TablaEstudiante::all(), 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener usuarios estudiantes: ' . $e->getMessage());
            return response()->json(['message' => 'Error al cargar estudiantes.'], 500);
        }
    }

    public function usuarioProfesor()
    {
        try {
            return response()->json(TablaProfesor::all(), 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener usuarios profesores: ' . $e->getMessage());
            return response()->json(['message' => 'Error al cargar profesores.'], 500);
        }
    }

    public function getCarreras()
    {
        // Esta es la ruta que fallaba: ahora con try-catch robusto
        try {
            return response()->json(TablaCarrera::all(), 200); 
        } catch (\Exception $e) {
            // Loguea el error real y devuelve 500.
            Log::error('Error al obtener carreras: ' . $e->getMessage());
            return response()->json(['message' => 'Error al cargar carreras.'], 500);
        }
    }

    public function getSemestres()
    {
        // Esta es la ruta que fallaba: ahora con try-catch robusto
        try {
            return response()->json(TablaSemestre::all(), 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener semestres: ' . $e->getMessage());
            return response()->json(['message' => 'Error al cargar semestres.'], 500);
        }
    }
    
    public function getRoles()
    {
        try {
            $roles = TablaRol::all();
            return response()->json($roles, 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching roles: ' . $e->getMessage());
            return response()->json(['message' => 'Error cargando roles'], 500);
        }
    }

    // ============================================================
    // 🔹 Actualiza el rol y envía correo de activación (pendiente)
    // ============================================================
    public function updateRoleAndData(Request $request)
    {
        // El try-catch en esta función ya estaba bien implementado
        $request->validate([
            'userId' => 'required|string|exists:TablaUsuario,IdUsuario',
            'newRoleId' => 'required|integer|exists:TablaRol,IdRol',
            'roleSpecificData' => 'nullable|array',
        ]);

        $user = TablaUsuario::where('IdUsuario', $request->userId)->first();
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        // Bloquear si ya tiene token activo (espera 24h)
        if ($user->activation_token && Carbon::parse($user->activation_expires_at)->isFuture()) {
            return response()->json([
                'message' => 'Ya tienes un cambio de rol pendiente. Espera 24 horas antes de solicitar otro.'
            ], 403);
        }

        $newRoleId = $request->newRoleId;
        $roleSpecificData = $request->roleSpecificData ?? [];

        DB::beginTransaction();
        try {
            // VALIDACIONES SEGÚN ROL
            if ($newRoleId === 2) { // Estudiante
                $request->validate([
                    'roleSpecificData.CorreoInstitucional' => 'required|email|regex:/^[\w\.-]+@itfip\.edu\.co$/',
                    'roleSpecificData.IdCarrera' => 'required|integer|exists:TablaCarrera,IdCarrera',
                    'roleSpecificData.IdSemestre' => 'required|integer|exists:TablaSemestre,IdSemestre',
                ]);

                TablaEstudiante::updateOrCreate(
                    ['IdUsuario' => $user->IdUsuario],
                    [
                        'IdRol' => $newRoleId,
                        'Activo' => 0, // Desactivado hasta confirmar
                        'IdCarrera' => $roleSpecificData['IdCarrera'],
                        'IdSemestre' => $roleSpecificData['IdSemestre'],
                        'CorreoInstitucional' => $roleSpecificData['CorreoInstitucional'],
                        'Fecha_Sys' => now(),
                    ]
                );
            } elseif ($newRoleId === 3) { // Profesor / Administrativo
                $request->validate([
                    'roleSpecificData.CorreoInstitucional' => 'required|email|regex:/^[\w\.-]+@itfip\.edu\.co$/',
                ]);

                TablaProfesor::updateOrCreate(
                    ['IdUsuario' => $user->IdUsuario],
                    [
                        'IdRol' => $newRoleId,
                        'Activo' => 0, // Desactivado hasta confirmar
                        'CorreoInstitucional' => $roleSpecificData['CorreoInstitucional'],
                        'Fecha_Sys' => now(),
                    ]
                );
            }

            // Generar token de activación
            $user->activation_token = Str::random(60);
            $user->activation_expires_at = Carbon::now()->addHours(24);
            $user->save();

            // Enviar correo de activación
            $correo = $roleSpecificData['CorreoInstitucional'] ?? null;
            if ($correo) {
                $enlace = url("/api/activar-rol/{$user->activation_token}?nuevo_rol={$newRoleId}");

                Mail::raw(
                    "Hola {$user->Nombres},\n\nHaz clic en el siguiente enlace para confirmar tu cambio de rol:\n\n{$enlace}\n\nEste enlace expirará en 24 horas.\n\nAtentamente,\nEquipo MAPITFIP",
                    function ($mensaje) use ($correo) {
                        $mensaje->to($correo)
                            ->subject('Confirmación de cambio de rol - MAPITFIP');
                    }
                );
            }

            DB::commit();

            return response()->json([
                'message' => 'Se envió un correo de confirmación al correo institucional. Debes activarlo en menos de 24 horas.',
                'activation_required' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar el rol y los datos: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar el rol y los datos: ' . $e->getMessage()], 500);
        }
    }

    // ============================================================
    // 🔹 Activar el cambio de rol desde el enlace de correo
    // ============================================================
    public function activarCambioRol(Request $request, $token)
    {
        // No requiere try-catch extenso, la lógica principal es la búsqueda.
        $usuario = TablaUsuario::where('activation_token', $token)->first();

        if (!$usuario) {
            return response()->json(['message' => 'Token inválido o ya usado.'], 404);
        }

        // Si expiró el token → revierte a Externo y desactiva registros
        if (Carbon::parse($usuario->activation_expires_at)->isPast()) {
            TablaEstudiante::where('IdUsuario', $usuario->IdUsuario)->update(['Activo' => 0]);
            TablaProfesor::where('IdUsuario', $usuario->IdUsuario)->update(['Activo' => 0]);

            $usuario->IdRol = 1; // Externo
            $usuario->activation_token = null;
            $usuario->activation_expires_at = null;
            $usuario->save();

            return response()->json([
                'message' => 'El enlace ha expirado. Tu rol se ha restablecido a Externo y el registro fue desactivado.'
            ], 410);
        }

        $nuevoRol = (int) $request->query('nuevo_rol');
        if (!in_array($nuevoRol, [2, 3])) {
            return response()->json(['message' => 'Rol inválido.'], 400);
        }

        // Activar rol confirmado
        $usuario->IdRol = $nuevoRol;
        $usuario->activation_token = null;
        $usuario->activation_expires_at = Carbon::now()->addHours(24); // Espera 24 h para otro cambio
        $usuario->save();

        if ($nuevoRol === 2) {
            TablaEstudiante::where('IdUsuario', $usuario->IdUsuario)->update(['Activo' => 1]);
        } elseif ($nuevoRol === 3) {
            TablaProfesor::where('IdUsuario', $usuario->IdUsuario)->update(['Activo' => 1]);
        }

        return response()->json([
            'message' => 'El cambio de rol ha sido activado correctamente.',
            'nuevo_rol' => $nuevoRol
        ]);
    }

    // ============================================================
    // 🔹 Verificar correo institucional
    // ============================================================
    public function verifyInstitutionalEmail(Request $request)
    {
        $correo = $request->input('correo');
        if (!$correo) {
            return response()->json(['active' => false, 'message' => 'No se proporcionó un correo.'], 400);
        }

        if (!str_ends_with(strtolower($correo), '@itfip.edu.co')) {
            return response()->json(['active' => false, 'message' => 'El correo no pertenece al dominio institucional.'], 200);
        }

        try {
             $usuario = TablaUsuario::where('NumeroDocumento', Auth::user()->NumeroDocumento ?? null)->first();
             if (!$usuario) {
                 return response()->json(['active' => false, 'message' => 'No se encontró el usuario autenticado.'], 404);
             }
             return response()->json(['active' => true, 'message' => 'El correo institucional es válido y activo.'], 200);
         } catch (\Exception $e) {
             Log::error('Error al verificar email institucional: ' . $e->getMessage());
             return response()->json(['message' => 'Error interno al verificar correo.'], 500);
         }
    }
}