<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // Captura las excepciones específicas para la API y devuelve respuestas JSON personalizadas
        $this->renderable(function (ModelNotFoundException $e, $request) {
            // Respuesta JSON cuando el modelo no se encuentra
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Recurso no encontrado'
                ], Response::HTTP_NOT_FOUND);
            }
        });

        $this->renderable(function (AuthorizationException $e, $request) {
            // Respuesta JSON cuando el usuario no está autorizado
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'No autorizado'
                ], Response::HTTP_FORBIDDEN);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            // Respuesta JSON para rutas no encontradas
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Ruta no encontrada'
                ], Response::HTTP_NOT_FOUND);
            }
        });

        // Otras excepciones pueden ser manejadas aquí...
    }
}
