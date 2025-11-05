<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReporteMapaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas (no requieren autenticación)
Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/documentos', [DocumentController::class, 'index']);
Route::get('/user/historial', action: [UserController::class, 'historialRegistro']);
Route::get('/user/registroUsuario', action: [UserController::class, 'usuarioRegistro']);
Route::get('/user/estudiante', action: [UserController::class, 'usuarioEstudiante']);
Route::get('/user/profesor', action: [UserController::class, 'usuarioProfesor']);
Route::get('/user/carrera', action: [UserController::class, 'getCarreras']);
Route::get('/historial-registros', [UserController::class, 'historialRegistro']);
Route::get('/roles', [UserController::class, 'getRoles']);


// Rutas del administrador
Route::post('/userRoot', [AdminController::class, 'login']);
Route::get('/admin/data', [AdminController::class, 'getAdminData']);
Route::put('/admin/update', [AdminController::class, 'updateAdminData']);
Route::put('/usuarios/{id}/update', [AuthController::class, 'updateUser']);
Route::get('/consulta-error-mapa', [ReporteMapaController::class, 'index']);



// NUEVAS RUTAS PARA LA GESTIÓN DE USUARIOS
Route::get('/usuarios', [AuthController::class, 'getAllUsers']);
Route::get('/usuarios/stats', [AuthController::class, 'getUserStats']);
Route::put('/usuarios/{id}/toggle-activo', [AuthController::class, 'toggleUserActive']);

// Rutas protegidas por Sanctum (requieren un token de autenticación válido)
Route::middleware('auth:sanctum')->group(function () {
    // Ruta para obtener los datos del usuario autenticado (mantén la tuya existente)
    Route::get('/user', [UserController::class, 'showAuthenticatedUser']);
    Route::post('/reportar-error-mapa', action: [ReporteMapaController::class, 'store']);
    //
    Route::post('/verify-institutional-email', [UserController::class, 'verifyInstitutionalEmail']);
    Route::post('/solicitar-cambio-rol', [UserController::class, 'solicitarCambioRol']);
    Route::get('/activar-rol/{token}', [UserController::class, 'activarCambioRol']);
    //
    // Otras rutas API protegidas (ejemplo)
    Route::get('/interfaz-data', function (Request $request) {
        return response()->json(['message' => 'Datos de interfaz protegidos', 'user_id' => $request->user()->Id]);
    });

    // NUEVAS RUTAS para la funcionalidad de cambio de rol y activación
    Route::put('/user/update-role-and-data', [UserController::class, 'updateRoleAndData']);
    Route::get('/carreras', [UserController::class, 'getCarreras']);
    Route::get('/semestres', [UserController::class, 'getSemestres']);

    // Ruta de activación (aunque protegida por middleware en el grupo, el acceso es vía el enlace de email)
    // Es importante que la lógica del controlador maneje la seguridad del token.
    // Esta ruta se puede sacar del middleware 'auth:sanctum' si quieres que sea accesible sin token,
    // pero siempre validando el token enviado por URL dentro del controlador.
    // Por simplicidad, la he dejado dentro del grupo protegido como lo indicaste,
    // pero considera si debería ser una ruta pública con validación interna.
    Route::get('/activate-account/{token}', [UserController::class, 'activateAccount'])->name('activate.account');

    // Puedes añadir una ruta de logout si lo necesitas
    Route::post('/logout', [AuthController::class, 'logout']);
});