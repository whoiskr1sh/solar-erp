<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Expense;
use App\Models\Commission;
use App\Models\ResourceAllocation;
use App\Models\Activity;
use App\Models\Lead;
use App\Models\Quotation;
use App\Models\Task;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MobileController extends Controller
{
    public function index()
    {
        // Get mobile dashboard statistics
        $userId = Auth::id();
        
        $stats = [
            'projects' => [
                'total' => Project::count(),
                'active' => Project::where('status', 'active')->count(),
                'completed' => Project::where('status', 'completed')->count(),
            ],
            'expenses' => [
                'total' => Expense::count(),
                'pending' => Expense::where('status', 'pending')->count(),
                'monthly_total' => Expense::whereMonth('expense_date', now()->month)->sum('amount'),
            ],
            'commissions' => [
                'total' => Commission::count(),
                'pending' => Commission::where('status', 'pending')->count(),
                'total_amount' => Commission::where('status', 'approved')->sum('commission_amount'),
            ],
            'tasks' => [
                'total' => Task::count(),
                'completed' => Task::where('status', 'completed')->count(),
                'overdue' => Task::where('due_date', '<', now())->where('status', '!=', 'completed')->count(),
            ],
            'leads' => [
                'total' => Lead::count(),
                'hot' => Lead::where('status', 'hot')->count(),
                'qualified' => Lead::where('status', 'qualified')->count(),
            ],
        ];

        // Get recent activities
        $recentActivities = Activity::with(['project', 'resourceAllocation'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get recent notifications
        $notifications = Notification::orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get pending expenses for approval
        $pendingExpenses = Expense::with(['category', 'creator'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('mobile.index', compact('stats', 'recentActivities', 'notifications', 'pendingExpenses'));
    }

    public function projects()
    {
        $projects = Project::with(['client', 'manager'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $projectsCount = [
            'total' => Project::count(),
            'active' => Project::where('status', 'active')->count(),
            'pending' => Project::where('status', 'pending')->count(),
            'completed' => Project::where('status', 'completed')->count(),
        ];

        return view('mobile.projects', compact('projects', 'projectsCount'));
    }

    public function expenses()
    {
        $expenses = Expense::with(['category', 'project', 'creator'])
            ->orderBy('expense_date', 'desc')
            ->paginate(10);

        $stats = [
            'total' => Expense::count(),
            'pending' => Expense::where('status', 'pending')->count(),
            'approved' => Expense::where('status', 'approved')->count(),
            'paid' => Expense::where('status', 'paid')->count(),
            'month_total' => Expense::whereMonth('expense_date', now()->month)->sum('amount'),
        ];

        return view('mobile.expenses', compact('expenses', 'stats'));
    }

    public function commissions()
    {
        $commissions = Commission::with(['channelPartner'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total' => Commission::count(),
            'pending' => Commission::where('status', 'pending')->count(),
            'approved' => Commission::where('status', 'approved')->count(),
            'paid' => Commission::where('status', 'paid')->count(),
            'total_amount' => Commission::where('status', 'approved')->sum('commission_amount'),
        ];

        return view('mobile.commissions', compact('commissions', 'stats'));
    }

    public function tasks()
    {
        $tasks = Task::with(['project', 'assignedTo'])
            ->orderBy('due_date', 'asc')
            ->paginate(10);

        $stats = [
            'total' => Task::count(),
            'pending' => Task::where('status', 'pending')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'overdue' => Task::where('due_date', '<', now())->where('status', '!=', 'completed')->count(),
        ];

        return view('mobile.tasks', compact('tasks', 'stats'));
    }

    public function resources()
    {
        $resources = ResourceAllocation::with(['project', 'allocatedTo'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('mobile.resources', compact('resources'));
    }

    public function notifications()
    {
        $notifications = Notification::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('mobile.notifications', compact('notifications'));
    }

    public function quickActions()
    {
        $categories = \App\Models\ExpenseCategory::where('is_active', true)->get();
        $projects = Project::where('status', 'active')->get();
        
        return view('mobile.quick-actions', compact('categories', 'projects'));
    }

    public function profile()
    {
        $user = Auth::user();
        
        return view('mobile.profile', compact('user'));
    }
}
