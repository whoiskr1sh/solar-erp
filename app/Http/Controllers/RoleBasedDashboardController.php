<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Task;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Document;
use App\Models\User;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\MaterialRequest;
use App\Models\QualityCheck;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Complaint;
use App\Models\AMC;
use App\Models\OMMaintenance;
use Illuminate\Support\Facades\Auth;

class RoleBasedDashboardController extends Controller
{
    /**
     * Show dashboard based on user role
     */
    public function showDashboard(Request $request)
    {
        $user = Auth::user();
        $role = $user->roles->first();
        
        if (!$role) {
            return redirect('/dashboard');
        }

        switch ($role->name) {
            case 'SUPER ADMIN':
                return $this->superAdminDashboard();
            case 'SALES MANAGER':
                return $this->salesManagerDashboard();
            case 'TELE SALES':
                return $this->teleSalesDashboard();
            case 'FIELD SALES':
                return $this->fieldSalesDashboard();
            case 'PROJECT MANAGER':
                return $this->projectManagerDashboard();
            case 'PROJECT ENGINEER':
                return $this->projectEngineerDashboard();
            case 'LIASONING EXECUTIVE':
                return $this->liaisoningDashboard();
            case 'QUALITY ENGINEER':
                return $this->qualityEngineerDashboard();
            case 'PURCHASE MANAGER/EXECUTIVE':
                return $this->purchaseManagerDashboard();
            case 'ACCOUNT EXECUTIVE':
                return $this->accountExecutiveDashboard();
            case 'STORE EXECUTIVE':
                return $this->storeExecutiveDashboard();
            case 'SERVICE ENGINEER':
                return $this->serviceEngineerDashboard();
            case 'HR MANAGER':
                return $this->hrManagerDashboard();
            default:
                return $this->defaultDashboard();
        }
    }

    /**
     * Super Admin Dashboard - Complete system overview
     */
    private function superAdminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_leads' => Lead::count(),
            'total_projects' => Project::count(),
            'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
            'active_projects' => Project::where('status', 'active')->count(),
            'pending_tasks' => Task::where('status', 'pending')->count(),
            'total_vendors' => Vendor::count(),
            'total_employees' => Employee::count(),
        ];

        // Get call counts by sub-coordinators (TELE SALES, FIELD SALES)
        $subCoordinators = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['TELE SALES', 'FIELD SALES']);
        })->where('is_active', true)->get();

        $subCoordinatorCallStats = [];
        foreach ($subCoordinators as $coordinator) {
            $totalCalls = Lead::where('assigned_user_id', $coordinator->id)
                ->sum('call_count');
            
            $todayCalls = Lead::where('assigned_user_id', $coordinator->id)
                ->whereDate('updated_at', today())
                ->where('last_updated_by', $coordinator->id)
                ->count();
            
            $subCoordinatorCallStats[] = [
                'id' => $coordinator->id,
                'name' => $coordinator->name,
                'role' => $coordinator->roles->first()->name ?? 'N/A',
                'total_calls' => $totalCalls,
                'today_calls' => $todayCalls,
                'total_leads' => Lead::where('assigned_user_id', $coordinator->id)->count(),
            ];
        }

        $recentLeads = Lead::with('assignedUser')->latest()->limit(5)->get();
        $recentProjects = Project::with('client', 'projectManager')->latest()->limit(5)->get();
        $recentUsers = User::latest()->limit(5)->get();

        return view('dashboards.super-admin', compact('stats', 'recentLeads', 'recentProjects', 'recentUsers', 'subCoordinatorCallStats'));
    }

    /**
     * Sales Manager Dashboard - CRM Focus Only
     */
    private function salesManagerDashboard()
    {
        $stats = [
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'converted_leads' => Lead::where('status', 'converted')->count(),
            'total_customers' => 0, // Customer model not available
            'total_quotations' => Quotation::count(),
            'pending_quotations' => Quotation::where('status', 'pending')->count(),
            'total_projects' => Project::count(), // Added for sales manager dashboard
            'active_projects' => Project::where('status', 'active')->count(), // Added for sales manager dashboard
            'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount') ?? 0, // Added for sales manager dashboard
        ];

        $recentLeads = Lead::with('assignedUser')->latest()->limit(5)->get();
        $recentCustomers = collect(); // Empty collection for now
        $recentQuotations = Quotation::latest()->limit(5)->get();
        $recentProjects = Project::with('client')->latest()->limit(5)->get(); // Added missing variable

        return view('dashboards.sales-manager', compact('stats', 'recentLeads', 'recentCustomers', 'recentQuotations', 'recentProjects'));
    }

    /**
     * Tele Sales Dashboard - Enhanced with Dynamic Data
     */
    private function teleSalesDashboard()
    {
        $userId = Auth::id();
        
        // Lead Statistics - All leads for TELE SALES
        $totalLeads = Lead::count();
        $newLeads = Lead::where('status', 'new')->count();
        $contactedLeads = Lead::where('status', 'contacted')->count();
        $qualifiedLeads = Lead::where('status', 'qualified')->count();
        $convertedLeads = Lead::where('status', 'converted')->count();
        $followUpLeads = Lead::where('status', 'follow_up')->count();
        
        // Quotation Statistics
        $totalQuotations = Quotation::where('created_by', $userId)->count();
        $pendingQuotations = Quotation::where('created_by', $userId)->where('status', 'pending')->count();
        $approvedQuotations = Quotation::where('created_by', $userId)->where('status', 'approved')->count();
        $acceptedQuotations = Quotation::where('created_by', $userId)->where('status', 'accepted')->count();
        $successfulQuotations = $approvedQuotations + $acceptedQuotations; // Both approved and accepted are success
        $rejectedQuotations = Quotation::where('created_by', $userId)->where('status', 'rejected')->count();
        
        // Document Statistics
        $totalDocuments = Document::where('created_by', $userId)->count();
        $recentDocumentsCount = Document::where('created_by', $userId)->where('created_at', '>=', now()->subDays(7))->count();
        
        // Recent Activity - All leads for TELE SALES
        $recentLeads = Lead::latest()->limit(5)->get();
        $recentQuotations = Quotation::where('created_by', $userId)->latest()->limit(5)->get();
        $recentDocuments = Document::where('created_by', $userId)->latest()->limit(5)->get();
        
        // Performance Metrics
        $conversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 2) : 0;
        $quotationSuccessRate = $totalQuotations > 0 ? round(($successfulQuotations / $totalQuotations) * 100, 2) : 0;
        
        // This Month Performance - All leads for TELE SALES
        $thisMonthLeads = Lead::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $thisMonthQuotations = Quotation::where('created_by', $userId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $thisMonthConversions = Lead::where('status', 'converted')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        $stats = [
            // Lead Stats
            'total_leads' => $totalLeads,
            'new_leads' => $newLeads,
            'contacted_leads' => $contactedLeads,
            'qualified_leads' => $qualifiedLeads,
            'converted_leads' => $convertedLeads,
            'follow_up_leads' => $followUpLeads,
            
            // Quotation Stats
            'total_quotations' => $totalQuotations,
            'pending_quotations' => $pendingQuotations,
            'approved_quotations' => $approvedQuotations,
            'accepted_quotations' => $acceptedQuotations,
            'successful_quotations' => $successfulQuotations,
            'rejected_quotations' => $rejectedQuotations,
            
            // Document Stats
            'total_documents' => $totalDocuments,
            'recent_documents' => $recentDocumentsCount,
            
            // Performance Metrics
            'conversion_rate' => $conversionRate,
            'quotation_success_rate' => $quotationSuccessRate,
            
            // This Month Performance
            'this_month_leads' => $thisMonthLeads,
            'this_month_quotations' => $thisMonthQuotations,
            'this_month_conversions' => $thisMonthConversions,
        ];

        return view('dashboards.tele-sales', compact('stats', 'recentLeads', 'recentQuotations', 'recentDocuments'));
    }

    /**
     * Field Sales Dashboard
     */
    private function fieldSalesDashboard()
    {
        $userId = Auth::id();

        // Lead Statistics - All leads for FIELD SALES
        $totalLeads = Lead::count();
        $newLeads = Lead::where('status', 'new')->count();
        $contactedLeads = Lead::where('status', 'contacted')->count();
        $qualifiedLeads = Lead::where('status', 'qualified')->count();
        $convertedLeads = Lead::where('status', 'converted')->count();
        $followUpLeads = Lead::where('status', 'follow_up')->count();

        // Quotation Statistics
        $totalQuotations = Quotation::where('created_by', $userId)->count();
        $pendingQuotations = Quotation::where('created_by', $userId)->where('status', 'pending')->count();
        $approvedQuotations = Quotation::where('created_by', $userId)->where('status', 'approved')->count();
        $acceptedQuotations = Quotation::where('created_by', $userId)->where('status', 'accepted')->count();
        $successfulQuotations = $approvedQuotations + $acceptedQuotations; // Both approved and accepted are success
        $rejectedQuotations = Quotation::where('created_by', $userId)->where('status', 'rejected')->count();

        // Document Statistics
        $totalDocuments = Document::where('created_by', $userId)->count();
        $recentDocumentsCount = Document::where('created_by', $userId)->where('created_at', '>=', now()->subDays(7))->count();

        // Recent Activity - All leads for FIELD SALES
        $recentLeads = Lead::latest()->limit(5)->get();
        $recentQuotations = Quotation::where('created_by', $userId)->latest()->limit(5)->get();
        $recentDocuments = Document::where('created_by', $userId)->latest()->limit(5)->get();

        // Performance Metrics
        $conversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 2) : 0;
        $quotationSuccessRate = $totalQuotations > 0 ? round(($successfulQuotations / $totalQuotations) * 100, 2) : 0;

        // This Month Performance - All leads for FIELD SALES
        $thisMonthLeads = Lead::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $thisMonthQuotations = Quotation::where('created_by', $userId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $thisMonthConversions = Lead::where('status', 'converted')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        $stats = [
            // Lead Stats
            'total_leads' => $totalLeads,
            'new_leads' => $newLeads,
            'contacted_leads' => $contactedLeads,
            'qualified_leads' => $qualifiedLeads,
            'converted_leads' => $convertedLeads,
            'follow_up_leads' => $followUpLeads,

            // Quotation Stats
            'total_quotations' => $totalQuotations,
            'pending_quotations' => $pendingQuotations,
            'approved_quotations' => $approvedQuotations,
            'accepted_quotations' => $acceptedQuotations,
            'successful_quotations' => $successfulQuotations,
            'rejected_quotations' => $rejectedQuotations,

            // Document Stats
            'total_documents' => $totalDocuments,
            'recent_documents' => $recentDocumentsCount,

            // Performance Metrics
            'conversion_rate' => $conversionRate,
            'quotation_success_rate' => $quotationSuccessRate,

            // This Month Performance
            'this_month_leads' => $thisMonthLeads,
            'this_month_quotations' => $thisMonthQuotations,
            'this_month_conversions' => $thisMonthConversions,
        ];

        return view('dashboards.field-sales', compact('stats', 'recentLeads', 'recentQuotations', 'recentDocuments'));
    }

    /**
     * Project Manager Dashboard
     */
    private function projectManagerDashboard()
    {
        $stats = [
            'my_projects' => Project::where('project_manager_id', Auth::id())->count(),
            'active_projects' => Project::where('project_manager_id', Auth::id())->where('status', 'active')->count(),
            'completed_projects' => Project::where('project_manager_id', Auth::id())->where('status', 'completed')->count(),
            'total_tasks' => Task::where('assigned_to', Auth::id())->count(),
            'pending_tasks' => Task::where('assigned_to', Auth::id())->where('status', 'pending')->count(),
            'material_requests' => MaterialRequest::where('requested_by', Auth::id())->count(),
            'pending_material_requests' => MaterialRequest::where('requested_by', Auth::id())->where('status', 'pending')->count(),
        ];

        $myProjects = Project::where('project_manager_id', Auth::id())->latest()->limit(5)->get();
        $myTasks = Task::where('assigned_to', Auth::id())->latest()->limit(5)->get();
        $materialRequests = MaterialRequest::where('requested_by', Auth::id())->latest()->limit(5)->get();

        return view('dashboards.project-manager', compact('stats', 'myProjects', 'myTasks', 'materialRequests'));
    }

    /**
     * Project Engineer Dashboard
     */
    private function projectEngineerDashboard()
    {
        $stats = [
            'assigned_projects' => Project::where('project_engineer', Auth::id())->count(),
            'active_projects' => Project::where('project_engineer', Auth::id())->where('status', 'active')->count(),
            'my_tasks' => Task::where('assigned_to', Auth::id())->count(),
            'pending_tasks' => Task::where('assigned_to', Auth::id())->where('status', 'pending')->count(),
            'quality_checks' => QualityCheck::where('checked_by', Auth::id())->count(),
            'pending_quality_checks' => QualityCheck::where('checked_by', Auth::id())->where('status', 'pending')->count(),
        ];

        $assignedProjects = Project::where('project_engineer', Auth::id())->latest()->limit(5)->get();
        $myTasks = Task::where('assigned_to', Auth::id())->latest()->limit(5)->get();
        $qualityChecks = QualityCheck::where('checked_by', Auth::id())->latest()->limit(5)->get();

        return view('dashboards.project-engineer', compact('stats', 'assignedProjects', 'myTasks', 'qualityChecks'));
    }

    /**
     * Liaisoning Executive Dashboard
     */
    private function liaisoningDashboard()
    {
        $stats = [
            'permits_managed' => 0, // Add permit model when available
            'approvals_pending' => 0, // Add approval model when available
            'documents_uploaded' => \App\Models\Document::where('created_by', Auth::id())->count(),
            'vendors_registered' => Vendor::where('created_by', Auth::id())->count(),
            'projects_coordinated' => Project::where('liaisoning_officer', Auth::id())->count(),
        ];

        $recentDocuments = \App\Models\Document::where('created_by', Auth::id())->latest()->limit(5)->get();
        $recentVendors = Vendor::where('created_by', Auth::id())->latest()->limit(5)->get();

        return view('dashboards.liaisoning', compact('stats', 'recentDocuments', 'recentVendors'));
    }

    /**
     * Quality Engineer Dashboard
     */
    private function qualityEngineerDashboard()
    {
        $stats = [
            'quality_checks' => QualityCheck::where('checked_by', Auth::id())->count(),
            'pending_checks' => QualityCheck::where('checked_by', Auth::id())->where('status', 'pending')->count(),
            'approved_checks' => QualityCheck::where('checked_by', Auth::id())->where('status', 'approved')->count(),
            'rejected_checks' => QualityCheck::where('checked_by', Auth::id())->where('status', 'rejected')->count(),
            'inventory_audits' => \App\Models\InventoryAudit::where('audited_by', Auth::id())->count(),
        ];

        $recentQualityChecks = QualityCheck::where('checked_by', Auth::id())->latest()->limit(5)->get();
        $recentAudits = \App\Models\InventoryAudit::where('audited_by', Auth::id())->latest()->limit(5)->get();

        return view('dashboards.quality-engineer', compact('stats', 'recentQualityChecks', 'recentAudits'));
    }

    /**
     * Purchase Manager Dashboard
     */
    private function purchaseManagerDashboard()
    {
        $stats = [
            'total_vendors' => Vendor::count(),
            'active_vendors' => Vendor::where('status', 'active')->count(),
            'purchase_orders' => PurchaseOrder::count(),
            'pending_orders' => PurchaseOrder::where('status', 'pending')->count(),
            'approved_orders' => PurchaseOrder::where('status', 'approved')->count(),
            'total_rfq' => \App\Models\RFQ::count(),
            'pending_rfq' => \App\Models\RFQ::where('status', 'pending')->count(),
            'material_requests' => MaterialRequest::count(),
            'pending_material_requests' => MaterialRequest::where('status', 'pending')->count(),
        ];

        $recentPurchaseOrders = PurchaseOrder::latest()->limit(5)->get();
        $recentVendors = Vendor::latest()->limit(5)->get();
        $recentRFQ = \App\Models\RFQ::latest()->limit(5)->get();
        $materialRequests = MaterialRequest::latest()->limit(5)->get();

        return view('dashboards.purchase-manager', compact('stats', 'recentPurchaseOrders', 'recentVendors', 'recentRFQ', 'materialRequests'));
    }

    /**
     * Account Executive Dashboard
     */
    private function accountExecutiveDashboard()
    {
        $stats = [
            'total_invoices' => Invoice::count(),
            'pending_invoices' => Invoice::where('status', 'sent')->count(),
            'paid_invoices' => Invoice::where('status', 'paid')->count(),
            'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
            'pending_payments' => \App\Models\PaymentRequest::where('status', 'pending')->count(),
            'approved_payments' => \App\Models\PaymentRequest::where('status', 'approved')->count(),
            'total_quotations' => Quotation::count(),
            'pending_quotations' => Quotation::where('status', 'pending')->count(),
        ];

        $recentInvoices = Invoice::latest()->limit(5)->get();
        $recentPayments = \App\Models\PaymentRequest::latest()->limit(5)->get();
        $recentQuotations = Quotation::latest()->limit(5)->get();

        return view('dashboards.account-executive', compact('stats', 'recentInvoices', 'recentPayments', 'recentQuotations'));
    }

    /**
     * Store Executive Dashboard
     */
    private function storeExecutiveDashboard()
    {
        $stats = [
            'total_products' => Product::count(),
            'low_stock_products' => Product::whereRaw('current_stock <= min_stock_level')->count(),
            'material_requests' => MaterialRequest::count(),
            'pending_requests' => MaterialRequest::where('status', 'pending')->count(),
            'approved_requests' => MaterialRequest::where('status', 'approved')->count(),
            'grn_received' => \App\Models\GRN::count(),
            'pending_grn' => \App\Models\GRN::where('status', 'pending')->count(),
        ];

        $lowStockProducts = Product::whereRaw('current_stock <= min_stock_level')->latest()->limit(5)->get();
        $recentMaterialRequests = MaterialRequest::latest()->limit(5)->get();
        $recentGRN = \App\Models\GRN::latest()->limit(5)->get();

        return view('dashboards.store-executive', compact('stats', 'lowStockProducts', 'recentMaterialRequests', 'recentGRN'));
    }

    /**
     * Service Engineer Dashboard
     */
    private function serviceEngineerDashboard()
    {
        $stats = [
            'service_requests' => Complaint::count(),
            'pending_services' => Complaint::where('status', 'pending')->count(),
            'completed_services' => Complaint::where('status', 'resolved')->count(),
            'total_complaints' => Complaint::count(),
            'open_complaints' => Complaint::where('status', 'open')->count(),
            'resolved_complaints' => Complaint::where('status', 'resolved')->count(),
            'amc_contracts' => AMC::count(),
            'active_amc' => AMC::where('status', 'active')->count(),
        ];

        $recentServiceRequests = Complaint::latest()->limit(5)->get();
        $recentComplaints = Complaint::latest()->limit(5)->get();
        $recentAMC = AMC::latest()->limit(5)->get();

        return view('dashboards.service-engineer', compact('stats', 'recentServiceRequests', 'recentComplaints', 'recentAMC'));
    }

    /**
     * HR Manager Dashboard
     */
    private function hrManagerDashboard()
    {
        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('status', 'active')->count(),
            'total_attendance' => Attendance::count(),
            'today_attendance' => Attendance::whereDate('date', today())->count(),
            'leave_requests' => LeaveRequest::count(),
            'pending_leaves' => LeaveRequest::where('status', 'pending')->count(),
            'approved_leaves' => LeaveRequest::where('status', 'approved')->count(),
            'total_payroll' => \App\Models\Payroll::count(),
        ];

        $recentEmployees = Employee::latest()->limit(5)->get();
        $recentLeaveRequests = LeaveRequest::latest()->limit(5)->get();
        $recentAttendance = Attendance::latest()->limit(5)->get();

        return view('dashboards.hr-manager', compact('stats', 'recentEmployees', 'recentLeaveRequests', 'recentAttendance'));
    }

    /**
     * Default Dashboard (fallback)
     */
    private function defaultDashboard()
    {
        $stats = [
            'total_leads' => Lead::count(),
            'total_projects' => Project::count(),
            'total_tasks' => Task::count(),
            'total_invoices' => Invoice::count(),
        ];

        return view('dashboard', compact('stats'));
    }
}
