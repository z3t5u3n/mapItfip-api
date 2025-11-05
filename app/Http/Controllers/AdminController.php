<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TablaUserAdmin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        Log::info('Datos recibidos en login:', $request->all());

        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Validación fallida', ['errors' => $validator->errors()]);
            return response()->json([
                'message' => 'Datos de entrada inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $password = $request->password;

        try {
            $admin = TablaUserAdmin::where('Contraseña', $password)->first();
            
            if($admin){
                Log::info('Login exitoso', ['admin_id' => $admin->Id]);
                return response()->json([
                    'success' => true,
                    'message' => 'Root encontrado',
                    'admin' => $admin,
                ], 200);
            } else {
                Log::warning('Credenciales incorrectas', ['password_provided' => $password]);
                return response()->json([
                    'success' => false,
                    'message' => 'Root no encontrado'
                ], 401);
            }

        } catch (\Exception $e) {
            Log::error('Error en el proceso de validacion de credenciales:', [
                'error' => $e->getMessage(), 
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Ocurrio un error en el servidor al intentar iniciar sesion'
            ], 500);
        }
    }

    /**
     * Obtener datos del administrador
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdminData()
    {
        try {
            // Obtener el primer administrador (ajusta según tu lógica de autenticación)
            $admin = TablaUserAdmin::first();
            
            if($admin){
                return response()->json([
                    'success' => true,
                    'admin' => $admin
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Administrador no encontrado'
                ], 404);
            }

        } catch (\Exception $e) {
            Log::error('Error al obtener datos del administrador:', [
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos del administrador'
            ], 500);
        }
    }

    /**
     * Actualizar datos del administrador
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAdminData(Request $request)
    {
        Log::info('Datos para actualizar:', $request->all());

        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:255',
            'Apellidos' => 'required|string|max:255',
            'CorreoIntitucional' => 'required|email|max:255',
            'Contraseña' => 'sometimes|string|min:6' // Opcional para actualizar
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Obtener el administrador (ajusta según tu lógica)
            $admin = TablaUserAdmin::first();
            
            if($admin){
                $updateData = [
                    'Nombre' => $request->Nombre,
                    'Apellidos' => $request->Apellidos,
                    'CorreoIntitucional' => $request->CorreoIntitucional,
                ];

                // Solo actualizar contraseña si se proporciona
                if ($request->has('Contraseña') && !empty($request->Contraseña)) {
                    $updateData['Contraseña'] = $request->Contraseña;
                }

                $admin->update($updateData);

                Log::info('Administrador actualizado exitosamente', ['admin_id' => $admin->Id]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Datos actualizados correctamente',
                    'admin' => $admin
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Administrador no encontrado'
                ], 404);
            }

        } catch (\Exception $e) {
            Log::error('Error al actualizar administrador:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar datos del administrador'
            ], 500);
        }
    }
}