<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing roles and permissions (handle foreign key constraints)
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        Permission::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define all roles
        $roles = [
            'SALES MANAGER',
            'TELE SALES',
            'FIELD SALES',
            'PROJECT MANAGER',
            'PROJECT ENGINEER',
            'LIASONING EXECUTIVE',
            'QUALITY ENGINEER',
            'PURCHASE MANAGER/EXECUTIVE',
            'ACCOUNT EXECUTIVE',
            'STORE EXECUTIVE',
            'SERVICE ENGINEER',
            'HR MANAGER',
            'SUPER ADMIN'
        ];

        // Create roles
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Define comprehensive permissions
        $permissions = [
            // Dashboard permissions
            'view_dashboard',
            'view_analytics',
            'view_reports',
            
            // User management permissions
            'manage_users',
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // Lead management permissions
            'manage_leads',
            'view_leads',
            'create_leads',
            'edit_leads',
            'delete_leads',
            'convert_leads',
            'assign_leads',
            
            // Project management permissions
            'manage_projects',
            'view_projects',
            'create_projects',
            'edit_projects',
            'delete_projects',
            'assign_projects',
            'view_project_details',
            'manage_project_tasks',
            'manage_project_materials',
            'manage_project_budget',
            
            // Task management permissions
            'manage_tasks',
            'view_tasks',
            'create_tasks',
            'edit_tasks',
            'delete_tasks',
            'assign_tasks',
            'update_task_status',
            
            // Product management permissions
            'manage_products',
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            
            // Vendor management permissions
            'manage_vendors',
            'view_vendors',
            'create_vendors',
            'edit_vendors',
            'delete_vendors',
            
            // Purchase management permissions
            'manage_purchase_orders',
            'view_purchase_orders',
            'create_purchase_orders',
            'edit_purchase_orders',
            'delete_purchase_orders',
            'approve_purchase_orders',
            'manage_purchase_requisitions',
            'view_purchase_requisitions',
            'create_purchase_requisitions',
            'edit_purchase_requisitions',
            'approve_purchase_requisitions',
            'manage_rfq',
            'view_rfq',
            'create_rfq',
            'edit_rfq',
            
            // Inventory management permissions
            'manage_inventory',
            'view_inventory',
            'manage_stock',
            'view_stock',
            'manage_warehouse',
            'view_warehouse',
            'manage_material_requests',
            'view_material_requests',
            'create_material_requests',
            'edit_material_requests',
            'manage_material_consumption',
            'view_material_consumption',
            'create_material_consumption',
            'edit_material_consumption',
            'manage_grn',
            'view_grn',
            'create_grn',
            'edit_grn',
            'manage_stock_valuation',
            'view_stock_valuation',
            'manage_stock_ledger',
            'view_stock_ledger',
            'manage_inventory_audit',
            'view_inventory_audit',
            'create_inventory_audit',
            'edit_inventory_audit',
            
            // Invoice management permissions
            'manage_invoices',
            'view_invoices',
            'create_invoices',
            'edit_invoices',
            'delete_invoices',
            'approve_invoices',
            'manage_quotations',
            'view_quotations',
            'create_quotations',
            'edit_quotations',
            
            // Quality management permissions
            'manage_quality_checks',
            'view_quality_checks',
            'create_quality_checks',
            'edit_quality_checks',
            'approve_quality_checks',
            
            // HR management permissions
            'manage_employees',
            'view_employees',
            'create_employees',
            'edit_employees',
            'delete_employees',
            'manage_attendance',
            'view_attendance',
            'create_attendance',
            'edit_attendance',
            'manage_leave_requests',
            'view_leave_requests',
            'create_leave_requests',
            'edit_leave_requests',
            'approve_leave_requests',
            'manage_payroll',
            'view_payroll',
            'create_payroll',
            'edit_payroll',
            'manage_performance_reviews',
            'view_performance_reviews',
            'create_performance_reviews',
            'edit_performance_reviews',
            'manage_job_applications',
            'view_job_applications',
            'create_job_applications',
            'edit_job_applications',
            'manage_expense_claims',
            'view_expense_claims',
            'create_expense_claims',
            'edit_expense_claims',
            'approve_expense_claims',
            
            // Financial management permissions
            'manage_accounts',
            'view_accounts',
            'manage_budget',
            'view_budget',
            'create_budget',
            'edit_budget',
            'manage_expenses',
            'view_expenses',
            'create_expenses',
            'edit_expenses',
            'manage_payment_requests',
            'view_payment_requests',
            'create_payment_requests',
            'edit_payment_requests',
            'approve_payment_requests',
            'manage_payment_milestones',
            'view_payment_milestones',
            'create_payment_milestones',
            'edit_payment_milestones',
            'view_financial_reports',
            
            // Document management permissions
            'manage_documents',
            'view_documents',
            'upload_documents',
            'download_documents',
            'delete_documents',
            
            // Settings permissions
            'manage_settings',
            'view_settings',
            'manage_system_settings',
            
            // Service management permissions
            'manage_service_requests',
            'view_service_requests',
            'create_service_requests',
            'edit_service_requests',
            'manage_complaints',
            'view_complaints',
            'create_complaints',
            'edit_complaints',
            'manage_amc',
            'view_amc',
            'create_amc',
            'edit_amc',
            'manage_maintenance',
            'view_maintenance',
            'create_maintenance',
            'edit_maintenance',
            
            // Liaisoning permissions
            'manage_liaisoning',
            'view_liaisoning',
            'create_liaisoning',
            'edit_liaisoning',
            'manage_permits',
            'manage_approvals',
            
            // Customer management permissions
            'manage_customers',
            'view_customers',
            'create_customers',
            'edit_customers',
            
            // Commission permissions
            'manage_commission',
            'view_commission',
            
            // Daily progress permissions
            'manage_daily_progress',
            'view_daily_progress',
            'create_daily_progress',
            'edit_daily_progress',
            
            // Contractor permissions
            'manage_contractors',
            'view_contractors',
            
            // Escalation permissions
            'manage_escalations',
            'view_escalations',
            'create_escalations',
            
            // Material permissions
            'manage_materials',
            'view_materials',
            
            // Salary slip permissions
            'manage_salary_slips',
            'view_salary_slips',
            'create_salary_slips',
            
            // Appraisal permissions
            'manage_appraisals',
            'view_appraisals',
            'create_appraisals',
            'edit_appraisals',
            
            // Activity management permissions
            'manage_activities',
            'view_activities',
            'create_activities',
            'edit_activities',
            'delete_activities',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $this->assignRolePermissions();
    }

    /**
     * Assign permissions to specific roles
     */
    private function assignRolePermissions(): void
    {
        // SUPER ADMIN - All permissions
        $superAdminRole = Role::findByName('SUPER ADMIN');
        $superAdminRole->givePermissionTo(Permission::all());

        // SALES MANAGER permissions - CRM Focus Only
        $salesManagerRole = Role::findByName('SALES MANAGER');
        $salesManagerRole->givePermissionTo([
            'view_dashboard',
            'view_analytics',
            'view_reports',
            'manage_leads',
            'view_leads',
            'create_leads',
            'edit_leads',
            'assign_leads',
            'convert_leads',
            'manage_quotations',
            'view_quotations',
            'create_quotations',
            'edit_quotations',
            'view_customers',
            'manage_customers',
            'create_customers',
            'edit_customers',
            'view_products',
            'view_commission',
            'manage_commission',
            'manage_activities',
            'view_activities',
            'create_activities',
            'edit_activities',
        ]);

        // TELE SALES permissions - Limited CRM Access (Leads, Quotations, Documents only)
        $teleSalesRole = Role::findByName('TELE SALES');
        $teleSalesRole->givePermissionTo([
            'view_dashboard',
            'view_leads',
            'create_leads',
            'edit_leads',
            'view_quotations',
            'create_quotations',
            'edit_quotations',
            'view_documents',
            'upload_documents',
            'download_documents',
            'manage_documents',
            'view_customers',
            'create_customers',
            'edit_customers',
            'view_products',
        ]);

        // FIELD SALES permissions - Limited CRM Access (Leads, Quotations, Documents only)
        $fieldSalesRole = Role::findByName('FIELD SALES');
        $fieldSalesRole->givePermissionTo([
            'view_dashboard',
            'view_leads', 'create_leads', 'edit_leads',
            'view_quotations', 'create_quotations', 'edit_quotations',
            'view_documents', 'upload_documents', 'download_documents', 'manage_documents',
            'view_customers', 'create_customers', 'edit_customers',
            'view_products',
        ]);

        // PROJECT MANAGER permissions - Project Management Only
        $projectManagerRole = Role::findByName('PROJECT MANAGER');
        $projectManagerRole->givePermissionTo([
            'view_dashboard',
            'view_analytics',
            'view_reports',
            'manage_projects',
            'view_projects',
            'create_projects',
            'edit_projects',
            'assign_projects',
            'view_project_details',
            'manage_project_tasks',
            'manage_project_materials',
            'manage_project_budget',
            'manage_tasks',
            'view_tasks',
            'create_tasks',
            'edit_tasks',
            'assign_tasks',
            'update_task_status',
            'view_material_requests',
            'manage_material_requests',
            'view_material_consumption',
            'manage_material_consumption',
            'view_daily_progress',
            'manage_daily_progress',
            'view_quality_checks',
            'manage_quality_checks',
            'view_contractors',
            'manage_contractors',
            'view_escalations',
            'manage_escalations',
        ]);

        // PROJECT ENGINEER permissions - Project Execution Only
        $projectEngineerRole = Role::findByName('PROJECT ENGINEER');
        $projectEngineerRole->givePermissionTo([
            'view_dashboard',
            'view_projects',
            'view_project_details',
            'view_tasks',
            'edit_tasks',
            'update_task_status',
            'view_material_requests',
            'create_material_requests',
            'view_material_consumption',
            'create_material_consumption',
            'view_daily_progress',
            'create_daily_progress',
            'edit_daily_progress',
            'view_quality_checks',
            'create_quality_checks',
            'edit_quality_checks',
            'view_contractors',
            'view_escalations',
            'create_escalations',
        ]);

        // LIASONING EXECUTIVE permissions - Liaisoning Only
        $liaisoningRole = Role::findByName('LIASONING EXECUTIVE');
        $liaisoningRole->givePermissionTo([
            'view_dashboard',
            'manage_liaisoning',
            'view_liaisoning',
            'create_liaisoning',
            'edit_liaisoning',
            'manage_permits',
            'manage_approvals',
            'view_projects',
            'view_project_details',
            'view_documents',
            'upload_documents',
            'download_documents',
            'view_vendors',
            'create_vendors',
            'edit_vendors',
        ]);

        // QUALITY ENGINEER permissions - Quality Management Only
        $qualityEngineerRole = Role::findByName('QUALITY ENGINEER');
        $qualityEngineerRole->givePermissionTo([
            'view_dashboard',
            'manage_quality_checks',
            'view_quality_checks',
            'create_quality_checks',
            'edit_quality_checks',
            'approve_quality_checks',
            'view_projects',
            'view_project_details',
            'view_materials',
            'view_products',
            'view_inventory',
            'view_stock',
            'manage_inventory_audit',
            'view_inventory_audit',
            'create_inventory_audit',
            'edit_inventory_audit',
        ]);

        // PURCHASE MANAGER/EXECUTIVE permissions - Purchase Management Only
        $purchaseManagerRole = Role::findByName('PURCHASE MANAGER/EXECUTIVE');
        $purchaseManagerRole->givePermissionTo([
            'view_dashboard',
            'view_analytics',
            'view_reports',
            'manage_vendors',
            'view_vendors',
            'create_vendors',
            'edit_vendors',
            'delete_vendors',
            'manage_purchase_orders',
            'view_purchase_orders',
            'create_purchase_orders',
            'edit_purchase_orders',
            'approve_purchase_orders',
            'manage_purchase_requisitions',
            'view_purchase_requisitions',
            'create_purchase_requisitions',
            'edit_purchase_requisitions',
            'approve_purchase_requisitions',
            'manage_rfq',
            'view_rfq',
            'create_rfq',
            'edit_rfq',
            'manage_grn',
            'view_grn',
            'create_grn',
            'edit_grn',
            'view_products',
            'view_materials',
            'view_inventory',
            'view_stock',
        ]);

        // ACCOUNT EXECUTIVE permissions - Financial Management Only
        $accountExecutiveRole = Role::findByName('ACCOUNT EXECUTIVE');
        $accountExecutiveRole->givePermissionTo([
            'view_dashboard',
            'view_analytics',
            'view_reports',
            'manage_accounts',
            'view_accounts',
            'manage_invoices',
            'view_invoices',
            'create_invoices',
            'edit_invoices',
            'approve_invoices',
            'manage_quotations',
            'view_quotations',
            'create_quotations',
            'edit_quotations',
            'manage_payment_requests',
            'view_payment_requests',
            'create_payment_requests',
            'edit_payment_requests',
            'approve_payment_requests',
            'manage_payment_milestones',
            'view_payment_milestones',
            'create_payment_milestones',
            'edit_payment_milestones',
            'manage_budget',
            'view_budget',
            'create_budget',
            'edit_budget',
            'manage_expenses',
            'view_expenses',
            'create_expenses',
            'edit_expenses',
            'view_financial_reports',
            'view_customers',
            'view_vendors',
        ]);

        // STORE EXECUTIVE permissions - Inventory Management Only
        $storeExecutiveRole = Role::findByName('STORE EXECUTIVE');
        $storeExecutiveRole->givePermissionTo([
            'view_dashboard',
            'manage_inventory',
            'view_inventory',
            'manage_stock',
            'view_stock',
            'manage_warehouse',
            'view_warehouse',
            'manage_material_requests',
            'view_material_requests',
            'create_material_requests',
            'edit_material_requests',
            'manage_material_consumption',
            'view_material_consumption',
            'create_material_consumption',
            'edit_material_consumption',
            'manage_grn',
            'view_grn',
            'create_grn',
            'edit_grn',
            'manage_stock_valuation',
            'view_stock_valuation',
            'manage_stock_ledger',
            'view_stock_ledger',
            'manage_inventory_audit',
            'view_inventory_audit',
            'create_inventory_audit',
            'edit_inventory_audit',
            'view_materials',
            'view_products',
            'view_purchase_orders',
            'view_vendors',
        ]);

        // SERVICE ENGINEER permissions - Service Management Only
        $serviceEngineerRole = Role::findByName('SERVICE ENGINEER');
        $serviceEngineerRole->givePermissionTo([
            'view_dashboard',
            'manage_service_requests',
            'view_service_requests',
            'create_service_requests',
            'edit_service_requests',
            'manage_complaints',
            'view_complaints',
            'create_complaints',
            'edit_complaints',
            'manage_amc',
            'view_amc',
            'create_amc',
            'edit_amc',
            'manage_maintenance',
            'view_maintenance',
            'create_maintenance',
            'edit_maintenance',
            'view_projects',
            'view_project_details',
            'view_customers',
            'view_products',
            'view_documents',
            'upload_documents',
            'download_documents',
        ]);

        // HR MANAGER permissions - HR Management Only
        $hrManagerRole = Role::findByName('HR MANAGER');
        $hrManagerRole->givePermissionTo([
            'view_dashboard',
            'view_analytics',
            'view_reports',
            'manage_users',
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'manage_employees',
            'view_employees',
            'create_employees',
            'edit_employees',
            'delete_employees',
            'manage_attendance',
            'view_attendance',
            'create_attendance',
            'edit_attendance',
            'manage_leave_requests',
            'view_leave_requests',
            'create_leave_requests',
            'edit_leave_requests',
            'approve_leave_requests',
            'manage_payroll',
            'view_payroll',
            'create_payroll',
            'edit_payroll',
            'manage_performance_reviews',
            'view_performance_reviews',
            'create_performance_reviews',
            'edit_performance_reviews',
            'manage_job_applications',
            'view_job_applications',
            'create_job_applications',
            'edit_job_applications',
            'manage_expense_claims',
            'view_expense_claims',
            'create_expense_claims',
            'edit_expense_claims',
            'approve_expense_claims',
            'view_salary_slips',
            'create_salary_slips',
            'manage_appraisals',
            'view_appraisals',
            'create_appraisals',
            'edit_appraisals',
        ]);
    }
}