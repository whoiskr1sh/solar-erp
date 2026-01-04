<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Task;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\User;
use App\Models\PurchaseOrder;
use App\Models\Vendor;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'converted_leads' => Lead::where('status', 'converted')->count(),
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'active')->count(),
            'total_tasks' => Task::count(),
            'pending_tasks' => Task::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'low_stock_products' => Product::whereRaw('current_stock <= min_stock_level')->count(),
            'total_invoices' => Invoice::count(),
            'pending_invoices' => Invoice::where('status', 'sent')->count(),
            'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
        ];

        $recentLeads = Lead::with('assignedUser')->latest()->limit(5)->get();
        $recentProjects = Project::with('client', 'projectManager')->latest()->limit(5)->get();
        $recentTasks = Task::with('assignedTo', 'project')->latest()->limit(5)->get();
        $recentInvoices = Invoice::with('client')->latest()->limit(5)->get();
        $recentPurchaseOrders = PurchaseOrder::with('vendor')->latest()->limit(5)->get();
        $recentVendors = Vendor::latest()->limit(5)->get();

        // Chart data
        $leadsBySource = Lead::selectRaw('source, count(*) as count')
            ->groupBy('source')
            ->get();

        $leadsByStatus = Lead::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        $projectsByStatus = Project::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        // Additional chart data
        $monthlyRevenue = Invoice::selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->where('status', 'paid')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                $monthNames = [
                    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                    5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
                    9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
                ];
                return [
                    'month' => $monthNames[$item->month] ?? $item->month,
                    'revenue' => $item->revenue
                ];
            });

        $taskCompletion = Task::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        $productSales = Product::selectRaw('name, current_stock')
            ->orderBy('current_stock', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'stats', 
            'recentLeads', 
            'recentProjects', 
            'recentTasks', 
            'recentInvoices',
            'recentPurchaseOrders',
            'recentVendors',
            'leadsBySource', 
            'leadsByStatus',
            'projectsByStatus',
            'monthlyRevenue',
            'taskCompletion',
            'productSales'
        ));
    }
}
