<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Project;
use App\Models\Task;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrmDashboardController extends Controller
{
    public function index()
    {
        // Lead Statistics
        $leadStats = [
            'total' => Lead::count(),
            'new' => Lead::where('status', 'new')->count(),
            'qualified' => Lead::where('status', 'qualified')->count(),
            'converted' => Lead::where('status', 'converted')->count(),
            'lost' => Lead::where('status', 'lost')->count(),
        ];

        // Lead Conversion Rate
        $conversionRate = $leadStats['total'] > 0 ? round(($leadStats['converted'] / $leadStats['total']) * 100, 2) : 0;

        // Recent Leads
        $recentLeads = Lead::with('creator')->latest()->limit(5)->get();

        // Lead Source Analysis
        $leadSources = Lead::select('source', DB::raw('COUNT(*) as count'))
            ->groupBy('source')
            ->orderBy('count', 'desc')
            ->get();

        // Monthly Lead Trends (Last 6 months)
        $monthlyLeads = Lead::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Project Statistics
        $projectStats = [
            'total' => Project::count(),
            'active' => Project::where('status', 'active')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'on_hold' => Project::where('status', 'on_hold')->count(),
        ];

        // Recent Projects
        $recentProjects = Project::with(['client', 'projectManager'])->latest()->limit(5)->get();

        // Task Statistics
        $taskStats = [
            'total' => Task::count(),
            'pending' => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'overdue' => Task::overdue()->count(),
        ];

        // Recent Tasks
        $recentTasks = Task::with(['project', 'assignedUser'])->latest()->limit(5)->get();

        // Financial Overview
        $financialStats = [
            'total_invoices' => Invoice::count(),
            'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
            'pending_revenue' => Invoice::where('status', 'pending')->sum('total_amount'),
            'total_quotations' => Quotation::count(),
            'quotation_value' => Quotation::sum('total_amount'),
        ];

        // Recent Invoices
        $recentInvoices = Invoice::with(['client', 'project'])->latest()->limit(5)->get();

        // Recent Quotations
        $recentQuotations = Quotation::with(['client', 'project'])->latest()->limit(5)->get();

        // Document Statistics
        $documentStats = [
            'total' => Document::count(),
            'active' => Document::where('status', 'active')->count(),
            'expired' => Document::where('expiry_date', '<', now())->count(),
        ];

        // Recent Documents
        $recentDocuments = Document::with(['lead', 'project'])->latest()->limit(5)->get();

        // Top Performing Sources
        $topSources = Lead::select('source', DB::raw('COUNT(*) as count'), DB::raw('SUM(CASE WHEN status = "converted" THEN 1 ELSE 0 END) as converted'))
            ->groupBy('source')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Lead Status Pipeline
        $leadPipeline = [
            'new' => Lead::where('status', 'new')->count(),
            'qualified' => Lead::where('status', 'qualified')->count(),
            'converted' => Lead::where('status', 'converted')->count(),
            'lost' => Lead::where('status', 'lost')->count(),
        ];

        return view('crm.dashboard', compact(
            'leadStats',
            'conversionRate',
            'recentLeads',
            'leadSources',
            'monthlyLeads',
            'projectStats',
            'recentProjects',
            'taskStats',
            'recentTasks',
            'financialStats',
            'recentInvoices',
            'recentQuotations',
            'documentStats',
            'recentDocuments',
            'topSources',
            'leadPipeline'
        ));
    }
}
