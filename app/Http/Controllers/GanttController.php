<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GanttController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['client', 'projectManager', 'tasks.assignedTo']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('project_manager_id')) {
            $query->where('project_manager_id', $request->project_manager_id);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('project_code', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $projects = $query->latest()->get();
        $users = User::where('is_active', true)->get();
        
        // Get selected project
        $selectedProject = null;
        if ($request->filled('project_id')) {
            $selectedProject = Project::with(['tasks.assignedTo', 'client', 'projectManager'])
                ->find($request->project_id);
        } elseif ($projects->isNotEmpty()) {
            $selectedProject = $projects->first();
        }

        $stats = [
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'active')->count(),
            'total_tasks' => Task::count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'in_progress_tasks' => Task::where('status', 'in_progress')->count(),
            'pending_tasks' => Task::where('status', 'pending')->count(),
        ];

        return view('gantt.index', compact('projects', 'users', 'stats', 'selectedProject'));
    }

    public function show(Project $project)
    {
        $project->load(['tasks.assignedTo', 'client', 'projectManager']);
        $tasks = $project->tasks()
            ->with('assignedTo')
            ->orderBy('start_date', 'asc')
            ->get();
        
        // Calculate project statistics
        $stats = [
            'total_tasks' => $tasks->count(),
            'completed_tasks' => $tasks->where('status', 'completed')->count(),
            'in_progress_tasks' => $tasks->where('status', 'in_progress')->count(),
            'pending_tasks' => $tasks->where('status', 'pending')->count(),
            'overdue_tasks' => $tasks->filter(function($task) {
                return $task->due_date && $task->due_date->isPast() && !in_array($task->status, ['completed', 'cancelled']);
            })->count(),
        ];
        
        return view('gantt.show', compact('project', 'tasks', 'stats'));
    }

    public function updateTaskDates(Request $request, Project $project)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $task = $project->tasks()->findOrFail($request->task_id);
        
        $task->update([
            'start_date' => $request->start_date,
            'due_date' => $request->end_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task dates updated successfully',
            'task' => $task->fresh()
        ]);
    }

    public function updateTaskProgress(Request $request, Project $project)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'progress' => 'required|numeric|min:0|max:100',
        ]);

        $task = $project->tasks()->findOrFail($request->task_id);
        
        // Update status based on progress
        $status = 'pending';
        if ($request->progress == 100) {
            $status = 'completed';
        } elseif ($request->progress > 0) {
            $status = 'in_progress';
        }
        
        $task->update([
            'status' => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task progress updated successfully',
            'task' => $task->fresh()
        ]);
    }

    public function create()
    {
        $users = User::where('is_active', true)->get();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        return view('gantt.create', compact('users', 'projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after:start_date',
            'priority' => 'required|in:low,medium,high,urgent',
            'estimated_hours' => 'nullable|integer|min:0',
        ]);

        Task::create([
            ...$request->all(),
            'status' => 'pending',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('gantt.index')->with('success', 'Task created successfully!');
    }

    public function edit(Task $task)
    {
        $users = User::where('is_active', true)->get();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        return view('gantt.edit', compact('task', 'users', 'projects'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after:start_date',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'estimated_hours' => 'nullable|integer|min:0',
            'actual_hours' => 'nullable|integer|min:0',
        ]);

        $task->update($request->all());

        return redirect()->route('gantt.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('gantt.index')->with('success', 'Task deleted successfully!');
    }
}
