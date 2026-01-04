<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RoleSeeder::class);
        
        // Create users for all roles
        $this->call(UserSeeder::class);

        // Seed leads
        $this->call(LeadSeeder::class);
        
        // Seed projects
        $this->call(ProjectSeeder::class);
        
        // Seed products
        $this->call(ProductSeeder::class);
        
            // Seed invoices
            $this->call(InvoiceSeeder::class);
            
        // Seed quotations
        $this->call(QuotationSeeder::class);
        
        // Seed documents
        $this->call(DocumentSeeder::class);
        
        // Seed vendors
        $this->call(VendorSeeder::class);
        
        // Seed activities
        $this->call(ActivitySeeder::class);
        
        // Seed commissions
        $this->call(CommissionSeeder::class);
        
        // Seed channel partners
        $this->call(ChannelPartnerSeeder::class);
        
        // Seed tasks
        $this->call(TaskSeeder::class);
        
        // Seed resource allocations
        $this->call(ResourceAllocationSeeder::class);
        
        // Seed escalations
        $this->call(EscalationSeeder::class);
        
        // Seed costing
        $this->call(CostingSeeder::class);
        
        // Seed expense categories
        $this->call(ExpenseCategorySeeder::class);
        
        // Seed expenses
        $this->call(ExpenseSeeder::class);
        
        // Seed project profitabilities
        $this->call(ProjectProfitabilitySeeder::class);
        
        // Seed budget categories
        $this->call(BudgetCategorySeeder::class);
        
        // Seed budgets
        $this->call(BudgetSeeder::class);
        
        // Seed payment milestones
        $this->call(PaymentMilestoneSeeder::class);
        
        // Seed material requests
        $this->call(MaterialRequestSeeder::class);
        $this->call(MaterialSeeder::class);
        
        // Seed material consumptions
        $this->call(MaterialConsumptionSeeder::class);
        
        // Seed daily progress reports
        $this->call(DailyProgressReportSeeder::class);
        
        // Seed notifications
        $this->call(NotificationSeeder::class);
        
        // Seed site warehouses
        $this->call(SiteWarehouseSeeder::class);
        
        // Seed inventory modules
        $this->call(StockValuationSeeder::class);
        $this->call(StockLedgerSeeder::class);
        $this->call(QualityCheckSeeder::class);
        $this->call(InventoryAuditSeeder::class);
        
        // Seed O&M modules
        $this->call(ComplaintSeeder::class);
        $this->call(AMCSeeder::class);
        $this->call(OMMaintenanceSeeder::class);
        
        // Seed HR modules
        $this->call(EmployeeSeeder::class);
        $this->call(LeaveRequestSeeder::class);
        $this->call(AttendanceSeeder::class);
        $this->call(PayrollSeeder::class);
        $this->call(PerformanceReviewSeeder::class);
        $this->call(AppraisalSeeder::class);
        $this->call(JobApplicationSeeder::class);
        $this->call(ExpenseClaimSeeder::class);
        $this->call(SalarySlipSeeder::class);
    }
}
