<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CrmDashboardController;
use App\Http\Controllers\MobileCrmController;
use App\Http\Controllers\MobileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DailyProgressReportController;
use App\Http\Controllers\SiteWarehouseController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseRequisitionController;
use App\Http\Controllers\PaymentRequestController;
use App\Http\Controllers\RFQController;
use App\Http\Controllers\VendorRegistrationController;
use App\Http\Controllers\CostingController;
use App\Http\Controllers\ChannelPartnerController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\EscalationController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ResourceAllocationController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleBasedDashboardController;
use App\Http\Controllers\ProjectSubModuleController;
use App\Http\Controllers\PurchaseSubModuleController;

// Public routes
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public template download (no auth required)
Route::get('/leads/download-template', [LeadController::class, 'downloadTemplate'])->name('leads.download-template');

// Test route for download (no auth required)
Route::get('/test-download', function() {
    $csvContent = "Name,Phone,Email\n";
    $csvContent .= "John Doe,9876543210,john@example.com\n";
    $csvContent .= "Jane Smith,9876543211,jane@example.com\n";
    
    return response($csvContent, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="test.csv"',
    ]);
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Role-based dashboard routes
    Route::get('/dashboard/sales-manager', [RoleBasedDashboardController::class, 'showDashboard'])->name('dashboard.sales-manager');
    Route::get('/dashboard/tele-sales', [RoleBasedDashboardController::class, 'showDashboard'])->name('dashboard.tele-sales');
    Route::get('/dashboard/field-sales', [RoleBasedDashboardController::class, 'showDashboard'])->name('dashboard.field-sales');
    Route::get('/dashboard/project-manager', [RoleBasedDashboardController::class, 'showDashboard'])->name('dashboard.project-manager');
    Route::get('/dashboard/project-engineer', [RoleBasedDashboardController::class, 'showDashboard'])->name('dashboard.project-engineer');
    Route::get('/dashboard/liaisoning', [RoleBasedDashboardController::class, 'showDashboard'])->name('dashboard.liaisoning');
    Route::get('/dashboard/quality-engineer', [RoleBasedDashboardController::class, 'showDashboard'])->name('dashboard.quality-engineer');
    Route::get('/dashboard/purchase-manager', [RoleBasedDashboardController::class, 'showDashboard'])->name('dashboard.purchase-manager');
    Route::get('/dashboard/account-executive', [RoleBasedDashboardController::class, 'showDashboard'])->name('dashboard.account-executive');
    Route::get('/dashboard/store-executive', [RoleBasedDashboardController::class, 'showDashboard'])->name('dashboard.store-executive');
    Route::get('/dashboard/service-engineer', [RoleBasedDashboardController::class, 'showDashboard'])->name('dashboard.service-engineer');
    Route::get('/dashboard/hr-manager', [RoleBasedDashboardController::class, 'showDashboard'])->name('dashboard.hr-manager');
    
    // Profile and Settings Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::post('/profile/enable-2fa', [ProfileController::class, 'enable2FA'])->name('profile.enable-2fa');
    Route::post('/profile/disable-2fa', [ProfileController::class, 'disable2FA'])->name('profile.disable-2fa');
    
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/reset', [SettingsController::class, 'resetDefaults'])->name('settings.reset');
    
    // Analytics Dashboard
    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'dashboard'])->name('analytics.dashboard');
    Route::get('/analytics/chart-data', [AnalyticsController::class, 'getChartData'])->name('analytics.chart-data');
    
    // CRM Dashboard
    Route::get('/crm-dashboard', [CrmDashboardController::class, 'index'])->name('crm.dashboard');
    
    // Leads - Specific routes must come BEFORE resource route to avoid conflicts
    Route::get('/leads/request-reassignment', [App\Http\Controllers\LeadReassignmentRequestController::class, 'create'])->name('leads.request-reassignment');
    Route::post('/leads/reassignment-requests', [App\Http\Controllers\LeadReassignmentRequestController::class, 'store'])->name('leads.reassignment-requests.store');
    Route::get('/leads/reassigned', [App\Http\Controllers\LeadController::class, 'reassigned'])->name('leads.reassigned');
    Route::post('/leads/import', [LeadController::class, 'import'])->name('leads.import');
    Route::get('/leads/export', [LeadController::class, 'export'])->name('leads.export');
    Route::put('/leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.update-status');
    Route::post('/leads/lookup-by-email', [LeadController::class, 'lookupByEmail'])->name('leads.lookup-by-email');
    Route::post('/leads/{lead}/reveal-contact', [LeadController::class, 'revealContact'])->name('leads.reveal-contact');
    
    // Leads Resource Route (must come after specific routes)
    Route::resource('leads', LeadController::class);
    
    // User Availability
    Route::post('/user/availability/toggle', [App\Http\Controllers\UserAvailabilityController::class, 'toggle'])->name('user.availability.toggle');
    
    // Projects
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/{project}/dashboard', [ProjectController::class, 'dashboard'])->name('projects.dashboard');
    Route::get('/projects/{project}/gantt', [ProjectController::class, 'gantt'])->name('projects.gantt');
    Route::post('/projects/{project}/update-task-dates', [ProjectController::class, 'updateTaskDates'])->name('projects.update-task-dates');
    Route::post('/projects/{project}/update-task-progress', [ProjectController::class, 'updateTaskProgress'])->name('projects.update-task-progress');
    
    
    // Products/Inventory
    Route::get('/products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
    Route::get('/products/{product}/stock-adjustment', [ProductController::class, 'showStockAdjustment'])->name('products.stock-adjustment.show');
    Route::resource('products', ProductController::class);
    Route::post('/products/{product}/stock-adjustment', [ProductController::class, 'stockAdjustment'])->name('products.stock-adjustment');
    
        // Invoices
        Route::resource('invoices', InvoiceController::class);
        Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
        Route::get('/invoices/{invoice}/preview', [InvoiceController::class, 'preview'])->name('invoices.preview');
        Route::post('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.mark-paid');
        Route::post('/invoices/{invoice}/send-email', [InvoiceController::class, 'sendEmail'])->name('invoices.send-email');
        
        // Quotations
        Route::resource('quotations', QuotationController::class);
        Route::get('/quotations/{quotation}/pdf', [QuotationController::class, 'pdf'])->name('quotations.pdf');
        Route::get('/quotations/{quotation}/preview', [QuotationController::class, 'preview'])->name('quotations.preview');
        Route::post('/quotations/{quotation}/convert-to-invoice', [QuotationController::class, 'convertToInvoice'])->name('quotations.convert-to-invoice');
        Route::post('/quotations/{quotation}/send-email', [QuotationController::class, 'sendEmail'])->name('quotations.send-email');
        Route::get('/quotations/{quotation}/create-revision', [QuotationController::class, 'createRevision'])->name('quotations.create-revision');
        Route::post('/quotations/{quotation}/store-revision', [QuotationController::class, 'storeRevision'])->name('quotations.store-revision');
        
        // Documents
        Route::resource('documents', DocumentController::class);
        Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
        Route::get('/documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');
        
        // Vendors
        Route::resource('vendors', VendorController::class);
        
        // Tasks
        Route::resource('tasks', TaskController::class);
        Route::put('tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
        
        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
            Route::get('/leads', [ReportController::class, 'leads'])->name('leads');
            Route::get('/projects', [ReportController::class, 'projects'])->name('projects');
            Route::get('/tasks', [ReportController::class, 'tasks'])->name('tasks');
            Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
            Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
            Route::get('/vendors', [ReportController::class, 'vendors'])->name('vendors');
        });
        
        // Notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
            Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
            Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
            Route::post('/reset', [NotificationController::class, 'resetNotifications'])->name('reset');
        });
        
        // Costing/Budgeting
        Route::prefix('costing')->name('costing.')->group(function () {
            Route::get('/', [CostingController::class, 'index'])->name('index');
            Route::get('/create', [CostingController::class, 'create'])->name('create');
            Route::post('/', [CostingController::class, 'store'])->name('store');
            Route::get('/{costing}', [CostingController::class, 'show'])->name('show');
            Route::get('/{costing}/edit', [CostingController::class, 'edit'])->name('edit');
            Route::put('/{costing}', [CostingController::class, 'update'])->name('update');
            Route::delete('/{costing}', [CostingController::class, 'destroy'])->name('destroy');
            Route::post('/{costing}/approve', [CostingController::class, 'approve'])->name('approve');
            Route::post('/{costing}/reject', [CostingController::class, 'reject'])->name('reject');
            Route::get('/export', [CostingController::class, 'export'])->name('export');
        });

        // Channel Partners
        Route::prefix('channel-partners')->name('channel-partners.')->group(function () {
            Route::get('/', [ChannelPartnerController::class, 'index'])->name('index');
            Route::get('/create', [ChannelPartnerController::class, 'create'])->name('create');
            Route::post('/', [ChannelPartnerController::class, 'store'])->name('store');
            Route::get('/export', [ChannelPartnerController::class, 'export'])->name('export');
            Route::get('/{channelPartner}', [ChannelPartnerController::class, 'show'])->name('show');
            Route::get('/{channelPartner}/edit', [ChannelPartnerController::class, 'edit'])->name('edit');
            Route::put('/{channelPartner}', [ChannelPartnerController::class, 'update'])->name('update');
            Route::delete('/{channelPartner}', [ChannelPartnerController::class, 'destroy'])->name('destroy');
            Route::post('/{channelPartner}/activate', [ChannelPartnerController::class, 'activate'])->name('activate');
            Route::post('/{channelPartner}/deactivate', [ChannelPartnerController::class, 'deactivate'])->name('deactivate');
            Route::post('/{channelPartner}/suspend', [ChannelPartnerController::class, 'suspend'])->name('suspend');
        });

        // Commissions
        Route::prefix('commissions')->name('commissions.')->group(function () {
            Route::get('/', [CommissionController::class, 'index'])->name('index');
            Route::get('/create', [CommissionController::class, 'create'])->name('create');
            Route::post('/', [CommissionController::class, 'store'])->name('store');
            Route::get('/export', [CommissionController::class, 'export'])->name('export');
            Route::get('/{commission}', [CommissionController::class, 'show'])->name('show');
            Route::get('/{commission}/edit', [CommissionController::class, 'edit'])->name('edit');
            Route::put('/{commission}', [CommissionController::class, 'update'])->name('update');
            Route::delete('/{commission}', [CommissionController::class, 'destroy'])->name('destroy');
            Route::post('/{commission}/approve', [CommissionController::class, 'approve'])->name('approve');
            Route::post('/{commission}/cancel', [CommissionController::class, 'cancel'])->name('cancel');
            Route::post('/{commission}/dispute', [CommissionController::class, 'dispute'])->name('dispute');
            Route::post('/{commission}/add-payment', [CommissionController::class, 'addPayment'])->name('add-payment');
            Route::post('/{commission}/mark-paid', [CommissionController::class, 'markAsPaid'])->name('mark-paid');
        });

        // Escalations
        Route::prefix('escalations')->name('escalations.')->group(function () {
            Route::get('/', [EscalationController::class, 'index'])->name('index');
            Route::get('/create', [EscalationController::class, 'create'])->name('create');
            Route::post('/', [EscalationController::class, 'store'])->name('store');
            Route::get('/export', [EscalationController::class, 'export'])->name('export');
            Route::get('/{escalation}', [EscalationController::class, 'show'])->name('show');
            Route::get('/{escalation}/edit', [EscalationController::class, 'edit'])->name('edit');
            Route::put('/{escalation}', [EscalationController::class, 'update'])->name('update');
            Route::delete('/{escalation}', [EscalationController::class, 'destroy'])->name('destroy');
            Route::post('/{escalation}/mark-in-progress', [EscalationController::class, 'markInProgress'])->name('mark-in-progress');
            Route::post('/{escalation}/mark-resolved', [EscalationController::class, 'markResolved'])->name('mark-resolved');
            Route::post('/{escalation}/mark-closed', [EscalationController::class, 'markClosed'])->name('mark-closed');
            Route::post('/{escalation}/escalate', [EscalationController::class, 'escalate'])->name('escalate');
            Route::post('/{escalation}/assign', [EscalationController::class, 'assign'])->name('assign');
        });
        
        // Mobile CRM
        Route::prefix('mobile-crm')->name('mobile-crm.')->group(function () {
            Route::get('/', [MobileCrmController::class, 'index'])->name('index');
            Route::get('/leads', [MobileCrmController::class, 'leads'])->name('leads');
            Route::get('/tasks', [MobileCrmController::class, 'tasks'])->name('tasks');
            Route::get('/projects', [MobileCrmController::class, 'projects'])->name('projects');
            Route::get('/invoices', [MobileCrmController::class, 'invoices'])->name('invoices');
            Route::get('/quotations', [MobileCrmController::class, 'quotations'])->name('quotations');
            Route::get('/quick-actions', [MobileCrmController::class, 'quickActions'])->name('quick-actions');
            Route::get('/notifications', [MobileCrmController::class, 'notifications'])->name('notifications');
            Route::get('/profile', [MobileCrmController::class, 'profile'])->name('profile');
            Route::post('/profile', [MobileCrmController::class, 'updateProfile'])->name('profile.update');
            Route::get('/search', [MobileCrmController::class, 'search'])->name('search');
        });
        
        // Mobile App Routes
        Route::prefix('mobile')->name('mobile.')->group(function () {
            Route::get('/', [MobileController::class, 'index'])->name('index');
            Route::get('/projects', [MobileController::class, 'projects'])->name('projects');
            Route::get('/expenses', [MobileController::class, 'expenses'])->name('expenses');
            Route::get('/commissions', [MobileController::class, 'commissions'])->name('commissions');
            Route::get('/tasks', [MobileController::class, 'tasks'])->name('tasks');
            Route::get('/resources', [MobileController::class, 'resources'])->name('resources');
            Route::get('/notifications', [MobileController::class, 'notifications'])->name('notifications');
            Route::get('/quick-actions', [MobileController::class, 'quickActions'])->name('quick-actions');
            Route::get('/profile', [MobileController::class, 'profile'])->name('profile');
        });
        
        // Daily Progress Report Routes
        Route::prefix('dpr')->name('dpr.')->group(function () {
            Route::get('/', [DailyProgressReportController::class, 'index'])->name('index');
            Route::get('/dashboard', [DailyProgressReportController::class, 'dashboard'])->name('dashboard');
            Route::get('/create', [DailyProgressReportController::class, 'create'])->name('create');
            Route::post('/', [DailyProgressReportController::class, 'store'])->name('store');
            Route::get('/{dpr}', [DailyProgressReportController::class, 'show'])->name('show');
            Route::get('/{dpr}/edit', [DailyProgressReportController::class, 'edit'])->name('edit');
            Route::put('/{dpr}', [DailyProgressReportController::class, 'update'])->name('update');
            Route::delete('/{dpr}', [DailyProgressReportController::class, 'destroy'])->name('destroy');
            Route::post('/{dpr}/approve', [DailyProgressReportController::class, 'approve'])->name('approve');
        });
        
        // Site Warehouse Routes
        Route::prefix('site-warehouses')->name('site-warehouses.')->group(function () {
            Route::get('/', [SiteWarehouseController::class, 'index'])->name('index');
            Route::get('/create', [SiteWarehouseController::class, 'create'])->name('create');
            Route::post('/', [SiteWarehouseController::class, 'store'])->name('store');
            Route::get('/{siteWarehouse}', [SiteWarehouseController::class, 'show'])->name('show');
            Route::get('/{siteWarehouse}/edit', [SiteWarehouseController::class, 'edit'])->name('edit');
            Route::put('/{siteWarehouse}', [SiteWarehouseController::class, 'update'])->name('update');
            Route::delete('/{siteWarehouse}', [SiteWarehouseController::class, 'destroy'])->name('destroy');
        });

    // Purchase Order Routes
    Route::prefix('purchase-orders')->name('purchase-orders.')->group(function () {
        Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');
        Route::get('/dashboard', [PurchaseOrderController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [PurchaseOrderController::class, 'create'])->name('create');
        Route::post('/', [PurchaseOrderController::class, 'store'])->name('store');
        Route::get('/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('show');
        Route::get('/{purchaseOrder}/edit', [PurchaseOrderController::class, 'edit'])->name('edit');
        Route::put('/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('update');
        Route::delete('/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('destroy');
        Route::post('/{purchaseOrder}/approve', [PurchaseOrderController::class, 'approve'])->name('approve');
    });

    // Purchase Requisition Routes
    Route::prefix('purchase-requisitions')->name('purchase-requisitions.')->group(function () {
        Route::get('/', [PurchaseRequisitionController::class, 'index'])->name('index');
        Route::get('/dashboard', [PurchaseRequisitionController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [PurchaseRequisitionController::class, 'create'])->name('create');
        Route::post('/', [PurchaseRequisitionController::class, 'store'])->name('store');
        Route::get('/{purchaseRequisition}', [PurchaseRequisitionController::class, 'show'])->name('show');
        Route::get('/{purchaseRequisition}/edit', [PurchaseRequisitionController::class, 'edit'])->name('edit');
        Route::put('/{purchaseRequisition}', [PurchaseRequisitionController::class, 'update'])->name('update');
        Route::delete('/{purchaseRequisition}', [PurchaseRequisitionController::class, 'destroy'])->name('destroy');
        Route::post('/{purchaseRequisition}/submit', [PurchaseRequisitionController::class, 'submit'])->name('submit');
        Route::post('/{purchaseRequisition}/approve', [PurchaseRequisitionController::class, 'approve'])->name('approve');
        Route::post('/{purchaseRequisition}/reject', [PurchaseRequisitionController::class, 'reject'])->name('reject');
        Route::post('/{purchaseRequisition}/convert-to-po', [PurchaseRequisitionController::class, 'convertToPO'])->name('convert-to-po');
    });

    // Payment Request Routes
    Route::prefix('payment-requests')->name('payment-requests.')->group(function () {
        Route::get('/', [PaymentRequestController::class, 'index'])->name('index');
        Route::get('/dashboard', [PaymentRequestController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [PaymentRequestController::class, 'create'])->name('create');
        Route::post('/', [PaymentRequestController::class, 'store'])->name('store');
        Route::get('/{paymentRequest}', [PaymentRequestController::class, 'show'])->name('show');
        Route::get('/{paymentRequest}/edit', [PaymentRequestController::class, 'edit'])->name('edit');
        Route::put('/{paymentRequest}', [PaymentRequestController::class, 'update'])->name('update');
        Route::delete('/{paymentRequest}', [PaymentRequestController::class, 'destroy'])->name('destroy');
        Route::post('/{paymentRequest}/submit', [PaymentRequestController::class, 'submit'])->name('submit');
        Route::post('/{paymentRequest}/approve', [PaymentRequestController::class, 'approve'])->name('approve');
        Route::post('/{paymentRequest}/reject', [PaymentRequestController::class, 'reject'])->name('reject');
        Route::post('/{paymentRequest}/mark-paid', [PaymentRequestController::class, 'markAsPaid'])->name('mark-paid');
    });

    // RFQ Routes
    Route::prefix('rfqs')->name('rfqs.')->group(function () {
        Route::get('/', [RFQController::class, 'index'])->name('index');
        Route::get('/dashboard', [RFQController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [RFQController::class, 'create'])->name('create');
        Route::post('/', [RFQController::class, 'store'])->name('store');
        Route::get('/{rfq}', [RFQController::class, 'show'])->name('show');
        Route::get('/{rfq}/edit', [RFQController::class, 'edit'])->name('edit');
        Route::put('/{rfq}', [RFQController::class, 'update'])->name('update');
        Route::delete('/{rfq}', [RFQController::class, 'destroy'])->name('destroy');
        Route::post('/{rfq}/send', [RFQController::class, 'send'])->name('send');
        Route::post('/{rfq}/approve', [RFQController::class, 'approve'])->name('approve');
    });

    // Vendor Registration Routes
    Route::prefix('vendor-registrations')->name('vendor-registrations.')->group(function () {
        Route::get('/', [VendorRegistrationController::class, 'index'])->name('index');
        Route::get('/dashboard', [VendorRegistrationController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [VendorRegistrationController::class, 'create'])->name('create');
        Route::post('/', [VendorRegistrationController::class, 'store'])->name('store');
        Route::get('/{vendorRegistration}', [VendorRegistrationController::class, 'show'])->name('show');
        Route::get('/{vendorRegistration}/edit', [VendorRegistrationController::class, 'edit'])->name('edit');
        Route::put('/{vendorRegistration}', [VendorRegistrationController::class, 'update'])->name('update');
        Route::delete('/{vendorRegistration}', [VendorRegistrationController::class, 'destroy'])->name('destroy');
        Route::post('/{vendorRegistration}/approve', [VendorRegistrationController::class, 'approve'])->name('approve');
        Route::post('/{vendorRegistration}/reject', [VendorRegistrationController::class, 'reject'])->name('reject');
    });

    // Material Request routes
    Route::prefix('material-requests')->name('material-requests.')->group(function () {
        Route::get('/', [App\Http\Controllers\MaterialRequestController::class, 'index'])->name('index');
        Route::get('/dashboard', [App\Http\Controllers\MaterialRequestController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [App\Http\Controllers\MaterialRequestController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\MaterialRequestController::class, 'store'])->name('store');
        Route::get('/{materialRequest}', [App\Http\Controllers\MaterialRequestController::class, 'show'])->name('show');
        Route::get('/{materialRequest}/edit', [App\Http\Controllers\MaterialRequestController::class, 'edit'])->name('edit');
        Route::put('/{materialRequest}', [App\Http\Controllers\MaterialRequestController::class, 'update'])->name('update');
        Route::delete('/{materialRequest}', [App\Http\Controllers\MaterialRequestController::class, 'destroy'])->name('destroy');
        Route::post('/{materialRequest}/approve', [App\Http\Controllers\MaterialRequestController::class, 'approve'])->name('approve');
        Route::post('/{materialRequest}/reject', [App\Http\Controllers\MaterialRequestController::class, 'reject'])->name('reject');
        Route::post('/{materialRequest}/mark-in-progress', [App\Http\Controllers\MaterialRequestController::class, 'markInProgress'])->name('mark-in-progress');
        Route::post('/{materialRequest}/mark-completed', [App\Http\Controllers\MaterialRequestController::class, 'markCompleted'])->name('mark-completed');
    });

    // Inventory Management Routes
    Route::prefix('inventory')->name('inventory.')->group(function () {
        // Inward-GRN
        Route::get('/inward-grn', [App\Http\Controllers\InventoryController::class, 'inwardGrn'])->name('inward-grn');
        
        // Outward-Delivery Challan/Note
        Route::get('/outward-delivery', [App\Http\Controllers\InventoryController::class, 'outwardDelivery'])->name('outward-delivery');
        Route::get('/outward-delivery/export', [App\Http\Controllers\InventoryController::class, 'exportOutwardDelivery'])->name('outward-delivery.export');
        
        // Warehouse Management
        Route::get('/warehouse-management', [App\Http\Controllers\InventoryController::class, 'warehouseManagement'])->name('warehouse-management');
        Route::get('/warehouse-management/export', [App\Http\Controllers\InventoryController::class, 'exportWarehouseManagement'])->name('warehouse-management.export');
        
        // Stock Ledger
        Route::get('/stock-ledger', [App\Http\Controllers\InventoryController::class, 'stockLedger'])->name('stock-ledger');
        Route::get('/stock-ledger/export', [App\Http\Controllers\InventoryController::class, 'exportStockLedger'])->name('stock-ledger.export');
        
        // Inward Quality Check
        Route::get('/inward-quality-check', [App\Http\Controllers\InventoryController::class, 'inwardQualityCheck'])->name('inward-quality-check');
        Route::get('/inward-quality-check/export', [App\Http\Controllers\InventoryController::class, 'exportInwardQualityCheck'])->name('inward-quality-check.export');
        
        // Stock Valuation Summary
        Route::get('/stock-valuation-summary', [App\Http\Controllers\InventoryController::class, 'stockValuationSummary'])->name('stock-valuation-summary');
        Route::get('/stock-valuation-summary/export', [App\Http\Controllers\InventoryController::class, 'exportStockValuationSummary'])->name('stock-valuation-summary.export');
        
        // Warehouse Location
        Route::get('/warehouse-location', [App\Http\Controllers\InventoryController::class, 'warehouseLocation'])->name('warehouse-location');
        Route::get('/warehouse-location/export', [App\Http\Controllers\InventoryController::class, 'exportWarehouseLocation'])->name('warehouse-location.export');
        
        // Inventory Audit
        Route::get('/inventory-audit', [App\Http\Controllers\InventoryController::class, 'inventoryAudit'])->name('inventory-audit');
        Route::get('/inventory-audit/export', [App\Http\Controllers\InventoryController::class, 'exportInventoryAudit'])->name('inventory-audit.export');
    });

    // GRN Routes
    Route::get('/grns/export', [App\Http\Controllers\GRNController::class, 'export'])->name('grns.export');
    Route::resource('grns', App\Http\Controllers\GRNController::class);
    Route::post('/grns/{grn}/verify', [App\Http\Controllers\GRNController::class, 'verify'])->name('grns.verify');
    
    // Demo Excel Download
    Route::get('/demo/leads-excel', function() {
        $fileName = 'demo-leads-' . now()->format('Y-m-d') . '.xlsx';
        $filePath = storage_path('app/public/' . $fileName);
        
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return response()->json(['error' => 'Demo file not found'], 404);
        }
    })->name('demo.leads-excel');

    // O&M Management Routes
    Route::prefix('om')->name('om.')->group(function () {
        Route::get('/complaint-management', [App\Http\Controllers\OMController::class, 'complaintManagement'])->name('complaint-management');
        Route::get('/complaint-management/export', [App\Http\Controllers\OMController::class, 'exportComplaintManagement'])->name('complaint-management.export');
        Route::get('/amc', [App\Http\Controllers\OMController::class, 'amc'])->name('amc');
        Route::get('/amc/export', [App\Http\Controllers\OMController::class, 'exportAmc'])->name('amc.export');
        Route::get('/om-project-management', [App\Http\Controllers\OMController::class, 'omProjectManagement'])->name('om-project-management');
        Route::get('/om-project-management/export', [App\Http\Controllers\OMController::class, 'exportOmProjectManagement'])->name('om-project-management.export');
    });

    // HR Management Routes
    // Delete Approval Routes (Admin only)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/delete-approvals', [App\Http\Controllers\DeleteApprovalController::class, 'index'])->name('delete-approvals.index');
        Route::get('/delete-approval/{id}/view', [App\Http\Controllers\DeleteApprovalController::class, 'view'])->name('delete-approval.view');
        Route::get('/delete-approval/{id}/approve', [App\Http\Controllers\DeleteApprovalController::class, 'approve'])->name('delete-approval.approve');
        Route::get('/delete-approval/{id}/reject', [App\Http\Controllers\DeleteApprovalController::class, 'showRejectForm'])->name('delete-approval.reject');
        Route::post('/delete-approval/{id}/reject', [App\Http\Controllers\DeleteApprovalController::class, 'reject'])->name('delete-approval.reject.post');
    });

    // Lead Backups Routes (Admin and Sales Manager)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/lead-backups', [App\Http\Controllers\LeadBackupController::class, 'index'])->name('lead-backups.index');
        Route::get('/lead-backups/{id}', [App\Http\Controllers\LeadBackupController::class, 'show'])->name('lead-backups.show');
        Route::post('/lead-backups/{id}/restore', [App\Http\Controllers\LeadBackupController::class, 'restore'])->name('lead-backups.restore');
        
        // Model Backups Routes (Generic backup system for all models)
        Route::get('/model-backups', [App\Http\Controllers\ModelBackupController::class, 'index'])->name('model-backups.index');
        Route::get('/model-backups/{id}', [App\Http\Controllers\ModelBackupController::class, 'show'])->name('model-backups.show');
        Route::post('/model-backups/{id}/restore', [App\Http\Controllers\ModelBackupController::class, 'restore'])->name('model-backups.restore');
        Route::delete('/model-backups/{id}', [App\Http\Controllers\ModelBackupController::class, 'destroy'])->name('model-backups.destroy');
        
        // Duplicate Lead Approvals Routes
        Route::get('/duplicate-lead-approvals', [App\Http\Controllers\DuplicateLeadApprovalController::class, 'index'])->name('duplicate-lead-approvals.index');
        Route::get('/duplicate-lead-approvals/{id}', [App\Http\Controllers\DuplicateLeadApprovalController::class, 'show'])->name('duplicate-lead-approvals.show');
        Route::get('/duplicate-lead-approvals/{id}/approve', [App\Http\Controllers\DuplicateLeadApprovalController::class, 'approve'])->name('duplicate-lead-approvals.approve');
        Route::post('/duplicate-lead-approvals/{id}/reject', [App\Http\Controllers\DuplicateLeadApprovalController::class, 'reject'])->name('duplicate-lead-approvals.reject');
        
        // Lead Reassignment Requests Routes
        Route::get('/lead-reassignment-requests', [App\Http\Controllers\LeadReassignmentRequestController::class, 'index'])->name('lead-reassignment-requests.index');
        Route::get('/lead-reassignment-requests/{id}', [App\Http\Controllers\LeadReassignmentRequestController::class, 'show'])->name('lead-reassignment-requests.show');
        Route::get('/lead-reassignment-requests/{id}/approve', [App\Http\Controllers\LeadReassignmentRequestController::class, 'approve'])->name('lead-reassignment-requests.approve');
        Route::get('/lead-reassignment-requests/{id}/reject', [App\Http\Controllers\LeadReassignmentRequestController::class, 'showRejectForm'])->name('lead-reassignment-requests.reject');
        Route::post('/lead-reassignment-requests/{id}/reject', [App\Http\Controllers\LeadReassignmentRequestController::class, 'reject'])->name('lead-reassignment-requests.reject.post');
        
        // Task Reassignment Requests Routes
        Route::get('/task-reassignment-requests', [App\Http\Controllers\TaskReassignmentRequestController::class, 'index'])->name('task-reassignment-requests.index');
        Route::get('/task-reassignment-requests/{id}', [App\Http\Controllers\TaskReassignmentRequestController::class, 'show'])->name('task-reassignment-requests.show');
        Route::get('/task-reassignment-requests/{id}/approve', [App\Http\Controllers\TaskReassignmentRequestController::class, 'approve'])->name('task-reassignment-requests.approve');
        Route::get('/task-reassignment-requests/{id}/reject', [App\Http\Controllers\TaskReassignmentRequestController::class, 'showRejectForm'])->name('task-reassignment-requests.reject');
        Route::post('/task-reassignment-requests/{id}/reject', [App\Http\Controllers\TaskReassignmentRequestController::class, 'reject'])->name('task-reassignment-requests.reject.post');
        
        // Task Assignment Approvals Routes
        Route::get('/task-assignment-approvals', [App\Http\Controllers\TaskAssignmentApprovalController::class, 'index'])->name('task-assignment-approvals.index');
        Route::get('/task-assignment-approvals/{id}', [App\Http\Controllers\TaskAssignmentApprovalController::class, 'show'])->name('task-assignment-approvals.show');
        Route::get('/task-assignment-approvals/{id}/approve', [App\Http\Controllers\TaskAssignmentApprovalController::class, 'approve'])->name('task-assignment-approvals.approve');
        Route::get('/task-assignment-approvals/{id}/reject', [App\Http\Controllers\TaskAssignmentApprovalController::class, 'showRejectForm'])->name('task-assignment-approvals.reject');
        Route::post('/task-assignment-approvals/{id}/reject', [App\Http\Controllers\TaskAssignmentApprovalController::class, 'reject'])->name('task-assignment-approvals.reject.post');
        
        // Unavailable Users Routes
        Route::get('/unavailable-users', [App\Http\Controllers\UserAvailabilityController::class, 'index'])->name('unavailable-users.index');
    });

    Route::prefix('hr')->name('hr.')->group(function () {
        Route::get('/employee-management', [App\Http\Controllers\HRController::class, 'employeeManagement'])->name('employee-management');
        Route::get('/employee-management/export', [App\Http\Controllers\HRController::class, 'exportEmployees'])->name('employee-management.export');
        Route::get('/{role}/leave-management', [App\Http\Controllers\HRController::class, 'leaveManagement'])->name('leave-management');
        Route::get('/{role}/leave-management/export', [App\Http\Controllers\HRController::class, 'exportLeaveManagement'])->name('leave-management.export');
        Route::post('/leave-request/store', [App\Http\Controllers\HRController::class, 'storeLeaveRequest'])->name('leave-request.store');
        Route::post('/leave-request/{id}/approve', [App\Http\Controllers\HRController::class, 'approveLeaveRequest'])->name('leave-request.approve');
        Route::get('/leave-request/{id}/approve', [App\Http\Controllers\HRController::class, 'approveLeaveRequest'])->name('leave-request.approve.get');
        Route::post('/leave-request/{id}/reject', [App\Http\Controllers\HRController::class, 'rejectLeaveRequest'])->name('leave-request.reject');
        Route::get('/leave-request/{id}/reject', [App\Http\Controllers\HRController::class, 'showRejectForm'])->name('leave-request.reject.get');
        Route::get('/leave-request/{id}/edit', [App\Http\Controllers\HRController::class, 'editLeaveRequest'])->name('leave-request.edit');
        Route::put('/leave-request/{id}', [App\Http\Controllers\HRController::class, 'updateLeaveRequest'])->name('leave-request.update');
        Route::delete('/leave-request/{id}', [App\Http\Controllers\HRController::class, 'deleteLeaveRequest'])->name('leave-request.delete');
        Route::get('/test-email', [App\Http\Controllers\HRController::class, 'testEmail'])->name('test-email');
        Route::get('/attendance', [App\Http\Controllers\HRController::class, 'attendance'])->name('attendance');
        Route::get('/attendance/export', [App\Http\Controllers\HRController::class, 'exportAttendance'])->name('attendance.export');
        Route::get('/attendance/{attendance}', [App\Http\Controllers\HRController::class, 'showAttendance'])->name('attendance.show');
        Route::get('/attendance/{attendance}/edit', [App\Http\Controllers\HRController::class, 'editAttendance'])->name('attendance.edit');
        Route::put('/attendance/{attendance}', [App\Http\Controllers\HRController::class, 'updateAttendance'])->name('attendance.update');
        Route::delete('/attendance/{attendance}', [App\Http\Controllers\HRController::class, 'deleteAttendance'])->name('attendance.delete');
        Route::get('/salary-payroll', [App\Http\Controllers\HRController::class, 'salaryPayroll'])->name('salary-payroll');
        Route::get('/salary-payroll/export', [App\Http\Controllers\HRController::class, 'exportSalaryPayroll'])->name('salary-payroll.export');
        Route::get('/performance-management', [App\Http\Controllers\HRController::class, 'performanceManagement'])->name('performance-management');
        Route::get('/performance-management/export', [App\Http\Controllers\HRController::class, 'exportPerformanceManagement'])->name('performance-management.export');
        Route::get('/appraisal-meetings', [App\Http\Controllers\HRController::class, 'appraisalMeetings'])->name('appraisal-meetings');
        Route::get('/appraisal-meetings/export', [App\Http\Controllers\HRController::class, 'exportAppraisalMeetings'])->name('appraisal-meetings.export');
        Route::get('/recruitment', [App\Http\Controllers\HRController::class, 'recruitment'])->name('recruitment');
        Route::get('/recruitment/export', [App\Http\Controllers\HRController::class, 'exportRecruitment'])->name('recruitment.export');
        Route::get('/employee-self-service', [App\Http\Controllers\HRController::class, 'employeeSelfService'])->name('employee-self-service');
        Route::get('/expense-reimbursement', [App\Http\Controllers\HRController::class, 'expenseReimbursement'])->name('expense-reimbursement');
        Route::get('/expense-reimbursement/export', [App\Http\Controllers\HRController::class, 'exportExpenseReimbursement'])->name('expense-reimbursement.export');
        Route::get('/auto-salary-slip', [App\Http\Controllers\HRController::class, 'autoSalarySlip'])->name('auto-salary-slip');
        Route::get('/auto-salary-slip/export', [App\Http\Controllers\HRController::class, 'exportAutoSalarySlip'])->name('auto-salary-slip.export');
    });

    // Company Policy Routes
    Route::prefix('company-policies')->name('company-policies.')->group(function () {
        Route::get('/', [App\Http\Controllers\CompanyPolicyController::class, 'index'])->name('index');
        Route::get('/dashboard', [App\Http\Controllers\CompanyPolicyController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [App\Http\Controllers\CompanyPolicyController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\CompanyPolicyController::class, 'store'])->name('store');
        Route::get('/{companyPolicy}', [App\Http\Controllers\CompanyPolicyController::class, 'show'])->name('show');
        Route::get('/{companyPolicy}/edit', [App\Http\Controllers\CompanyPolicyController::class, 'edit'])->name('edit');
        Route::put('/{companyPolicy}', [App\Http\Controllers\CompanyPolicyController::class, 'update'])->name('update');
        Route::delete('/{companyPolicy}', [App\Http\Controllers\CompanyPolicyController::class, 'destroy'])->name('destroy');
        Route::post('/{companyPolicy}/approve', [App\Http\Controllers\CompanyPolicyController::class, 'approve'])->name('approve');
        Route::post('/{companyPolicy}/archive', [App\Http\Controllers\CompanyPolicyController::class, 'archive'])->name('archive');
        Route::post('/{companyPolicy}/activate', [App\Http\Controllers\CompanyPolicyController::class, 'activate'])->name('activate');
    });

    // Financial Approval Routes
    Route::prefix('approvals')->name('approvals.')->group(function () {
        Route::get('/hr', [App\Http\Controllers\FinancialApprovalController::class, 'hrIndex'])->name('hr.index');
        Route::get('/admin', [App\Http\Controllers\FinancialApprovalController::class, 'adminIndex'])->name('admin.index');
    });

    // Project Management routes for PROJECT MANAGER
    Route::prefix('project-manager')->name('project-manager.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [ProjectSubModuleController::class, 'dashboard'])->name('dashboard');
        
        // Projects
        Route::get('/projects', [ProjectSubModuleController::class, 'projects'])->name('projects.index');
        Route::get('/projects/create', [ProjectSubModuleController::class, 'createProject'])->name('projects.create');
        Route::post('/projects', [ProjectSubModuleController::class, 'storeProject'])->name('projects.store');
        Route::get('/projects/{project}', [ProjectSubModuleController::class, 'showProject'])->name('projects.show');
        Route::get('/projects/{project}/edit', [ProjectSubModuleController::class, 'editProject'])->name('projects.edit');
        Route::put('/projects/{project}', [ProjectSubModuleController::class, 'updateProject'])->name('projects.update');
        Route::delete('/projects/{project}', [ProjectSubModuleController::class, 'deleteProject'])->name('projects.delete');
        
        // Tasks
        Route::get('/tasks', [ProjectSubModuleController::class, 'tasks'])->name('tasks.index');
        Route::get('/tasks/create', [ProjectSubModuleController::class, 'createTask'])->name('tasks.create');
        Route::post('/tasks', [ProjectSubModuleController::class, 'storeTask'])->name('tasks.store');
        Route::get('/tasks/{task}/request-reassignment', [App\Http\Controllers\TaskReassignmentRequestController::class, 'create'])->name('tasks.request-reassignment');
        Route::post('/tasks/{task}/request-reassignment', [App\Http\Controllers\TaskReassignmentRequestController::class, 'store'])->name('tasks.request-reassignment.store');
        Route::get('/tasks/{task}', [ProjectSubModuleController::class, 'showTask'])->name('tasks.show');
        Route::get('/tasks/{task}/edit', [ProjectSubModuleController::class, 'editTask'])->name('tasks.edit');
        Route::put('/tasks/{task}', [ProjectSubModuleController::class, 'updateTask'])->name('tasks.update');
        Route::delete('/tasks/{task}', [ProjectSubModuleController::class, 'deleteTask'])->name('tasks.delete');
        
        // Material Requests
        Route::get('/material-requests', [ProjectSubModuleController::class, 'materialRequests'])->name('material-requests.index');
        Route::get('/material-requests/create', [ProjectSubModuleController::class, 'createMaterialRequest'])->name('material-requests.create');
        Route::post('/material-requests', [ProjectSubModuleController::class, 'storeMaterialRequest'])->name('material-requests.store');
        Route::get('/material-requests/{materialRequest}', [ProjectSubModuleController::class, 'showMaterialRequest'])->name('material-requests.show');
        Route::get('/material-requests/{materialRequest}/edit', [ProjectSubModuleController::class, 'editMaterialRequest'])->name('material-requests.edit');
        Route::put('/material-requests/{materialRequest}', [ProjectSubModuleController::class, 'updateMaterialRequest'])->name('material-requests.update');
        Route::post('/material-requests/{materialRequest}/approve', [ProjectSubModuleController::class, 'approveMaterialRequest'])->name('material-requests.approve');
        Route::post('/material-requests/{materialRequest}/reject', [ProjectSubModuleController::class, 'rejectMaterialRequest'])->name('material-requests.reject');
        Route::delete('/material-requests/{materialRequest}', [ProjectSubModuleController::class, 'deleteMaterialRequest'])->name('material-requests.delete');
        
        // Progress Reports
        Route::get('/progress-reports', [ProjectSubModuleController::class, 'progressReports'])->name('progress-reports.index');
        Route::get('/progress-reports/create', [ProjectSubModuleController::class, 'createProgressReport'])->name('progress-reports.create');
        Route::post('/progress-reports', [ProjectSubModuleController::class, 'storeProgressReport'])->name('progress-reports.store');
        Route::get('/progress-reports/{progressReport}', [ProjectSubModuleController::class, 'showProgressReport'])->name('progress-reports.show');
        Route::get('/progress-reports/{progressReport}/edit', [ProjectSubModuleController::class, 'editProgressReport'])->name('progress-reports.edit');
        Route::put('/progress-reports/{progressReport}', [ProjectSubModuleController::class, 'updateProgressReport'])->name('progress-reports.update');
        Route::delete('/progress-reports/{progressReport}', [ProjectSubModuleController::class, 'deleteProgressReport'])->name('progress-reports.delete');
        
        // Resource Allocations
        Route::get('/resource-allocations', [ProjectSubModuleController::class, 'resourceAllocations'])->name('resource-allocations.index');
        Route::get('/resource-allocations/create', [ProjectSubModuleController::class, 'createResourceAllocation'])->name('resource-allocations.create');
        Route::post('/resource-allocations', [ProjectSubModuleController::class, 'storeResourceAllocation'])->name('resource-allocations.store');
        Route::get('/resource-allocations/{resourceAllocation}', [ProjectSubModuleController::class, 'showResourceAllocation'])->name('resource-allocations.show');
        Route::get('/resource-allocations/{resourceAllocation}/edit', [ProjectSubModuleController::class, 'editResourceAllocation'])->name('resource-allocations.edit');
        Route::put('/resource-allocations/{resourceAllocation}', [ProjectSubModuleController::class, 'updateResourceAllocation'])->name('resource-allocations.update');
        Route::delete('/resource-allocations/{resourceAllocation}', [ProjectSubModuleController::class, 'deleteResourceAllocation'])->name('resource-allocations.delete');
        
        // Payment Milestones
        Route::get('/payment-milestones', [ProjectSubModuleController::class, 'paymentMilestones'])->name('payment-milestones.index');
        Route::get('/payment-milestones/create', [ProjectSubModuleController::class, 'createPaymentMilestone'])->name('payment-milestones.create');
        Route::post('/payment-milestones', [ProjectSubModuleController::class, 'storePaymentMilestone'])->name('payment-milestones.store');
        Route::get('/payment-milestones/{paymentMilestone}', [ProjectSubModuleController::class, 'showPaymentMilestone'])->name('payment-milestones.show');
        Route::get('/payment-milestones/{paymentMilestone}/edit', [ProjectSubModuleController::class, 'editPaymentMilestone'])->name('payment-milestones.edit');
        Route::put('/payment-milestones/{paymentMilestone}', [ProjectSubModuleController::class, 'updatePaymentMilestone'])->name('payment-milestones.update');
        Route::delete('/payment-milestones/{paymentMilestone}', [ProjectSubModuleController::class, 'deletePaymentMilestone'])->name('payment-milestones.delete');
        
        // Budgets
        Route::get('/budgets', [ProjectSubModuleController::class, 'budgets'])->name('budgets.index');
        Route::get('/budgets/create', [ProjectSubModuleController::class, 'createBudget'])->name('budgets.create');
        Route::post('/budgets', [ProjectSubModuleController::class, 'storeBudget'])->name('budgets.store');
        Route::get('/budgets/{budget}', [ProjectSubModuleController::class, 'showBudget'])->name('budgets.show');
        Route::get('/budgets/{budget}/edit', [ProjectSubModuleController::class, 'editBudget'])->name('budgets.edit');
        Route::put('/budgets/{budget}', [ProjectSubModuleController::class, 'updateBudget'])->name('budgets.update');
        Route::delete('/budgets/{budget}', [ProjectSubModuleController::class, 'deleteBudget'])->name('budgets.delete');
        
        // Quality Checks
        Route::get('/quality-checks', [ProjectSubModuleController::class, 'qualityChecks'])->name('quality-checks.index');
        Route::get('/quality-checks/create', [ProjectSubModuleController::class, 'createQualityCheck'])->name('quality-checks.create');
        Route::post('/quality-checks', [ProjectSubModuleController::class, 'storeQualityCheck'])->name('quality-checks.store');
        Route::get('/quality-checks/{qualityCheck}', [ProjectSubModuleController::class, 'showQualityCheck'])->name('quality-checks.show');
        Route::get('/quality-checks/{qualityCheck}/edit', [ProjectSubModuleController::class, 'editQualityCheck'])->name('quality-checks.edit');
        Route::put('/quality-checks/{qualityCheck}', [ProjectSubModuleController::class, 'updateQualityCheck'])->name('quality-checks.update');
        Route::delete('/quality-checks/{qualityCheck}', [ProjectSubModuleController::class, 'deleteQualityCheck'])->name('quality-checks.delete');
    });

// Material Consumption routes
Route::prefix('material-consumptions')->name('material-consumptions.')->group(function () {
    Route::get('/', [App\Http\Controllers\MaterialConsumptionController::class, 'index'])->name('index');
    Route::get('/dashboard', [App\Http\Controllers\MaterialConsumptionController::class, 'dashboard'])->name('dashboard');
    Route::get('/create', [App\Http\Controllers\MaterialConsumptionController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\MaterialConsumptionController::class, 'store'])->name('store');
    Route::get('/{materialConsumption}', [App\Http\Controllers\MaterialConsumptionController::class, 'show'])->name('show');
    Route::get('/{materialConsumption}/edit', [App\Http\Controllers\MaterialConsumptionController::class, 'edit'])->name('edit');
    Route::put('/{materialConsumption}', [App\Http\Controllers\MaterialConsumptionController::class, 'update'])->name('update');
    Route::delete('/{materialConsumption}', [App\Http\Controllers\MaterialConsumptionController::class, 'destroy'])->name('destroy');
    Route::post('/{materialConsumption}/approve', [App\Http\Controllers\MaterialConsumptionController::class, 'approve'])->name('approve');
    Route::post('/{materialConsumption}/mark-completed', [App\Http\Controllers\MaterialConsumptionController::class, 'markCompleted'])->name('mark-completed');
    Route::post('/{materialConsumption}/record-waste', [App\Http\Controllers\MaterialConsumptionController::class, 'recordWaste'])->name('record-waste');
    Route::post('/{materialConsumption}/return-to-stock', [App\Http\Controllers\MaterialConsumptionController::class, 'returnToStock'])->name('return-to-stock');
    Route::post('/quick-consume', [App\Http\Controllers\MaterialConsumptionController::class, 'quickConsume'])->name('quick-consume');
});

    // Activity Planning & Tracking routes
    Route::prefix('activities')->name('activities.')->group(function () {
        Route::get('/', [ActivityController::class, 'index'])->name('index');
        Route::get('/create', [ActivityController::class, 'create'])->name('create');
        Route::post('/', [ActivityController::class, 'store'])->name('store');
        
        // Export route (must be before parameterized routes)
        Route::get('/export', [ActivityController::class, 'export'])->name('export');
        
        Route::get('/{activity}', [ActivityController::class, 'show'])->name('show');
        Route::get('/{activity}/edit', [ActivityController::class, 'edit'])->name('edit');
        Route::put('/{activity}', [ActivityController::class, 'update'])->name('update');
        Route::delete('/{activity}', [ActivityController::class, 'destroy'])->name('destroy');
        
        // Status management routes
        Route::post('/{activity}/mark-in-progress', [ActivityController::class, 'markInProgress'])->name('mark-in-progress');
        Route::post('/{activity}/mark-completed', [ActivityController::class, 'markCompleted'])->name('mark-completed');
        Route::post('/{activity}/mark-on-hold', [ActivityController::class, 'markOnHold'])->name('mark-on-hold');
        Route::post('/{activity}/mark-cancelled', [ActivityController::class, 'markCancelled'])->name('mark-cancelled');
        Route::post('/{activity}/update-progress', [ActivityController::class, 'updateProgress'])->name('update-progress');
        Route::post('/{activity}/add-hours', [ActivityController::class, 'addHours'])->name('add-hours');
        Route::post('/{activity}/add-cost', [ActivityController::class, 'addCost'])->name('add-cost');
        Route::post('/{activity}/approve', [ActivityController::class, 'approve'])->name('approve');
    });

    // Resource Allocation routes
    Route::prefix('resource-allocations')->name('resource-allocations.')->group(function () {
        Route::get('/', [ResourceAllocationController::class, 'index'])->name('index');
        Route::get('/create', [ResourceAllocationController::class, 'create'])->name('create');
        Route::post('/', [ResourceAllocationController::class, 'store'])->name('store');
        Route::get('/{resourceAllocation}', [ResourceAllocationController::class, 'show'])->name('show');
        Route::get('/{resourceAllocation}/edit', [ResourceAllocationController::class, 'edit'])->name('edit');
        Route::put('/{resourceAllocation}', [ResourceAllocationController::class, 'update'])->name('update');
        Route::delete('/{resourceAllocation}', [ResourceAllocationController::class, 'destroy'])->name('destroy');
        
        // Status management routes
        Route::post('/{resourceAllocation}/mark-allocated', [ResourceAllocationController::class, 'markAllocated'])->name('mark-allocated');
        Route::post('/{resourceAllocation}/mark-in-use', [ResourceAllocationController::class, 'markInUse'])->name('mark-in-use');
        Route::post('/{resourceAllocation}/mark-completed', [ResourceAllocationController::class, 'markCompleted'])->name('mark-completed');
        Route::post('/{resourceAllocation}/mark-cancelled', [ResourceAllocationController::class, 'markCancelled'])->name('mark-cancelled');
        Route::post('/{resourceAllocation}/update-utilization', [ResourceAllocationController::class, 'updateUtilization'])->name('update-utilization');
        Route::post('/{resourceAllocation}/add-quantity', [ResourceAllocationController::class, 'addQuantity'])->name('add-quantity');
        Route::post('/{resourceAllocation}/add-cost', [ResourceAllocationController::class, 'addCost'])->name('add-cost');
        Route::post('/{resourceAllocation}/approve', [ResourceAllocationController::class, 'approve'])->name('approve');
        
        // Export route (must be before parameterized routes)
        Route::get('/export', [ResourceAllocationController::class, 'export'])->name('export');
    });

    // Expense Management routes
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/', [App\Http\Controllers\ExpenseController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\ExpenseController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\ExpenseController::class, 'store'])->name('store');
        Route::get('/{expense}', [App\Http\Controllers\ExpenseController::class, 'show'])->name('show');
        Route::get('/{expense}/edit', [App\Http\Controllers\ExpenseController::class, 'edit'])->name('edit');
        Route::put('/{expense}', [App\Http\Controllers\ExpenseController::class, 'update'])->name('update');
        Route::delete('/{expense}', [App\Http\Controllers\ExpenseController::class, 'destroy'])->name('destroy');
        
        // Status management routes
        Route::post('/{expense}/approve', [App\Http\Controllers\ExpenseController::class, 'approve'])->name('approve');
        Route::post('/{expense}/reject', [App\Http\Controllers\ExpenseController::class, 'reject'])->name('reject');
        Route::post('/{expense}/mark-paid', [App\Http\Controllers\ExpenseController::class, 'markPaid'])->name('mark-paid');
    });

    // Site Expenses routes
    Route::prefix('site-expenses')->name('site-expenses.')->group(function () {
        Route::get('/', [App\Http\Controllers\SiteExpenseController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\SiteExpenseController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\SiteExpenseController::class, 'store'])->name('store');
        Route::get('/{siteExpense}', [App\Http\Controllers\SiteExpenseController::class, 'show'])->name('show');
        Route::get('/{siteExpense}/edit', [App\Http\Controllers\SiteExpenseController::class, 'edit'])->name('edit');
        Route::put('/{siteExpense}', [App\Http\Controllers\SiteExpenseController::class, 'update'])->name('update');
        Route::delete('/{siteExpense}', [App\Http\Controllers\SiteExpenseController::class, 'destroy'])->name('destroy');
        Route::post('/{siteExpense}/approve', [App\Http\Controllers\SiteExpenseController::class, 'approve'])->name('approve');
        Route::post('/{siteExpense}/reject', [App\Http\Controllers\SiteExpenseController::class, 'reject'])->name('reject');
    });

    // Advances routes
    Route::prefix('advances')->name('advances.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdvanceController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\AdvanceController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\AdvanceController::class, 'store'])->name('store');
        Route::get('/{advance}', [App\Http\Controllers\AdvanceController::class, 'show'])->name('show');
        Route::get('/{advance}/edit', [App\Http\Controllers\AdvanceController::class, 'edit'])->name('edit');
        Route::put('/{advance}', [App\Http\Controllers\AdvanceController::class, 'update'])->name('update');
        Route::delete('/{advance}', [App\Http\Controllers\AdvanceController::class, 'destroy'])->name('destroy');
        Route::post('/{advance}/approve', [App\Http\Controllers\AdvanceController::class, 'approve'])->name('approve');
        Route::post('/{advance}/reject', [App\Http\Controllers\AdvanceController::class, 'reject'])->name('reject');
    });

    // Expense Category routes
    Route::prefix('expense-categories')->name('expense-categories.')->group(function () {
        Route::get('/', [App\Http\Controllers\ExpenseCategoryController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\ExpenseCategoryController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\ExpenseCategoryController::class, 'store'])->name('store');
        Route::get('/{expenseCategory}', [App\Http\Controllers\ExpenseCategoryController::class, 'show'])->name('show');
        Route::get('/{expenseCategory}/edit', [App\Http\Controllers\ExpenseCategoryController::class, 'edit'])->name('edit');
        Route::put('/{expenseCategory}', [App\Http\Controllers\ExpenseCategoryController::class, 'update'])->name('update');
        Route::delete('/{expenseCategory}', [App\Http\Controllers\ExpenseCategoryController::class, 'destroy'])->name('destroy');
    });

    // Project Profitability routes
    Route::prefix('project-profitabilities')->name('project-profitabilities.')->group(function () {
        Route::get('/', [App\Http\Controllers\ProjectProfitabilityController::class, 'index'])->name('index');
        Route::get('/dashboard', [App\Http\Controllers\ProjectProfitabilityController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [App\Http\Controllers\ProjectProfitabilityController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\ProjectProfitabilityController::class, 'store'])->name('store');
        Route::get('/{projectProfitability}', [App\Http\Controllers\ProjectProfitabilityController::class, 'show'])->name('show');
        Route::get('/{projectProfitability}/edit', [App\Http\Controllers\ProjectProfitabilityController::class, 'edit'])->name('edit');
        Route::put('/{projectProfitability}', [App\Http\Controllers\ProjectProfitabilityController::class, 'update'])->name('update');
        Route::delete('/{projectProfitability}', [App\Http\Controllers\ProjectProfitabilityController::class, 'destroy'])->name('destroy');
        Route::post('/{projectProfitability}/approve', [App\Http\Controllers\ProjectProfitabilityController::class, 'approve'])->name('approve');
    });

    // Budgeting routes
    Route::prefix('budgets')->name('budgets.')->group(function () {
        Route::get('/', [App\Http\Controllers\BudgetController::class, 'index'])->name('index');
        Route::get('/dashboard', [App\Http\Controllers\BudgetController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [App\Http\Controllers\BudgetController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\BudgetController::class, 'store'])->name('store');
        Route::get('/{budget}', [App\Http\Controllers\BudgetController::class, 'show'])->name('show');
        Route::get('/{budget}/edit', [App\Http\Controllers\BudgetController::class, 'edit'])->name('edit');
        Route::put('/{budget}', [App\Http\Controllers\BudgetController::class, 'update'])->name('update');
        Route::delete('/{budget}', [App\Http\Controllers\BudgetController::class, 'destroy'])->name('destroy');
        Route::post('/{budget}/approve', [App\Http\Controllers\BudgetController::class, 'approve'])->name('approve');
        Route::post('/{budget}/reject', [App\Http\Controllers\BudgetController::class, 'reject'])->name('reject');
        Route::post('/{budget}/set-actual', [App\Http\Controllers\BudgetController::class, 'setActualAmount'])->name('set-actual');
    });

    // Budget Categories routes
    Route::prefix('budget-categories')->name('budget-categories.')->group(function () {
        Route::get('/', [App\Http\Controllers\BudgetCategoryController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\BudgetCategoryController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\BudgetCategoryController::class, 'store'])->name('store');
        Route::get('/{budgetCategory}', [App\Http\Controllers\BudgetCategoryController::class, 'show'])->name('show');
        Route::get('/{budgetCategory}/edit', [App\Http\Controllers\BudgetCategoryController::class, 'edit'])->name('edit');
        Route::put('/{budgetCategory}', [App\Http\Controllers\BudgetCategoryController::class, 'update'])->name('update');
        Route::delete('/{budgetCategory}', [App\Http\Controllers\BudgetCategoryController::class, 'destroy'])->name('destroy');
    });

    // Payment Milestones routes
    Route::prefix('payment-milestones')->name('payment-milestones.')->group(function () {
        Route::get('/', [App\Http\Controllers\PaymentMilestoneController::class, 'index'])->name('index');
        Route::get('/dashboard', [App\Http\Controllers\PaymentMilestoneController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [App\Http\Controllers\PaymentMilestoneController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\PaymentMilestoneController::class, 'store'])->name('store');
        Route::get('/{paymentMilestone}', [App\Http\Controllers\PaymentMilestoneController::class, 'show'])->name('show');
        Route::get('/{paymentMilestone}/edit', [App\Http\Controllers\PaymentMilestoneController::class, 'edit'])->name('edit');
        Route::put('/{paymentMilestone}', [App\Http\Controllers\PaymentMilestoneController::class, 'update'])->name('update');
        Route::delete('/{paymentMilestone}', [App\Http\Controllers\PaymentMilestoneController::class, 'destroy'])->name('destroy');
        Route::post('/{paymentMilestone}/mark-paid', [App\Http\Controllers\PaymentMilestoneController::class, 'markPaid'])->name('mark-paid');
        Route::post('/{paymentMilestone}/mark-completed', [App\Http\Controllers\PaymentMilestoneController::class, 'markCompleted'])->name('mark-completed');
        Route::post('/{paymentMilestone}/mark-in-progress', [App\Http\Controllers\PaymentMilestoneController::class, 'markInProgress'])->name('mark-in-progress');
    });

    // Contractor routes
    Route::prefix('contractors')->name('contractors.')->group(function () {
        Route::get('/', [App\Http\Controllers\ContractorController::class, 'index'])->name('index');
        Route::get('/dashboard', [App\Http\Controllers\ContractorController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [App\Http\Controllers\ContractorController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\ContractorController::class, 'store'])->name('store');
        Route::get('/{contractor}', [App\Http\Controllers\ContractorController::class, 'show'])->name('show');
        Route::get('/{contractor}/edit', [App\Http\Controllers\ContractorController::class, 'edit'])->name('edit');
        Route::put('/{contractor}', [App\Http\Controllers\ContractorController::class, 'update'])->name('update');
        Route::delete('/{contractor}', [App\Http\Controllers\ContractorController::class, 'destroy'])->name('destroy');

        Route::post('/{contractor}/verify', [App\Http\Controllers\ContractorController::class, 'verify'])->name('verify');
        Route::post('/{contractor}/update-availability', [App\Http\Controllers\ContractorController::class, 'updateAvailability'])->name('update-availability');
        Route::post('/{contractor}/update-rating', [App\Http\Controllers\ContractorController::class, 'updateRating'])->name('update-rating');
    });

    // Purchase Manager Routes
    Route::prefix('purchase-manager')->name('purchase-manager.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [PurchaseSubModuleController::class, 'dashboard'])->name('dashboard');

        // Purchase Orders
        Route::get('/purchase-orders', [PurchaseSubModuleController::class, 'purchaseOrders'])->name('purchase-orders.index');
        Route::get('/purchase-orders/create', [PurchaseSubModuleController::class, 'createPurchaseOrder'])->name('purchase-orders.create');
        Route::post('/purchase-orders', [PurchaseSubModuleController::class, 'storePurchaseOrder'])->name('purchase-orders.store');
        Route::get('/purchase-orders/{purchaseOrder}', [PurchaseSubModuleController::class, 'showPurchaseOrder'])->name('purchase-orders.show');
        Route::get('/purchase-orders/{purchaseOrder}/edit', [PurchaseSubModuleController::class, 'editPurchaseOrder'])->name('purchase-orders.edit');
        Route::put('/purchase-orders/{purchaseOrder}', [PurchaseSubModuleController::class, 'updatePurchaseOrder'])->name('purchase-orders.update');
        Route::delete('/purchase-orders/{purchaseOrder}', [PurchaseSubModuleController::class, 'deletePurchaseOrder'])->name('purchase-orders.delete');

        // Vendors
        Route::get('/vendors', [PurchaseSubModuleController::class, 'vendors'])->name('vendors.index');
        Route::get('/vendors/create', [PurchaseSubModuleController::class, 'createVendor'])->name('vendors.create');
        Route::post('/vendors', [PurchaseSubModuleController::class, 'storeVendor'])->name('vendors.store');
        Route::get('/vendors/{vendor}', [PurchaseSubModuleController::class, 'showVendor'])->name('vendors.show');
        Route::get('/vendors/{vendor}/edit', [PurchaseSubModuleController::class, 'editVendor'])->name('vendors.edit');
        Route::put('/vendors/{vendor}', [PurchaseSubModuleController::class, 'updateVendor'])->name('vendors.update');
        Route::delete('/vendors/{vendor}', [PurchaseSubModuleController::class, 'deleteVendor'])->name('vendors.delete');

        // Products
        Route::get('/products', [PurchaseSubModuleController::class, 'products'])->name('products.index');
        Route::get('/products/create', [PurchaseSubModuleController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [PurchaseSubModuleController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}', [PurchaseSubModuleController::class, 'showProduct'])->name('products.show');
        Route::get('/products/{product}/edit', [PurchaseSubModuleController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{product}', [PurchaseSubModuleController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [PurchaseSubModuleController::class, 'deleteProduct'])->name('products.delete');

        // RFQs
        Route::get('/rfqs', [PurchaseSubModuleController::class, 'rfqs'])->name('rfqs.index');
        Route::get('/rfqs/create', [PurchaseSubModuleController::class, 'createRFQ'])->name('rfqs.create');
        Route::post('/rfqs', [PurchaseSubModuleController::class, 'storeRFQ'])->name('rfqs.store');
        Route::get('/rfqs/{rfq}', [PurchaseSubModuleController::class, 'showRFQ'])->name('rfqs.show');
        Route::get('/rfqs/{rfq}/edit', [PurchaseSubModuleController::class, 'editRFQ'])->name('rfqs.edit');
        Route::put('/rfqs/{rfq}', [PurchaseSubModuleController::class, 'updateRFQ'])->name('rfqs.update');
        Route::delete('/rfqs/{rfq}', [PurchaseSubModuleController::class, 'deleteRFQ'])->name('rfqs.delete');

        // Purchase Requisitions
        Route::get('/purchase-requisitions', [PurchaseSubModuleController::class, 'purchaseRequisitions'])->name('purchase-requisitions.index');
        Route::get('/purchase-requisitions/create', [PurchaseSubModuleController::class, 'createPurchaseRequisition'])->name('purchase-requisitions.create');
        Route::post('/purchase-requisitions', [PurchaseSubModuleController::class, 'storePurchaseRequisition'])->name('purchase-requisitions.store');
        Route::get('/purchase-requisitions/{requisition}', [PurchaseSubModuleController::class, 'showPurchaseRequisition'])->name('purchase-requisitions.show');
        Route::get('/purchase-requisitions/{requisition}/edit', [PurchaseSubModuleController::class, 'editPurchaseRequisition'])->name('purchase-requisitions.edit');
        Route::put('/purchase-requisitions/{requisition}', [PurchaseSubModuleController::class, 'updatePurchaseRequisition'])->name('purchase-requisitions.update');
        Route::delete('/purchase-requisitions/{requisition}', [PurchaseSubModuleController::class, 'deletePurchaseRequisition'])->name('purchase-requisitions.delete');

        // Vendor Registrations
        Route::get('/vendor-registrations', [PurchaseSubModuleController::class, 'vendorRegistrations'])->name('vendor-registrations.index');
        Route::get('/vendor-registrations/create', [PurchaseSubModuleController::class, 'createVendorRegistration'])->name('vendor-registrations.create');
        Route::post('/vendor-registrations', [PurchaseSubModuleController::class, 'storeVendorRegistration'])->name('vendor-registrations.store');
        Route::get('/vendor-registrations/{registration}', [PurchaseSubModuleController::class, 'showVendorRegistration'])->name('vendor-registrations.show');
        Route::get('/vendor-registrations/{registration}/edit', [PurchaseSubModuleController::class, 'editVendorRegistration'])->name('vendor-registrations.edit');
        Route::put('/vendor-registrations/{registration}', [PurchaseSubModuleController::class, 'updateVendorRegistration'])->name('vendor-registrations.update');
        Route::delete('/vendor-registrations/{registration}', [PurchaseSubModuleController::class, 'deleteVendorRegistration'])->name('vendor-registrations.delete');
    });

    // Todo Routes
    Route::get('/todos', [App\Http\Controllers\TodoController::class, 'index'])->name('todos.index');
    Route::post('/todos', [App\Http\Controllers\TodoController::class, 'store'])->name('todos.store');
    Route::put('/todos/{todo}/status', [App\Http\Controllers\TodoController::class, 'updateStatus'])->name('todos.update-status');
    Route::get('/todos/check', [App\Http\Controllers\TodoController::class, 'checkIncompleteTodos'])->name('todos.check');
    Route::get('/todos/modal', [App\Http\Controllers\TodoController::class, 'getTodosForModal'])->name('todos.modal');
    Route::delete('/todos/{todo}', [App\Http\Controllers\TodoController::class, 'destroy'])->name('todos.destroy');

    // Admin/Sales Manager Todo Routes
    Route::get('/todos/all', [App\Http\Controllers\TodoController::class, 'allTodos'])->name('todos.all');
    Route::post('/todos/assign', [App\Http\Controllers\TodoController::class, 'assign'])->name('todos.assign');
    Route::put('/todos/{todo}/transfer', [App\Http\Controllers\TodoController::class, 'transfer'])->name('todos.transfer');

});
