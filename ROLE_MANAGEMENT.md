# Solar ERP - Role Management System

## Overview
This document describes the comprehensive role-based access control (RBAC) system implemented in the Solar ERP application using Laravel Spatie Permission package.

## Available Roles

### 1. SUPER ADMIN
- **Description**: Complete system access with all permissions
- **Permissions**: All system permissions
- **Use Case**: System administrators, IT managers

### 2. SALES MANAGER
- **Description**: Manages sales operations and team
- **Key Permissions**:
  - Lead management (view, create, edit, assign, convert)
  - Project viewing and quotation management
  - Customer management
  - Commission viewing and management
  - Analytics and reports
- **Use Case**: Sales department heads, regional sales managers

### 3. TELE SALES
- **Description**: Handles telephone-based sales activities
- **Key Permissions**:
  - Lead management (view, create, edit)
  - Quotation creation and viewing
  - Customer management (view, create, edit)
  - Product information access
- **Use Case**: Inside sales representatives, call center agents

### 4. FIELD SALES
- **Description**: Manages on-site sales activities
- **Key Permissions**:
  - Lead management (view, create, edit)
  - Project viewing and quotation management
  - Customer management (view, create, edit)
  - Commission viewing
  - Product information access
- **Use Case**: Outside sales representatives, territory managers

### 5. PROJECT MANAGER
- **Description**: Oversees project execution and delivery
- **Key Permissions**:
  - Complete project management (create, edit, assign)
  - Task management and assignment
  - Material request and consumption management
  - Daily progress reporting
  - Quality check management
  - Contractor and escalation management
  - Project budget management
- **Use Case**: Project leads, site managers, construction managers

### 6. PROJECT ENGINEER
- **Description**: Technical project execution and monitoring
- **Key Permissions**:
  - Project viewing and task management
  - Material request and consumption creation
  - Daily progress reporting
  - Quality check creation and editing
  - Escalation creation
- **Use Case**: Site engineers, technical supervisors

### 7. LIASONING EXECUTIVE
- **Description**: Handles regulatory and external coordination
- **Key Permissions**:
  - Liaisoning management (permits, approvals)
  - Document management (upload, download)
  - Vendor management (view, create, edit)
  - Project viewing for coordination
- **Use Case**: Regulatory compliance officers, external coordination specialists

### 8. QUALITY ENGINEER
- **Description**: Ensures quality standards and compliance
- **Key Permissions**:
  - Quality check management (create, edit, approve)
  - Inventory audit management
  - Material and product quality verification
  - Stock management viewing
- **Use Case**: Quality assurance engineers, compliance officers

### 9. PURCHASE MANAGER/EXECUTIVE
- **Description**: Manages procurement and vendor relationships
- **Key Permissions**:
  - Vendor management (complete CRUD)
  - Purchase order management (create, edit, approve)
  - Purchase requisition management
  - RFQ (Request for Quotation) management
  - GRN (Goods Receipt Note) management
  - Inventory and stock viewing
- **Use Case**: Procurement managers, purchasing executives

### 10. ACCOUNT EXECUTIVE
- **Description**: Handles financial operations and accounting
- **Key Permissions**:
  - Invoice management (create, edit, approve)
  - Quotation management
  - Payment request and milestone management
  - Budget management
  - Expense management
  - Financial reporting
  - Customer and vendor financial data access
- **Use Case**: Accountants, financial executives, billing specialists

### 11. STORE EXECUTIVE
- **Description**: Manages inventory and warehouse operations
- **Key Permissions**:
  - Complete inventory management
  - Stock and warehouse management
  - Material request and consumption management
  - GRN management
  - Stock valuation and ledger management
  - Inventory audit management
  - Purchase order viewing
- **Use Case**: Warehouse managers, inventory controllers, storekeepers

### 12. SERVICE ENGINEER
- **Description**: Handles post-installation service and maintenance
- **Key Permissions**:
  - Service request management
  - Complaint management
  - AMC (Annual Maintenance Contract) management
  - Maintenance management
  - Customer service documentation
  - Project viewing for service context
- **Use Case**: Service technicians, maintenance engineers, customer support

### 13. HR MANAGER
- **Description**: Manages human resources and employee operations
- **Key Permissions**:
  - Complete user and employee management
  - Attendance management
  - Leave request management and approval
  - Payroll management
  - Performance review management
  - Job application management
  - Expense claim management and approval
  - Salary slip management
  - Appraisal management
- **Use Case**: HR managers, HR executives, people operations

## Permission Categories

### Dashboard & Analytics
- `view_dashboard` - Access to main dashboard
- `view_analytics` - Access to analytics and insights
- `view_reports` - Access to various reports

### User Management
- `manage_users` - Complete user management
- `view_users` - View user information
- `create_users` - Create new users
- `edit_users` - Edit user information
- `delete_users` - Delete users

### Lead Management
- `manage_leads` - Complete lead management
- `view_leads` - View leads
- `create_leads` - Create new leads
- `edit_leads` - Edit lead information
- `delete_leads` - Delete leads
- `convert_leads` - Convert leads to projects
- `assign_leads` - Assign leads to team members

### Project Management
- `manage_projects` - Complete project management
- `view_projects` - View projects
- `create_projects` - Create new projects
- `edit_projects` - Edit project information
- `delete_projects` - Delete projects
- `assign_projects` - Assign projects
- `view_project_details` - View detailed project information
- `manage_project_tasks` - Manage project tasks
- `manage_project_materials` - Manage project materials
- `manage_project_budget` - Manage project budget

### Task Management
- `manage_tasks` - Complete task management
- `view_tasks` - View tasks
- `create_tasks` - Create new tasks
- `edit_tasks` - Edit task information
- `delete_tasks` - Delete tasks
- `assign_tasks` - Assign tasks
- `update_task_status` - Update task status

### Inventory Management
- `manage_inventory` - Complete inventory management
- `view_inventory` - View inventory
- `manage_stock` - Manage stock levels
- `view_stock` - View stock information
- `manage_warehouse` - Manage warehouse operations
- `manage_material_requests` - Manage material requests
- `manage_material_consumption` - Manage material consumption
- `manage_grn` - Manage Goods Receipt Notes
- `manage_stock_valuation` - Manage stock valuation
- `manage_stock_ledger` - Manage stock ledger
- `manage_inventory_audit` - Manage inventory audits

### Financial Management
- `manage_accounts` - Complete account management
- `view_accounts` - View account information
- `manage_budget` - Manage budgets
- `manage_expenses` - Manage expenses
- `manage_payment_requests` - Manage payment requests
- `manage_payment_milestones` - Manage payment milestones
- `view_financial_reports` - View financial reports

### HR Management
- `manage_employees` - Complete employee management
- `view_employees` - View employee information
- `create_employees` - Create new employees
- `edit_employees` - Edit employee information
- `delete_employees` - Delete employees
- `manage_attendance` - Manage attendance
- `manage_leave_requests` - Manage leave requests
- `manage_payroll` - Manage payroll
- `manage_performance_reviews` - Manage performance reviews
- `manage_job_applications` - Manage job applications
- `manage_expense_claims` - Manage expense claims

## Usage Instructions

### Running the Role Seeder
```bash
php artisan db:seed --class=RoleSeeder
```

### Managing Roles via Command Line
```bash
# List all roles
php artisan roles:manage list-roles

# List all permissions
php artisan roles:manage list-permissions

# Assign role to user
php artisan roles:manage assign-role --role="SALES MANAGER" --user="user@example.com"

# Remove role from user
php artisan roles:manage remove-role --role="SALES MANAGER" --user="user@example.com"

# List user roles
php artisan roles:manage list-user-roles --user="user@example.com"

# Sync permissions for a role
php artisan roles:manage sync-permissions --role="SALES MANAGER"
```

### Programmatic Role Management
```php
// Assign role to user
$user = User::find(1);
$user->assignRole('SALES MANAGER');

// Check if user has role
if ($user->hasRole('SALES MANAGER')) {
    // User has the role
}

// Check if user has permission
if ($user->hasPermissionTo('manage_leads')) {
    // User has the permission
}

// Get user roles
$roles = $user->roles;

// Get role permissions
$role = Role::findByName('SALES MANAGER');
$permissions = $role->permissions;
```

## Security Considerations

1. **Principle of Least Privilege**: Each role is granted only the minimum permissions necessary for their job function.

2. **Role Hierarchy**: SUPER ADMIN has all permissions, while other roles have specific, limited access.

3. **Permission Granularity**: Permissions are broken down into specific actions (view, create, edit, delete) for fine-grained control.

4. **Regular Audits**: Use the command-line tools to regularly audit role assignments and permissions.

5. **User Deactivation**: When users leave the organization, ensure their accounts are deactivated and roles removed.

## Best Practices

1. **Role Assignment**: Assign roles based on job functions, not individual preferences.

2. **Permission Review**: Regularly review and update role permissions as business requirements change.

3. **User Training**: Ensure users understand their role limitations and responsibilities.

4. **Documentation**: Keep this documentation updated as roles and permissions evolve.

5. **Testing**: Test role assignments in development environment before applying to production.

## Troubleshooting

### Common Issues

1. **Permission Denied**: Check if user has the required role and permission.
2. **Role Not Found**: Ensure the role exists in the database.
3. **User Not Found**: Verify user email/ID is correct.
4. **Seeder Errors**: Clear cache and run migrations before seeding.

### Debug Commands
```bash
# Clear permission cache
php artisan permission:cache-reset

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```


