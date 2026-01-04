<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Project;
use App\Models\Task;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function leads(Request $request)
    {
        $query = Lead::query();

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Source filter
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        $leads = $query->with('creator')->latest()->paginate(20);

        // Stats
        $stats = [
            'total' => Lead::count(),
            'new' => Lead::where('status', 'new')->count(),
            'qualified' => Lead::where('status', 'qualified')->count(),
            'converted' => Lead::where('status', 'converted')->count(),
            'lost' => Lead::where('status', 'lost')->count(),
        ];

        // Conversion rate
        $conversionRate = $stats['total'] > 0 ? round(($stats['converted'] / $stats['total']) * 100, 2) : 0;

        return view('reports.leads', compact('leads', 'stats', 'conversionRate'));
    }

    public function projects(Request $request)
    {
        $query = Project::query();

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->with(['client', 'projectManager'])->latest()->paginate(20);

        // Stats
        $stats = [
            'total' => Project::count(),
            'planning' => Project::where('status', 'planning')->count(),
            'in_progress' => Project::where('status', 'in_progress')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'on_hold' => Project::where('status', 'on_hold')->count(),
            'cancelled' => Project::where('status', 'cancelled')->count(),
        ];

        // Revenue stats
        $revenueStats = [
            'total_budget' => Project::sum('budget'),
            'completed_revenue' => Project::where('status', 'completed')->sum('budget'),
            'avg_project_value' => Project::avg('budget'),
        ];

        return view('reports.projects', compact('projects', 'stats', 'revenueStats'));
    }

    public function tasks(Request $request)
    {
        $query = Task::with(['project', 'assignedUser', 'creator']);

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Project filter
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $tasks = $query->latest()->paginate(20);

        // Stats
        $stats = [
            'total' => Task::count(),
            'pending' => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'cancelled' => Task::where('status', 'cancelled')->count(),
            'overdue' => Task::overdue()->count(),
        ];

        // Productivity stats
        $productivityStats = [
            'total_estimated_hours' => Task::sum('estimated_hours'),
            'total_actual_hours' => Task::sum('actual_hours'),
            'completion_rate' => $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100, 2) : 0,
        ];

        $projects = Project::select('id', 'name')->get();

        return view('reports.tasks', compact('tasks', 'stats', 'productivityStats', 'projects'));
    }

    public function financial(Request $request)
    {
        $query = Invoice::query();

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->with(['client', 'project'])->latest()->paginate(20);

        // Financial stats
        $stats = [
            'total_invoices' => Invoice::count(),
            'total_amount' => Invoice::sum('total_amount'),
            'paid_amount' => Invoice::where('status', 'paid')->sum('total_amount'),
            'pending_amount' => Invoice::where('status', 'pending')->sum('total_amount'),
            'overdue_amount' => Invoice::where('status', 'overdue')->sum('total_amount'),
        ];

        // Monthly revenue trend
        $monthlyRevenue = Invoice::select(
                DB::raw('DATE_FORMAT(invoice_date, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->where('status', 'paid')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Payment status breakdown
        $paymentStatus = Invoice::select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as amount'))
            ->groupBy('status')
            ->get();

        return view('reports.financial', compact('invoices', 'stats', 'monthlyRevenue', 'paymentStatus'));
    }

    public function inventory(Request $request)
    {
        $query = Product::query();

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Stock filter
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->where('current_stock', '<=', DB::raw('minimum_stock'));
                    break;
                case 'out':
                    $query->where('current_stock', 0);
                    break;
                case 'sufficient':
                    $query->where('current_stock', '>', DB::raw('minimum_stock'));
                    break;
            }
        }

        $products = $query->latest()->paginate(20);

        // Stats
        $stats = [
            'total_products' => Product::count(),
            'total_value' => Product::selectRaw('SUM(current_stock * selling_price) as total')->value('total'),
            'low_stock_items' => Product::whereRaw('current_stock <= min_stock_level')->count(),
            'out_of_stock' => Product::where('current_stock', 0)->count(),
        ];

        // Category breakdown
        $categoryBreakdown = Product::select('category', DB::raw('COUNT(*) as count'), DB::raw('SUM(current_stock * selling_price) as value'))
            ->groupBy('category')
            ->get();

        return view('reports.inventory', compact('products', 'stats', 'categoryBreakdown'));
    }

    public function vendors(Request $request)
    {
        $query = Vendor::query();

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $vendors = $query->with('creator')->latest()->paginate(20);

        // Stats
        $stats = [
            'total_vendors' => Vendor::count(),
            'active' => Vendor::where('status', 'active')->count(),
            'inactive' => Vendor::where('status', 'inactive')->count(),
            'blacklisted' => Vendor::where('status', 'blacklisted')->count(),
        ];

        // Top vendors by credit limit
        $topVendors = Vendor::where('status', 'active')
            ->orderBy('credit_limit', 'desc')
            ->limit(10)
            ->get();

        return view('reports.vendors', compact('vendors', 'stats', 'topVendors'));
    }

    public function dashboard()
    {
        // Overall stats
        $overallStats = [
            'total_leads' => Lead::count(),
            'total_projects' => Project::count(),
            'total_tasks' => Task::count(),
            'total_invoices' => Invoice::count(),
            'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
            'pending_revenue' => Invoice::where('status', 'pending')->sum('total_amount'),
        ];

        // Recent activities
        $recentLeads = Lead::with('creator')->latest()->limit(5)->get();
        $recentProjects = Project::with(['client', 'projectManager'])->latest()->limit(5)->get();
        $recentTasks = Task::with(['project', 'assignedUser'])->latest()->limit(5)->get();

        // Monthly trends
        $monthlyLeads = Lead::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyRevenue = Invoice::select(
                DB::raw('DATE_FORMAT(invoice_date, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->where('status', 'paid')
            ->where('invoice_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('reports.dashboard', compact(
            'overallStats',
            'recentLeads',
            'recentProjects',
            'recentTasks',
            'monthlyLeads',
            'monthlyRevenue'
        ));
    }
}
