<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HandlesDeletionApproval;

class TaskController extends Controller
{
    use HandlesDeletionApproval;
    public function index(Request $request)
    {
        $query = Task::with(['project', 'assignedUser', 'creator']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('overdue')) {
            $query->overdue();
        }
        if ($request->filled('due_today')) {
            $query->dueToday();
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $tasks = $query->latest()->paginate(15);
        
        $stats = [
            'total' => Task::count(),
            'pending' => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'overdue' => Task::overdue()->count(),
            'due_today' => Task::dueToday()->count(),
        ];

        $projects = Project::select('id', 'name')->get();
        $users = User::select('id', 'name')->get();

        return view('tasks.index', compact('tasks', 'stats', 'projects', 'users'));
    }

    public function create()
    {
        $projects = Project::select('id', 'name')->get();
        $users = User::select('id', 'name')->get();
        return view('tasks.create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'estimated_hours' => 'nullable|integer|min:0',
            'actual_hours' => 'nullable|integer|min:0',
        ]);

        $task = Task::create([
            ...$request->all(),
            'created_by' => Auth::id(),
            'completed_date' => $request->status === 'completed' ? now() : null,
        ]);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $task->load(['project', 'assignedUser', 'creator']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $projects = Project::select('id', 'name')->get();
        $users = User::select('id', 'name')->get();
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'estimated_hours' => 'nullable|integer|min:0',
            'actual_hours' => 'nullable|integer|min:0',
        ]);

        $updateData = $request->all();
        
        // Set completed_date if status changed to completed
        if ($request->status === 'completed' && $task->status !== 'completed') {
            $updateData['completed_date'] = now();
        } elseif ($request->status !== 'completed') {
            $updateData['completed_date'] = null;
        }

        $task->update($updateData);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Request $request, Task $task)
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ], [
            'reason.required' => 'Please provide a reason for deletion.',
            'reason.min' => 'Reason must be at least 10 characters long.',
        ]);

        $modelName = 'Task: ' . $task->title;
        return $this->handleDeletion($task, $modelName, $validated['reason'], 'tasks.index');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $updateData = ['status' => $request->status];
        
        if ($request->status === 'completed') {
            $updateData['completed_date'] = now();
        } elseif ($task->status === 'completed') {
            $updateData['completed_date'] = null;
        }

        $task->update($updateData);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task status updated successfully!');
    }
}
