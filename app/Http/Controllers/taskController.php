<?php

namespace App\Http\Controllers;

use App\Models\task;
use Illuminate\Http\Request;

class taskController extends Controller
{
    public function index()
    {
        return task::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'proyect_id' => ['required', 'integer'],
            'description' => ['required'],
            'link' => ['required'],
            'type_id' => ['required', 'integer'],
            'posted_date' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'notes' => ['required'],
            'words' => ['required', 'integer'],
            'used_time' => ['required', 'date'],
            'completed_date' => ['required', 'date'],
            'priority_id' => ['required', 'integer'],
        ]);

        return task::create($data);
    }

    public function show(task $task)
    {
        return $task;
    }

    public function update(Request $request, task $task)
    {
        $data = $request->validate([
            'proyect_id' => ['required', 'integer'],
            'description' => ['required'],
            'link' => ['required'],
            'type_id' => ['required', 'integer'],
            'posted_date' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'notes' => ['required'],
            'words' => ['required', 'integer'],
            'used_time' => ['required', 'date'],
            'completed_date' => ['required', 'date'],
            'priority_id' => ['required', 'integer'],
        ]);

        $task->update($data);

        return $task;
    }

    public function destroy(task $task)
    {
        $task->delete();

        return response()->json();
    }
}
