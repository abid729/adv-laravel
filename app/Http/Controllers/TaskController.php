<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Task::class);
        $user = auth()->user();
        
        $tasks = Task::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhereIn('team_id', $user->teams->pluck('id'));
            if ($user->hasPermission('view-all-tasks')) {
                $query->orWhereNotNull('id');
            }
        })->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $this->authorize('create', Task::class);
        $teams = auth()->user()->teams;
        return view('tasks.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Task::class);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'user_id' => auth()->id(),
            'team_id' => $validated['team_id'],
            'status' => 'pending',
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $teams = auth()->user()->teams;
        return view('tasks.edit', compact('task', 'teams'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_id' => 'nullable|exists:teams,id',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($validated);
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');
    }

    public function complete(Task $task)
    {
        $this->authorize('complete', $task);
        $task->update(['status' => 'completed']);
        return redirect()->route('tasks.index')->with('success', 'Task marked as complete');
    }

    public function assign(Request $request, Task $task)
    {
        Gate::authorize('assign-task', $task);
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $task->update(['user_id' => $validated['user_id']]);
        return redirect()->route('tasks.index')->with('success', 'Task assigned successfully');
    }
}