<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Imagen;

class ImagenController extends Controller
{
    // Listar imágenes filtradas por restaurante
    public function index(Request $request)
    {
        $restaurantId = $request->query('restaurant_id');

        if ($restaurantId) {
            $imagenes = Imagen::where('restaurant_id', $restaurantId)->get();
        } else {
            $imagenes = Imagen::all();
        }

        return response()->json($imagenes);
    }

    // Crear una nueva imagen
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'ruta' => 'required|string|max:255',
        ]);

        $imagen = Imagen::create($validatedData);

        return response()->json($imagen, 201);
    }

    // Eliminar una imagen
    public function destroy($id)
    {
        $imagen = Imagen::findOrFail($id);
        $imagen->delete();

        return response()->json(['message' => 'Imagen eliminada correctamente.'], 200);
    }
}
