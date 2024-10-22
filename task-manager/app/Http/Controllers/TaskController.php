<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Create a Task
    public function create(Request $request)
    {
        try {
            // Validate the incoming request
            $this->validate($request, [
                'title' => 'required|unique:tasks|max:255',
                'description' => 'nullable|string',
                'due_date' => 'required|date|after:today',
            ]);
    
            // Create the task
            $task = Task::create($request->all());
    
            // Return the created task with a 201 status
            return response()->json($task, 201);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation exceptions
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->validator->errors()
            ], 422);
    
        } catch (\Exception $e) {
            // Handle any other exceptions
            return response()->json([
                'error' => 'An error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get all Tasks
    public function getAll(Request $request)
    {
        $query = Task::query();

        // Task filtering by status and due date
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('due_date')) {
            $query->where('due_date', $request->input('due_date'));
        }

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        // Paginate the results
        $tasks = $query->paginate(10);
        return response()->json($tasks);
    }

    // Get a specific Task
    public function getTask($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

    // Update a Task
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'sometimes|required|unique:tasks,title,' . $id,
            'description' => 'nullable|string',
            'status' => 'in:pending,completed',
            'due_date' => 'sometimes|required|date|after:today',
        ]);

        $task = Task::findOrFail($id);
        $task->update($request->all());

        return response()->json($task);
    }

    // Delete a Task
    public function delete($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
