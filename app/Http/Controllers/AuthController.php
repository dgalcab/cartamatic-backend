<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Registro de usuario
    public function register(Request $request)
    {
        // Validación de los datos de registro
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Creación del usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Hasheamos la contraseña
        ]);

        // Creación de token usando Laravel Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // Respuesta en formato JSON con el token de acceso
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201); // Código 201 para indicar que el recurso fue creado
    }

    // Inicio de sesión
    public function login(Request $request)
    {
        // Intento de autenticación usando los datos de inicio de sesión
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Si la autenticación falla, devolvemos un error 401
            return response()->json(['message' => 'Credenciales incorrectas.'], 401);
        }

        // Si la autenticación es exitosa, obtenemos el usuario autenticado
        $user = Auth::user();

        // Creamos un nuevo token de autenticación
        $token = $user->createToken('auth_token')->plainTextToken;

        // Devolvemos el token, el tipo de token y los datos del usuario (incluyendo el id)
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,         // Asegúrate de que el ID del usuario sea devuelto
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        // Eliminamos todos los tokens del usuario autenticado
        $request->user()->tokens()->delete();

        // Devolvemos una respuesta indicando que el cierre de sesión fue exitoso
        return response()->json(['message' => 'Successfully logged out']);
    }
}
