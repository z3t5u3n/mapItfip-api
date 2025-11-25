<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TablaUsuario; // Importa tu modelo de usuario
use App\Models\TablaRol;
use App\Models\TablaEstudiante;
use App\Models\TablaProfesor;

use App\Models\TablaHistorialRegistro; // ✅ Importa el modelo de la tabla de historial
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Maneja el registro de nuevos usuarios.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // 1. Validación de los datos
        $validator = Validator::make($request->all(), [
    'nombre' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/'],
    'apellidos' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/'],
    'documento' => 'required|numeric|unique:TablaUsuario,NumeroDocumento',
    'tipoDocumento' => 'required|exists:TablaDocumento,IdDocumento',
    'rol' => 'required|in:Externo,Estudiante,Profesor',
], [
    // Mensajes personalizados
    'nombre.required' => 'El nombre es obligatorio.',
    'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
    'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios.',
    'documento.required' => 'El número de documento es obligatorio.',
    'documento.numeric' => 'El documento debe contener solo números.',
    'documento.unique' => 'Este documento ya está registrado.',
    'tipoDocumento.required' => 'Debe seleccionar un tipo de documento.',
    'tipoDocumento.exists' => 'El tipo de documento seleccionado no es válido.',
    'rol.required' => 'Debe seleccionar un rol.',
    'rol.in' => 'El rol seleccionado no es válido.'
]);

        if ($validator->fails()) {
            Log::error('Error de validación en el registro:', $validator->errors()->toArray());
            return response()->json(['message' => 'No debe ir campos vacios', 'errors' => $validator->errors()], 422);
        }

        // Mapear el rol de texto a IdRol
        $idRol = 1;
        if ($request->rol === 'Estudiante') {
            $idRol = 2;
        } elseif ($request->rol === 'Profesor') {
            $idRol = 3;
        }

        $idUsuario = Str::random(10);

        try {
            // 2. Crear el nuevo usuario en la base de datos
            $usuario = TablaUsuario::create([
                'IdUsuario' => $idUsuario,
                'Nombres' => $request->nombre,
                'Apellidos' => $request->apellidos ?? '',
                'IdDocumento' => (int) $request->tipoDocumento,
                'NumeroDocumento' => (int) $request->documento,
                'IdRol' => (int) $idRol,
                'IdActivo' => 1,
                'Fecha_Sys' => now(),
            ]);

            Log::info('Usuario registrado exitosamente:', $usuario->toArray());
            return response()->json(['message' => 'Usuario registrado exitosamente!', 'user' => $usuario], 201);

        } catch (\Exception $e) {
            Log::error('Error al registrar usuario:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Hubo un error al registrar el usuario.'], 500);
        }
    }

    /**
     * Maneja el inicio de sesión de usuarios.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'documento' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Número de documento es requerido y debe ser numérico.', 'errors' => $validator->errors()], 422);
        }

        $numeroDocumento = $request->documento;

        try {
            $usuario = TablaUsuario::where('NumeroDocumento', $numeroDocumento)
                ->where('IdActivo', 1)
                ->first();

            if ($usuario) {
                // ✅ Nuevo código para registrar el inicio de sesión en la tabla de historial
                TablaHistorialRegistro::create([
                    'IdUsuario' => $usuario->IdUsuario,
                    'Fecha_Sys' => now(),
                ]);

                // Generar un token para el usuario con Laravel Sanctum
                $token = $usuario->createToken('auth_token')->plainTextToken;

                Log::info('Login exitoso:', ['NumeroDocumento' => $numeroDocumento, 'IdUsuario' => $usuario->IdUsuario]);
                return response()->json([
                    'message' => 'Usuario encontrado',
                    'user' => $usuario,
                    'token' => $token,
                ], 200);
            } else {
                Log::info('Intento de login fallido: Documento no encontrado o inactivo', ['NumeroDocumento' => $numeroDocumento]);
                return response()->json(['message' => 'Usuario no registrado o inactivo.'], 404);
            }

        } catch (\Exception $e) {
            Log::error('Error en el proceso de login:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'message'

                => 'Ocurrió un error en el servidor al intentar iniciar sesión.'
            ], 500);
        }
    }
    //////////////////////
    // En AuthController.php - Asegúrate de que existan estos métodos:

    /**
     * Obtiene todos los usuarios para la gestión del administrador
     */
    public function getAllUsers()
    {
        try {
            $usuarios = TablaUsuario::with(['rol', 'documento', 'estudiante', 'profesor'])
                ->get()
                ->map(function ($usuario) {

                    // Definir correo según el rol
                    $correo = null;
                    if ($usuario->IdRol == 2 && $usuario->estudiante) {
                        $correo = $usuario->estudiante->CorreoIntitucional;
                    }
                    if ($usuario->IdRol == 3 && $usuario->profesor) {
                        $correo = $usuario->profesor->CorreoIntitucional;
                    }

                    return [
                        'IdUsuario' => $usuario->IdUsuario,
                        'Nombres' => $usuario->Nombres,
                        'Apellidos' => $usuario->Apellidos,
                        'NumeroDocumento' => $usuario->NumeroDocumento,
                        'TipoDocumento' => $usuario->documento->TipoDocumento ?? 'No definido',
                        'TipoRol' => $usuario->rol->TipoRol ?? 'Sin Rol',
                        'CorreoInstitucional' => $correo,
                        'Activo' => $usuario->IdActivo,
                        'Fecha_Sys' => $usuario->Fecha_Sys,
                    ];
                });

            return response()->json([
                'success' => true,
                'usuarios' => $usuarios
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error en getAllUsers: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene estadísticas de usuarios
     */
    public function getUserStats()
    {
        try {
            $totalUsuarios = TablaUsuario::count();
            $usuariosActivos = TablaUsuario::where('IdActivo', 1)->count();
            $usuariosInactivos = TablaUsuario::where('IdActivo', 0)->count();

            // Estadísticas por rol
            $roles = TablaRol::withCount([
                'usuarios' => function ($query) {
                    $query->where('IdActivo', 1);
                }
            ])->get();

            return response()->json([
                'success' => true,
                'stats' => [
                    'total' => $totalUsuarios,
                    'activos' => $usuariosActivos,
                    'inactivos' => $usuariosInactivos,
                    'porRol' => $roles
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ], 500);
        }
    }

    /**
     * Cambia el estado activo/inactivo de un usuario
     */
    public function toggleUserActive($id)
    {
        try {
            $usuario = TablaUsuario::where('IdUsuario', $id)->first();

            if ($usuario) {
                $usuario->IdActivo = $usuario->IdActivo == 1 ? 0 : 1;
                $usuario->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Estado actualizado correctamente',
                    'activo' => $usuario->IdActivo == 1
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del usuario:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado del usuario'
            ], 500);
        }
    }
    // En AuthController.php - agrega este método
    /**
     * Actualiza los datos de un usuario
     */
    public function updateUser($id, Request $request)
    {
        try {
            $usuario = TablaUsuario::where('IdUsuario', $id)->first();

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'Nombres' => 'required|string|max:255',
                'Apellidos' => 'required|string|max:255',
                'CorreoIntitucional' => 'required|email|max:255',
                'TipoRol' => 'required|in:Estudiante,Profesor,Externo'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar el usuario
            $usuario->update([
                'Nombres' => $request->Nombres,
                'Apellidos' => $request->Apellidos,
            ]);

            // Actualizar correo institucional según el tipo de usuario
            if ($request->TipoRol === 'Estudiante') {
                $estudiante = TablaEstudiante::where('IdUsuario', $id)->first();
                if ($estudiante) {
                    $estudiante->update([
                        'CorreoIntitucional' => $request->CorreoIntitucional
                    ]);
                }
            } elseif ($request->TipoRol === 'Profesor') {
                $profesor = TablaProfesor::where('IdUsuario', $id)->first();
                if ($profesor) {
                    $profesor->update([
                        'CorreoIntitucional' => $request->CorreoIntitucional
                    ]);
                }
            }

            Log::info('Usuario actualizado exitosamente:', ['usuario_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado correctamente'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al actualizar usuario:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el usuario'
            ], 500);
        }
    }


}
