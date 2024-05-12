<?php

namespace App\Http\Controllers;

use App\Models\type;
use Illuminate\Http\Request;

class typeController extends Controller
{
    public function index()
    {
        return type::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
        ]);

        return type::create($data);
    }

    public function show(type $type)
    {
        return $type;
    }

    public function update(Request $request, type $type)
    {
        $data = $request->validate([
            'name' => ['required'],
        ]);

        $type->update($data);

        return $type;
    }

    public function destroy(type $type)
    {
        $type->delete();

        return response()->json();
    }
}
