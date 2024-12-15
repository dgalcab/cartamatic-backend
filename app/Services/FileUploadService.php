<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    // Función para subir archivos
    public static function upload($file, $directory)
    {
        $fileName = $file->getClientOriginalName();  // Obtener el nombre original del archivo
        $path = $file->storeAs($directory, $fileName, 'public');  // Subir el archivo
        return $path;  // Devolver la ruta del archivo
    }

    // Función para eliminar archivos
    public static function delete($filePath)
    {
        if ($filePath) {
            Storage::disk('public')->delete($filePath);  // Eliminar el archivo si existe
        }
    }
}

