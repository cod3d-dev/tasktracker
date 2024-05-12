<?php

namespace App\Http\Controllers;

use App\Models\proyect;
use Illuminate\Http\Request;

class proyectController extends Controller
{
    public function index()
    {
        return proyect::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
        ]);

        return proyect::create($data);
    }

    public function show(proyect $proyect)
    {
        return $proyect;
    }

    public function update(Request $request, proyect $proyect)
    {
        $data = $request->validate([
            'name' => ['required'],
        ]);

        $proyect->update($data);

        return $proyect;
    }

    public function destroy(proyect $proyect)
    {
        $proyect->delete();

        return response()->json();
    }
}
