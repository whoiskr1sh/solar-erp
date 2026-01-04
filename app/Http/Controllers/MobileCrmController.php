<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Project;
use App\Models\Task;
use App\Models\Invoice;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MobileCrmController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Quick stats for mobile view
        $stats = [
            'today_leads' => Lead::whereDate('created_at', today())->count(),
            'pending_tasks' => Task::where('assigned_to', $user->id)->where('status', 'pending')->count(),
            'overdue_tasks' => Task::where('assigned_to', $user->id)->overdue()->count(),
            'pending_invoices' => Invoice::where('status', 'pending')->count(),
        ];

        // Recent activities
        $recentLeads = Lead::latest()->limit(5)->get();
        $recentTasks = Task::where('assigned_to', $user->id)->latest()->limit(5)->get();
        $recentProjects = Project::latest()->limit(5)->get();

        return view('mobile-crm.index', compact('stats', 'recentLeads', 'recentTasks', 'recentProjects'));
    }

    public function leads()
    {
        $leads = Lead::with('creator')->latest()->paginate(10);
        return view('mobile-crm.leads', compact('leads'));
    }

    public function tasks()
    {
        $user = Auth::user();
        $tasks = Task::with(['project', 'assignedUser'])
            ->where('assigned_to', $user->id)
            ->latest()
            ->paginate(10);
        
        return view('mobile-crm.tasks', compact('tasks'));
    }

    public function projects()
    {
        $projects = Project::with(['client', 'projectManager'])->latest()->paginate(10);
        return view('mobile-crm.projects', compact('projects'));
    }

    public function invoices()
    {
        $invoices = Invoice::with(['client', 'project'])->latest()->paginate(10);
        return view('mobile-crm.invoices', compact('invoices'));
    }

    public function quotations()
    {
        $quotations = Quotation::with(['client', 'project'])->latest()->paginate(10);
        return view('mobile-crm.quotations', compact('quotations'));
    }

    public function quickActions()
    {
        return view('mobile-crm.quick-actions');
    }

    public function notifications()
    {
        // Mock notifications for demo
        $notifications = [
            [
                'id' => 1,
                'title' => 'New Lead Assigned',
                'message' => 'You have been assigned a new lead: Solar Installation Project',
                'type' => 'lead',
                'time' => '2 hours ago',
                'read' => false
            ],
            [
                'id' => 2,
                'title' => 'Task Due Today',
                'message' => 'Site survey task is due today',
                'type' => 'task',
                'time' => '4 hours ago',
                'read' => false
            ],
            [
                'id' => 3,
                'title' => 'Invoice Overdue',
                'message' => 'Invoice #INV-001 is overdue',
                'type' => 'invoice',
                'time' => '1 day ago',
                'read' => true
            ],
            [
                'id' => 4,
                'title' => 'Quotation Accepted',
                'message' => 'Quotation #QT-001 has been accepted by client',
                'type' => 'quotation',
                'time' => '2 days ago',
                'read' => true
            ]
        ];

        return view('mobile-crm.notifications', compact('notifications'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('mobile-crm.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only(['name', 'email', 'phone']));

        return redirect()->route('mobile-crm.profile')
            ->with('success', 'Profile updated successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $results = [];

        if ($query) {
            // Search leads
            $leads = Lead::where('name', 'like', "%{$query}%")
                ->orWhere('company', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->limit(5)
                ->get();

            // Search projects
            $projects = Project::where('name', 'like', "%{$query}%")
                ->orWhere('project_code', 'like', "%{$query}%")
                ->limit(5)
                ->get();

            // Search tasks
            $tasks = Task::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->limit(5)
                ->get();

            $results = [
                'leads' => $leads,
                'projects' => $projects,
                'tasks' => $tasks,
            ];
        }

        return view('mobile-crm.search', compact('query', 'results'));
    }
}
