<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\MaterialRequest;
use App\Models\DailyProgressReport;
use App\Models\ResourceAllocation;
use App\Models\PaymentMilestone;
use App\Models\Budget;
use App\Models\Expense;
use App\Models\QualityCheck;
use App\Models\Document;
use App\Models\User;
use App\Models\TaskAssignmentApproval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectSubModuleController extends Controller
{
    /**
     * Project Management Dashboard for PROJECT MANAGER
     */
    public function dashboard()
    {
        $userId = Auth::id();
        
        // Get projects managed by this user
        $managedProjects = Project::where('project_manager_id', $userId)->get();
        $projectIds = $managedProjects->pluck('id');
        
        // Get converted leads (these will be available for creating new projects)
        $convertedLeads = \App\Models\Lead::where('status', 'converted')
            ->with(['assignedUser', 'creator'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
        
        // Calculate comprehensive statistics
        $stats = [
            // Project Stats
            'total_projects' => $managedProjects->count(),
            'active_projects' => $managedProjects->where('status', 'active')->count(),
            'completed_projects' => $managedProjects->where('status', 'completed')->count(),
            'on_hold_projects' => $managedProjects->where('status', 'on_hold')->count(),
            
            // Task Stats
            'total_tasks' => Task::whereIn('project_id', $projectIds)->count(),
            'pending_tasks' => Task::whereIn('project_id', $projectIds)->where('status', 'pending')->count(),
            'in_progress_tasks' => Task::whereIn('project_id', $projectIds)->where('status', 'in_progress')->count(),
            'completed_tasks' => Task::whereIn('project_id', $projectIds)->where('status', 'completed')->count(),
            'overdue_tasks' => Task::whereIn('project_id', $projectIds)
                ->where('due_date', '<', now())
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count(),
            
            // Material Request Stats
            'total_material_requests' => MaterialRequest::whereIn('project_id', $projectIds)->count(),
            'pending_material_requests' => MaterialRequest::whereIn('project_id', $projectIds)->where('status', 'pending')->count(),
            'approved_material_requests' => MaterialRequest::whereIn('project_id', $projectIds)->where('status', 'approved')->count(),
            
            // Progress Report Stats
            'total_dpr' => DailyProgressReport::whereIn('project_id', $projectIds)->count(),
            'this_week_dpr' => DailyProgressReport::whereIn('project_id', $projectIds)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            
            // Resource Allocation Stats
            'total_allocations' => ResourceAllocation::whereIn('project_id', $projectIds)->count(),
            'active_allocations' => ResourceAllocation::whereIn('project_id', $projectIds)->where('status', 'active')->count(),
            
            // Payment Milestone Stats
            'total_milestones' => PaymentMilestone::whereIn('project_id', $projectIds)->count(),
            'completed_milestones' => PaymentMilestone::whereIn('project_id', $projectIds)->where('status', 'completed')->count(),
            'pending_milestones' => PaymentMilestone::whereIn('project_id', $projectIds)->where('status', 'pending')->count(),
            
            // Budget Stats
            'total_budget' => Budget::whereIn('project_id', $projectIds)->sum('amount'),
            'used_budget' => Expense::whereIn('project_id', $projectIds)->sum('amount'),
            
            // Quality Check Stats
            'total_quality_checks' => QualityCheck::whereIn('project_id', $projectIds)->count(),
            'passed_quality_checks' => QualityCheck::whereIn('project_id', $projectIds)->where('status', 'passed')->count(),
            'failed_quality_checks' => QualityCheck::whereIn('project_id', $projectIds)->where('status', 'failed')->count(),
        ];
        
        // Recent Activities
        $recentTasks = Task::whereIn('project_id', $projectIds)
            ->with(['project', 'assignedTo'])
            ->latest()
            ->limit(5)
            ->get();
            
        $recentMaterialRequests = MaterialRequest::whereIn('project_id', $projectIds)
            ->with(['project', 'requester'])
            ->latest()
            ->limit(5)
            ->get();
            
        $recentDPR = DailyProgressReport::whereIn('project_id', $projectIds)
            ->with(['project', 'createdBy'])
            ->latest()
            ->limit(5)
            ->get();
        
        // Upcoming Deadlines
        $upcomingDeadlines = Task::whereIn('project_id', $projectIds)
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addDays(7))
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['project', 'assignedTo'])
            ->orderBy('due_date')
            ->limit(5)
            ->get();
        
        return view('project-manager.dashboard', compact(
            'stats',
            'managedProjects',
            'recentTasks',
            'recentMaterialRequests',
            'recentDPR',
            'upcomingDeadlines'
        ));
    }

    /**
     * Display all projects managed by the user
     */
    public function projects(Request $request)
    {
        $userId = Auth::id();
        $query = Project::where('project_manager_id', $userId)->with(['client', 'projectManager']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('project_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $projects = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('project-manager.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project
     */
    public function createProject()
    {
        $users = User::where('is_active', true)->get();
        $clients = \App\Models\Lead::where('status', 'converted')->get();
        
        return view('project-manager.projects.create', compact('users', 'clients'));
    }

    /**
     * Store a newly created project
     */
    public function storeProject(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_code' => 'nullable|string|max:50|unique:projects,project_code',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,critical',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'client_id' => 'nullable|exists:leads,id',
            'project_engineer' => 'nullable|exists:users,id',
            'liaisoning_officer' => 'nullable|exists:users,id',
            'location' => 'nullable|string|max:255',
        ]);

        $validated['project_manager_id'] = Auth::id();
        $validated['created_by'] = Auth::id();

        $project = Project::create($validated);

        return redirect()->route('project-manager.projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project
     */
    public function showProject(Project $project)
    {
        // Check if user is the project manager
        if ($project->project_manager_id !== Auth::id()) {
            abort(403, 'You are not authorized to view this project.');
        }

        $project->load(['client', 'projectManager', 'projectEngineer', 'liaisoningOfficer', 'tasks.assignedTo']);
        
        // Get project statistics
        $projectStats = [
            'total_tasks' => $project->tasks()->count(),
            'completed_tasks' => $project->tasks()->where('status', 'completed')->count(),
            'pending_tasks' => $project->tasks()->where('status', 'pending')->count(),
            'in_progress_tasks' => $project->tasks()->where('status', 'in_progress')->count(),
            'overdue_tasks' => $project->tasks()
                ->where('due_date', '<', now())
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count(),
            'total_material_requests' => MaterialRequest::where('project_id', $project->id)->count(),
            'pending_material_requests' => MaterialRequest::where('project_id', $project->id)->where('status', 'pending')->count(),
            'total_dpr' => DailyProgressReport::where('project_id', $project->id)->count(),
            'total_expenses' => Expense::where('project_id', $project->id)->sum('amount'),
        ];

        return view('project-manager.projects.show', compact('project', 'projectStats'));
    }

    /**
     * Show the form for editing the specified project
     */
    public function editProject(Project $project)
    {
        // Check if user is the project manager
        if ($project->project_manager_id !== Auth::id()) {
            abort(403, 'You are not authorized to edit this project.');
        }

        $users = User::where('is_active', true)->get();
        $clients = \App\Models\Lead::where('status', 'converted')->get();
        
        return view('project-manager.projects.edit', compact('project', 'users', 'clients'));
    }

    /**
     * Update the specified project
     */
    public function updateProject(Request $request, Project $project)
    {
        // Check if user is the project manager
        if ($project->project_manager_id !== Auth::id()) {
            abort(403, 'You are not authorized to update this project.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_code' => 'nullable|string|max:50|unique:projects,project_code,' . $project->id,
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,critical',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'client_id' => 'nullable|exists:leads,id',
            'project_engineer' => 'nullable|exists:users,id',
            'liaisoning_officer' => 'nullable|exists:users,id',
            'location' => 'nullable|string|max:255',
        ]);

        $project->update($validated);

        return redirect()->route('project-manager.projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Delete project with approval workflow
     */
    public function deleteProject(Request $request, Project $project)
    {
        // Check if user is the project manager
        if ($project->project_manager_id !== Auth::id()) {
            abort(403, 'You are not authorized to delete this project.');
        }

        $validated = $request->validate([
            'deletion_reason' => 'required|string|max:500',
            'confirmation' => 'required|accepted',
        ]);

        // Check if project has dependencies
        $hasTasks = $project->tasks()->count() > 0;
        $hasMaterialRequests = MaterialRequest::where('project_id', $project->id)->count() > 0;
        $hasDPR = DailyProgressReport::where('project_id', $project->id)->count() > 0;
        $hasExpenses = Expense::where('project_id', $project->id)->count() > 0;

        if ($hasTasks || $hasMaterialRequests || $hasDPR || $hasExpenses) {
            return redirect()->back()
                ->with('error', 'Cannot delete project. It has associated tasks, material requests, progress reports, or expenses. Please remove all dependencies first.');
        }

        // Log deletion request
        \App\Models\Activity::create([
            'user_id' => Auth::id(),
            'action' => 'project_deletion_request',
            'description' => "Project '{$project->name}' deletion requested",
            'metadata' => [
                'project_id' => $project->id,
                'deletion_reason' => $validated['deletion_reason'],
                'requested_at' => now(),
            ],
        ]);

        $project->delete();

        return redirect()->route('project-manager.projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    /**
     * Tasks Management
     */
    public function tasks(Request $request)
    {
        $userId = Auth::id();
        // Get projects where user is project manager, project engineer, or liaisoning officer
        $projectIds = Project::where(function($query) use ($userId) {
            $query->where('project_manager_id', $userId)
                  ->orWhere('project_engineer', $userId)
                  ->orWhere('liaisoning_officer', $userId);
        })->pluck('id');
        
        $query = Task::whereIn('project_id', $projectIds)->with(['project', 'assignedTo']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::where(function($query) use ($userId) {
            $query->where('project_manager_id', $userId)
                  ->orWhere('project_engineer', $userId)
                  ->orWhere('liaisoning_officer', $userId);
        })->get();

        return view('project-manager.tasks.index', compact('tasks', 'projects'));
    }

    /**
     * Create Task
     */
    public function createTask()
    {
        $userId = Auth::id();
        // Get projects where user is project manager, project engineer, or liaisoning officer
        $projects = Project::where(function($query) use ($userId) {
            $query->where('project_manager_id', $userId)
                  ->orWhere('project_engineer', $userId)
                  ->orWhere('liaisoning_officer', $userId);
        })->get();
        
        // Only show sub coordinators (TELE SALES, FIELD SALES, PROJECT ENGINEER, LIASONING EXECUTIVE)
        $users = User::where('is_active', true)
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['TELE SALES', 'FIELD SALES', 'PROJECT ENGINEER', 'LIASONING EXECUTIVE']);
            })
            ->orderBy('name')
            ->get();
        
        return view('project-manager.tasks.create', compact('projects', 'users'));
    }

    /**
     * Store Task
     */
    public function storeTask(Request $request)
    {
        $userId = Auth::id();
        // Get projects where user is project manager, project engineer, or liaisoning officer
        $projectIds = Project::where(function($query) use ($userId) {
            $query->where('project_manager_id', $userId)
                  ->orWhere('project_engineer', $userId)
                  ->orWhere('liaisoning_officer', $userId);
        })->pluck('id');
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id|in:' . $projectIds->implode(','),
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after:start_date',
            'estimated_hours' => 'nullable|numeric|min:0',
        ]);

        // Validate that assigned_to is a sub coordinator
        $assignedUserId = $validated['assigned_to'];
        $assignedUser = User::findOrFail($assignedUserId);
        $isSubCoordinator = $assignedUser->hasRole('TELE SALES') 
            || $assignedUser->hasRole('FIELD SALES') 
            || $assignedUser->hasRole('PROJECT ENGINEER') 
            || $assignedUser->hasRole('LIASONING EXECUTIVE');
        
        if (!$isSubCoordinator) {
            return redirect()->back()
                ->with('error', 'Tasks can only be assigned to sub coordinators (TELE SALES, FIELD SALES, PROJECT ENGINEER, or LIASONING EXECUTIVE).')
                ->withInput();
        }

        $validated['created_by'] = Auth::id();
        // Don't assign immediately - set to null initially, will be assigned after approval
        $validated['assigned_to'] = null;

        $task = Task::create($validated);

        // Create assignment approval request
        $approvalRequest = TaskAssignmentApproval::create([
            'task_id' => $task->id,
            'requested_by' => Auth::id(),
            'assigned_to' => $assignedUserId,
            'status' => 'pending_manager_approval',
        ]);

        // Notify Sales Managers
        $managers = User::whereHas('roles', function($q) {
            $q->where('name', 'SALES MANAGER');
        })->get();

        foreach ($managers as $manager) {
            \App\Models\Notification::create([
                'user_id' => $manager->id,
                'title' => 'Task Assignment Approval Required',
                'message' => Auth::user()->name . " has requested to assign task '{$task->title}' to {$assignedUser->name}. Approval required.",
                'type' => 'approval',
                'data' => [
                    'task_assignment_approval_id' => $approvalRequest->id,
                    'task_id' => $task->id,
                ]
            ]);
        }

        return redirect()->route('project-manager.tasks.show', $task)
            ->with('success', 'Task created successfully. Assignment approval request sent to Sales Manager.');
    }

    /**
     * Show Task
     */
    public function showTask(Task $task)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $project = $task->project;
        
        // Check if user is the project manager, project engineer, or liaisoning officer
        if ($project->project_manager_id !== $userId 
            && $project->project_engineer !== $userId 
            && $project->liaisoning_officer !== $userId) {
            abort(403, 'You are not authorized to view this task.');
        }

        $task->load(['project', 'assignedUser', 'creator']);
        
        // Check if user can request reassignment
        $canRequestReassignment = false;
        $isSubCoordinator = false;
        $isSalesManager = false;
        
        // Check if user is project engineer or liaisoning officer (sub coordinator)
        if ($project->project_engineer == $userId || $project->liaisoning_officer == $userId) {
            $isSubCoordinator = true;
            $canRequestReassignment = true;
        }
        
        // Check if user is Sales Manager
        if ($user->hasRole('SALES MANAGER')) {
            $isSalesManager = true;
            $canRequestReassignment = true;
        }
        
        // Check if there's already a pending request for this task
        $hasPendingRequest = \App\Models\TaskReassignmentRequest::where('task_id', $task->id)
            ->where('status', 'pending')
            ->exists();
        
        return view('project-manager.tasks.show', compact('task', 'canRequestReassignment', 'hasPendingRequest', 'isSubCoordinator', 'isSalesManager'));
    }

    /**
     * Edit Task
     */
    public function editTask(Task $task)
    {
        $userId = Auth::id();
        $project = $task->project;
        
        // Check if user is the project manager, project engineer, or liaisoning officer
        if ($project->project_manager_id !== $userId 
            && $project->project_engineer !== $userId 
            && $project->liaisoning_officer !== $userId) {
            abort(403, 'You are not authorized to edit this task.');
        }

        // Get projects where user is project manager, project engineer, or liaisoning officer
        $projects = Project::where(function($query) use ($userId) {
            $query->where('project_manager_id', $userId)
                  ->orWhere('project_engineer', $userId)
                  ->orWhere('liaisoning_officer', $userId);
        })->get();
        // Only show sub coordinators (TELE SALES, FIELD SALES, PROJECT ENGINEER, LIASONING EXECUTIVE)
        $users = User::where('is_active', true)
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['TELE SALES', 'FIELD SALES', 'PROJECT ENGINEER', 'LIASONING EXECUTIVE']);
            })
            ->orderBy('name')
            ->get();
        
        return view('project-manager.tasks.edit', compact('task', 'projects', 'users'));
    }

    /**
     * Update Task
     */
    public function updateTask(Request $request, Task $task)
    {
        $userId = Auth::id();
        $project = $task->project;
        
        // Check if user is the project manager, project engineer, or liaisoning officer
        if ($project->project_manager_id !== $userId 
            && $project->project_engineer !== $userId 
            && $project->liaisoning_officer !== $userId) {
            abort(403, 'You are not authorized to update this task.');
        }

        // Get projects where user is project manager, project engineer, or liaisoning officer
        $projectIds = Project::where(function($query) use ($userId) {
            $query->where('project_manager_id', $userId)
                  ->orWhere('project_engineer', $userId)
                  ->orWhere('liaisoning_officer', $userId);
        })->pluck('id');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id|in:' . $projectIds->implode(','),
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after:start_date',
            'estimated_hours' => 'nullable|numeric|min:0',
        ]);

        // Validate that assigned_to is a sub coordinator
        $assignedUser = User::findOrFail($validated['assigned_to']);
        $isSubCoordinator = $assignedUser->hasRole('TELE SALES') 
            || $assignedUser->hasRole('FIELD SALES') 
            || $assignedUser->hasRole('PROJECT ENGINEER') 
            || $assignedUser->hasRole('LIASONING EXECUTIVE');
        
        if (!$isSubCoordinator) {
            return redirect()->back()
                ->with('error', 'Tasks can only be assigned to sub coordinators (TELE SALES, FIELD SALES, PROJECT ENGINEER, or LIASONING EXECUTIVE).')
                ->withInput();
        }

        $updateData = $validated;
        
        // If assigned_to is changing, create approval request
        if ($task->assigned_to != $validated['assigned_to']) {
            // Check if there's already a pending approval for this task
            $existingApproval = TaskAssignmentApproval::where('task_id', $task->id)
                ->whereIn('status', ['pending_manager_approval', 'pending_admin_approval'])
                ->exists();
            
            if (!$existingApproval) {
                // Don't update assigned_to yet - wait for approval
                unset($updateData['assigned_to']);
                
                // Create assignment approval request
                $approvalRequest = TaskAssignmentApproval::create([
                    'task_id' => $task->id,
                    'requested_by' => Auth::id(),
                    'assigned_to' => $validated['assigned_to'],
                    'status' => 'pending_manager_approval',
                ]);

                // Notify Sales Managers
                $managers = User::whereHas('roles', function($q) {
                    $q->where('name', 'SALES MANAGER');
                })->get();

                foreach ($managers as $manager) {
                    \App\Models\Notification::create([
                        'user_id' => $manager->id,
                        'title' => 'Task Assignment Approval Required',
                        'message' => Auth::user()->name . " has requested to reassign task '{$task->title}' to {$assignedUser->name}. Approval required.",
                        'type' => 'approval',
                        'data' => [
                            'task_assignment_approval_id' => $approvalRequest->id,
                            'task_id' => $task->id,
                        ]
                    ]);
                }
            } else {
                return redirect()->back()
                    ->with('error', 'There is already a pending assignment approval for this task. Please wait for approval.')
                    ->withInput();
            }
        }
        
        // Set completed_date if status changed to completed
        if ($request->status === 'completed' && $task->status !== 'completed') {
            $updateData['completed_date'] = now();
        } elseif ($request->status !== 'completed') {
            $updateData['completed_date'] = null;
        }

        $task->update($updateData);

        $message = isset($approvalRequest) 
            ? 'Task updated successfully. Assignment approval request sent to Sales Manager.'
            : 'Task updated successfully.';

        return redirect()->route('project-manager.tasks.show', $task)
            ->with('success', $message);
    }

    /**
     * Delete Task with approval
     */
    public function deleteTask(Request $request, Task $task)
    {
        $userId = Auth::id();
        $project = $task->project;
        
        // Check if user is the project manager, project engineer, or liaisoning officer
        if ($project->project_manager_id !== $userId 
            && $project->project_engineer !== $userId 
            && $project->liaisoning_officer !== $userId) {
            abort(403, 'You are not authorized to delete this task.');
        }

        $validated = $request->validate([
            'deletion_reason' => 'required|string|max:500',
            'confirmation' => 'required|accepted',
        ]);

        // Log deletion request
        \App\Models\Activity::create([
            'user_id' => Auth::id(),
            'action' => 'task_deletion_request',
            'description' => "Task '{$task->title}' deletion requested",
            'metadata' => [
                'task_id' => $task->id,
                'project_id' => $task->project_id,
                'deletion_reason' => $validated['deletion_reason'],
                'requested_at' => now(),
            ],
        ]);

        $task->delete();

        return redirect()->route('project-manager.tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    /**
     * Material Requests Management
     */
    public function materialRequests(Request $request)
    {
        $userId = Auth::id();
        $projectIds = Project::where('project_manager_id', $userId)->pluck('id');
        
        $query = MaterialRequest::whereIn('project_id', $projectIds)->with(['project', 'requester']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('request_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        $materialRequests = $query->orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::where('project_manager_id', $userId)->get();

        return view('project-manager.material-requests.index', compact('materialRequests', 'projects'));
    }

    /**
     * Approve Material Request
     */
    public function approveMaterialRequest(Request $request, MaterialRequest $materialRequest)
    {
        $userId = Auth::id();
        $project = $materialRequest->project;
        
        // Check if user is the project manager
        if ($project->project_manager_id !== $userId) {
            abort(403, 'You are not authorized to approve this material request.');
        }

        $validated = $request->validate([
            'approval_notes' => 'nullable|string|max:500',
        ]);

        $materialRequest->update([
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_date' => now(),
            'approval_notes' => $validated['approval_notes'],
        ]);

        return redirect()->back()
            ->with('success', 'Material request approved successfully.');
    }

    /**
     * Reject Material Request
     */
    public function rejectMaterialRequest(Request $request, MaterialRequest $materialRequest)
    {
        $userId = Auth::id();
        $project = $materialRequest->project;
        
        // Check if user is the project manager
        if ($project->project_manager_id !== $userId) {
            abort(403, 'You are not authorized to reject this material request.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $materialRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->back()
            ->with('success', 'Material request rejected successfully.');
    }

    /**
     * Daily Progress Reports
     */
    public function progressReports(Request $request)
    {
        $userId = Auth::id();
        $projectIds = Project::where('project_manager_id', $userId)->pluck('id');
        
        $query = DailyProgressReport::whereIn('project_id', $projectIds)->with(['project', 'createdBy']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $progressReports = $query->orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::where('project_manager_id', $userId)->get();

        return view('project-manager.progress-reports.index', compact('progressReports', 'projects'));
    }

    /**
     * Create Progress Report
     */
    public function createProgressReport()
    {
        $userId = Auth::id();
        $projects = Project::where('project_manager_id', $userId)->get();
        
        return view('project-manager.progress-reports.create', compact('projects'));
    }

    /**
     * Store Progress Report
     */
    public function storeProgressReport(Request $request)
    {
        $userId = Auth::id();
        $projectIds = Project::where('project_manager_id', $userId)->pluck('id');
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,id|in:' . $projectIds->implode(','),
            'work_completed' => 'required|string',
            'work_planned' => 'required|string',
            'issues_encountered' => 'nullable|string',
            'next_day_plan' => 'required|string',
            'weather_conditions' => 'nullable|string',
            'attendance_count' => 'nullable|integer|min:0',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['created_by'] = $userId;

        $progressReport = DailyProgressReport::create($validated);

        return redirect()->route('project-manager.progress-reports.show', $progressReport)
            ->with('success', 'Progress report created successfully.');
    }

    /**
     * Resource Allocation Management
     */
    public function resourceAllocations(Request $request)
    {
        $userId = Auth::id();
        $projectIds = Project::where('project_manager_id', $userId)->pluck('id');
        
        $query = ResourceAllocation::whereIn('project_id', $projectIds)->with(['project', 'user']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        $resourceAllocations = $query->orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::where('project_manager_id', $userId)->get();

        return view('project-manager.resource-allocations.index', compact('resourceAllocations', 'projects'));
    }

    /**
     * Create Resource Allocation
     */
    public function createResourceAllocation()
    {
        $userId = Auth::id();
        $projects = Project::where('project_manager_id', $userId)->get();
        $users = User::where('is_active', true)->get();
        
        return view('project-manager.resource-allocations.create', compact('projects', 'users'));
    }

    /**
     * Store Resource Allocation
     */
    public function storeResourceAllocation(Request $request)
    {
        $userId = Auth::id();
        $projectIds = Project::where('project_manager_id', $userId)->pluck('id');
        
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id|in:' . $projectIds->implode(','),
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'allocation_percentage' => 'required|numeric|min:1|max:100',
            'hourly_rate' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,completed',
            'notes' => 'nullable|string',
        ]);

        $validated['allocated_by'] = $userId;

        $resourceAllocation = ResourceAllocation::create($validated);

        return redirect()->route('project-manager.resource-allocations.show', $resourceAllocation)
            ->with('success', 'Resource allocation created successfully.');
    }

    /**
     * Payment Milestones Management
     */
    public function paymentMilestones(Request $request)
    {
        $userId = Auth::id();
        $projectIds = Project::where('project_manager_id', $userId)->pluck('id');
        
        $query = PaymentMilestone::whereIn('project_id', $projectIds)->with(['project']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('milestone_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        $paymentMilestones = $query->orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::where('project_manager_id', $userId)->get();

        return view('project-manager.payment-milestones.index', compact('paymentMilestones', 'projects'));
    }

    /**
     * Create Payment Milestone
     */
    public function createPaymentMilestone()
    {
        $userId = Auth::id();
        $projects = Project::where('project_manager_id', $userId)->get();
        
        return view('project-manager.payment-milestones.create', compact('projects'));
    }

    /**
     * Store Payment Milestone
     */
    public function storePaymentMilestone(Request $request)
    {
        $userId = Auth::id();
        $projectIds = Project::where('project_manager_id', $userId)->pluck('id');
        
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id|in:' . $projectIds->implode(','),
            'milestone_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'percentage' => 'required|numeric|min:0|max:100',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = $userId;

        $paymentMilestone = PaymentMilestone::create($validated);

        return redirect()->route('project-manager.payment-milestones.show', $paymentMilestone)
            ->with('success', 'Payment milestone created successfully.');
    }

    /**
     * Budget Management
     */
    public function budgets(Request $request)
    {
        $userId = Auth::id();
        $projectIds = Project::where('project_manager_id', $userId)->pluck('id');
        
        $query = Budget::whereIn('project_id', $projectIds)->with(['project', 'category']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        $budgets = $query->orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::where('project_manager_id', $userId)->get();
        $categories = \App\Models\BudgetCategory::all();

        return view('project-manager.budgets.index', compact('budgets', 'projects', 'categories'));
    }

    /**
     * Create Budget
     */
    public function createBudget()
    {
        $userId = Auth::id();
        $projects = Project::where('project_manager_id', $userId)->get();
        $categories = \App\Models\BudgetCategory::all();
        
        return view('project-manager.budgets.create', compact('projects', 'categories'));
    }

    /**
     * Store Budget
     */
    public function storeBudget(Request $request)
    {
        $userId = Auth::id();
        $projectIds = Project::where('project_manager_id', $userId)->pluck('id');
        
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id|in:' . $projectIds->implode(','),
            'category_id' => 'required|exists:budget_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive,completed',
        ]);

        $validated['created_by'] = $userId;

        $budget = Budget::create($validated);

        return redirect()->route('project-manager.budgets.show', $budget)
            ->with('success', 'Budget created successfully.');
    }

    /**
     * Quality Checks Management
     */
    public function qualityChecks(Request $request)
    {
        $userId = Auth::id();
        $projectIds = Project::where('project_manager_id', $userId)->pluck('id');
        
        $query = QualityCheck::whereIn('project_id', $projectIds)->with(['project', 'checkedBy']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('qc_number', 'like', "%{$search}%")
                  ->orWhere('item_name', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        $qualityChecks = $query->orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::where('project_manager_id', $userId)->get();

        return view('project-manager.quality-checks.index', compact('qualityChecks', 'projects'));
    }

    /**
     * Create Quality Check
     */
    public function createQualityCheck()
    {
        $userId = Auth::id();
        $projects = Project::where('project_manager_id', $userId)->get();
        $users = User::where('is_active', true)->get();
        
        return view('project-manager.quality-checks.create', compact('projects', 'users'));
    }

    /**
     * Store Quality Check
     */
    public function storeQualityCheck(Request $request)
    {
        $userId = Auth::id();
        $projectIds = Project::where('project_manager_id', $userId)->pluck('id');
        
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id|in:' . $projectIds->implode(','),
            'qc_number' => 'nullable|string|max:50|unique:quality_checks,qc_number',
            'item_name' => 'required|string|max:255',
            'item_code' => 'nullable|string|max:100',
            'vendor_name' => 'nullable|string|max:255',
            'inspector_name' => 'required|string|max:255',
            'inspector_designation' => 'nullable|string|max:255',
            'checked_by' => 'required|exists:users,id',
            'status' => 'required|in:pending,passed,failed,rejected',
            'qc_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $validated['created_by'] = $userId;

        $qualityCheck = QualityCheck::create($validated);

        return redirect()->route('project-manager.quality-checks.show', $qualityCheck)
            ->with('success', 'Quality check created successfully.');
    }

    /**
     * Documents Management
     */
    public function documents(Request $request)
    {
        $userId = Auth::id();
        $projectIds = Project::where('project_manager_id', $userId)->pluck('id');
        
        $query = Document::whereIn('project_id', $projectIds)->with(['project', 'uploadedBy']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::where('project_manager_id', $userId)->get();

        return view('project-manager.documents.index', compact('documents', 'projects'));
    }

    /**
     * Create Document
     */
    public function createDocument()
    {
        $userId = Auth::id();
        $projects = Project::where('project_manager_id', $userId)->get();
        
        return view('project-manager.documents.create', compact('projects'));
    }

    /**
     * Store Document
     */
    public function storeDocument(Request $request)
    {
        $userId = Auth::id();
        $projectIds = Project::where('project_manager_id', $userId)->pluck('id');
        
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id|in:' . $projectIds->implode(','),
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:contract,drawing,specification,report,other',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
            'is_public' => 'boolean',
        ]);

        $validated['uploaded_by'] = $userId;
        $validated['file_name'] = $request->file('file')->getClientOriginalName();
        $validated['file_path'] = $request->file('file')->store('documents', 'public');
        $validated['file_size'] = $request->file('file')->getSize();

        $document = Document::create($validated);

        return redirect()->route('project-manager.documents.show', $document)
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * Delete Document with approval
     */
    public function deleteDocument(Request $request, Document $document)
    {
        $userId = Auth::id();
        $project = $document->project;
        
        // Check if user is the project manager
        if ($project->project_manager_id !== $userId) {
            abort(403, 'You are not authorized to delete this document.');
        }

        $validated = $request->validate([
            'deletion_reason' => 'required|string|max:500',
            'confirmation' => 'required|accepted',
        ]);

        // Log deletion request
        \App\Models\Activity::create([
            'user_id' => Auth::id(),
            'action' => 'document_deletion_request',
            'description' => "Document '{$document->title}' deletion requested",
            'metadata' => [
                'document_id' => $document->id,
                'project_id' => $document->project_id,
                'deletion_reason' => $validated['deletion_reason'],
                'requested_at' => now(),
            ],
        ]);

        // Delete file from storage
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('project-manager.documents.index')
            ->with('success', 'Document deleted successfully.');
    }
}