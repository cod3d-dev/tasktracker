<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index()
    {
        return Manager::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'project_id' => ['required', 'integer'],
            'email' => ['required', 'email', 'max:254'],
            'slack' => ['required'],
        ]);

        return Manager::create($data);
    }

    public function show(Manager $manager)
    {
        return $manager;
    }

    public function update(Request $request, Manager $manager)
    {
        $data = $request->validate([
            'name' => ['required'],
            'project_id' => ['required', 'integer'],
            'email' => ['required', 'email', 'max:254'],
            'slack' => ['required'],
        ]);

        $manager->update($data);

        return $manager;
    }

    public function destroy(Manager $manager)
    {
        $manager->delete();

        return response()->json();
    }
}
