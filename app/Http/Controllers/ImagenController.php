<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Imagen;
use Inertia\Inertia;

class ImagenController extends Controller
{
    // Listar imágenes, opcionalmente filtradas por restaurante
    public function index(Request $request)
    {
        if ($request->has('restaurant_id')) {
            $imagenes = Imagen::where('restaurant_id', $request->restaurant_id)->get();
        } else {
            $imagenes = Imagen::all();
        }
        return response()->json($imagenes);
    }

    // Guardar una imagen asociada a un restaurante
    public function store(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|max:2048',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        $path = $request->file('imagen')->store('public/imagenes');
        $rutaPublica = str_replace('public/', 'storage/', $path);

        Imagen::create([
            'restaurant_id' => $request->restaurant_id,
            'ruta' => $rutaPublica
        ]);

        return response()->json(['success' => true]);
    }
}

