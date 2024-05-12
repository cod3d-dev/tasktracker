<?php

namespace App\Http\Controllers;

use App\Models\project;
use Illuminate\Http\Request;

class projectController extends Controller
{
    public function index()
    {
        return project::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
        ]);

        return project::create($data);
    }

    public function show(project $project)
    {
        return $project;
    }

    public function update(Request $request, project $project)
    {
        $data = $request->validate([
            'name' => ['required'],
        ]);

        $project->update($data);

        return $project;
    }

    public function destroy(project $project)
    {
        $project->delete();

        return response()->json();
    }
}
