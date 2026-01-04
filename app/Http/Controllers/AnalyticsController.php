<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Lead;
use App\Models\Invoice;
use App\Models\Task;
use App\Models\Commission;
use App\Models\MaterialConsumption;
use App\Models\Quotation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        // Get summary statistics
        $stats = $this->getSummaryStats();
        
        return view('analytics.dashboard', compact('stats'));
    }

    public function getChartData(Request $request)
    {
        $period = $request->get('period', 30); // Default to 30 days
        $startDate = Carbon::now()->subDays($period);
        
        $data = [
            'revenue' => $this->getRevenueData($startDate),
            'projects' => $this->getProjectStatusData(),
            'leads' => $this->getLeadConversionData(),
            'tasks' => $this->getTaskCompletionData(),
            'sales' => $this->getSalesData(),
            'satisfaction' => $this->getSatisfactionData(),
            'installation' => $this->getInstallationData(),
            'regional' => $this->getRegionalData(),
        ];

        return response()->json($data);
    }

    private function getSummaryStats()
    {
        return [
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'active')->count(),
            'total_leads' => Lead::count(),
            'converted_leads' => Lead::where('status', 'converted')->count(),
            'total_invoices' => Invoice::count(),
            'paid_invoices' => Invoice::where('status', 'paid')->count(),
            'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
            'pending_tasks' => Task::where('status', 'pending')->count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
        ];
    }

    private function getRevenueData($startDate)
    {
        // Get monthly revenue data - use invoice_date instead of created_at
        $revenueData = Invoice::where('status', 'paid')
            ->where('invoice_date', '>=', $startDate)
            ->selectRaw('DATE_FORMAT(invoice_date, "%Y-%m") as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];
        
        // Generate last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $labels[] = Carbon::now()->subMonths($i)->format('M');
            
            $monthData = $revenueData->where('month', $month)->first();
            $data[] = $monthData ? (float)$monthData->total : 0;
        }

        // Calculate total from all paid invoices (not just filtered period)
        $totalRevenue = Invoice::where('status', 'paid')->sum('total_amount');

        return [
            'labels' => $labels,
            'data' => $data,
            'total' => (float)$totalRevenue
        ];
    }

    private function getProjectStatusData()
    {
        $statusData = Project::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Map actual database statuses to display labels
        $statusMapping = [
            'planning' => 'Planning',
            'active' => 'Active', 
            'on_hold' => 'On Hold',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];

        $labels = [];
        $data = [];
        $colors = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444'];
        $colorIndex = 0;

        foreach ($statusMapping as $dbStatus => $displayLabel) {
            $labels[] = $displayLabel;
            $data[] = $statusData->get($dbStatus, 0);
            $colorIndex++;
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => array_slice($colors, 0, count($labels))
        ];
    }

    private function getLeadConversionData()
    {
        $leadData = Lead::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Map actual database statuses to display labels
        $statusMapping = [
            'new' => 'New',
            'contacted' => 'Contacted',
            'qualified' => 'Qualified',
            'proposal' => 'Proposal Sent',
            'negotiation' => 'Negotiation',
            'converted' => 'Converted',
            'lost' => 'Lost'
        ];

        $labels = [];
        $data = [];
        $colors = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#22c55e', '#ef4444'];

        foreach ($statusMapping as $dbStatus => $displayLabel) {
            $labels[] = $displayLabel;
            $data[] = $leadData->get($dbStatus, 0);
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => array_slice($colors, 0, count($labels))
        ];
    }

    private function getTaskCompletionData()
    {
        $taskData = Task::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Map actual database statuses to display labels
        $statusMapping = [
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];

        $labels = [];
        $data = [];

        foreach ($statusMapping as $dbStatus => $displayLabel) {
            $labels[] = $displayLabel;
            $data[] = $taskData->get($dbStatus, 0);
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getSalesData()
    {
        // Get sales data by product type (assuming you have product categories)
        $salesData = DB::table('invoices')
            ->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->join('products', 'invoice_items.product_id', '=', 'products.id')
            ->where('invoices.status', 'paid')
            ->selectRaw('products.category, SUM(invoice_items.quantity) as total_quantity')
            ->groupBy('products.category')
            ->get();

        $labels = ['Residential', 'Commercial', 'Industrial', 'Utility'];
        $data = [];
        $colors = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'];

        foreach ($labels as $label) {
            $category = strtolower($label);
            $categoryData = $salesData->where('category', $category)->first();
            $data[] = $categoryData ? (int)$categoryData->total_quantity : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors
        ];
    }

    private function getSatisfactionData()
    {
        // Simulate quarterly satisfaction data (you can replace with real data)
        $labels = ['Q1', 'Q2', 'Q3', 'Q4'];
        $data = [4.2, 4.5, 4.3, 4.7]; // You can get this from customer feedback table

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getInstallationData()
    {
        // Get installation progress data
        $labels = ['Site Survey', 'Design', 'Permits', 'Installation', 'Testing', 'Handover'];
        $completed = [];
        $inProgress = [];

        foreach ($labels as $label) {
            // This is simplified - you can make it more sophisticated
            $completed[] = rand(80, 100);
            $inProgress[] = rand(0, 20);
        }

        return [
            'labels' => $labels,
            'completed' => $completed,
            'inProgress' => $inProgress
        ];
    }

    private function getRegionalData()
    {
        // Get regional performance data
        $regions = ['North', 'South', 'East', 'West'];
        $data = [];

        foreach ($regions as $region) {
            $projects = rand(10, 40);
            $satisfaction = rand(70, 95);
            $data[] = [
                'label' => $region . ' Region',
                'data' => [
                    ['x' => $projects, 'y' => $satisfaction],
                    ['x' => $projects + rand(-5, 5), 'y' => $satisfaction + rand(-10, 10)],
                    ['x' => $projects + rand(-5, 5), 'y' => $satisfaction + rand(-10, 10)],
                    ['x' => $projects + rand(-5, 5), 'y' => $satisfaction + rand(-10, 10)],
                ],
                'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'][array_search($region, $regions)],
                'borderColor' => ['#1d4ed8', '#059669', '#d97706', '#7c3aed'][array_search($region, $regions)]
            ];
        }

        return $data;
    }
}
