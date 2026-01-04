<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class ManageRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:manage {action} {--role=} {--user=} {--permission=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage roles and permissions in the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list-roles':
                $this->listRoles();
                break;
            case 'list-permissions':
                $this->listPermissions();
                break;
            case 'assign-role':
                $this->assignRole();
                break;
            case 'remove-role':
                $this->removeRole();
                break;
            case 'list-user-roles':
                $this->listUserRoles();
                break;
            case 'sync-permissions':
                $this->syncPermissions();
                break;
            default:
                $this->error('Invalid action. Available actions: list-roles, list-permissions, assign-role, remove-role, list-user-roles, sync-permissions');
        }
    }

    /**
     * List all available roles
     */
    private function listRoles()
    {
        $this->info('Available Roles:');
        $this->line('================');
        
        $roles = Role::all();
        foreach ($roles as $role) {
            $permissionCount = $role->permissions()->count();
            $userCount = $role->users()->count();
            $this->line("• {$role->name} (Permissions: {$permissionCount}, Users: {$userCount})");
        }
    }

    /**
     * List all available permissions
     */
    private function listPermissions()
    {
        $this->info('Available Permissions:');
        $this->line('=====================');
        
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('_', $permission->name)[0];
        });

        foreach ($permissions as $group => $groupPermissions) {
            $this->line("\n{$group}:");
            foreach ($groupPermissions as $permission) {
                $this->line("  • {$permission->name}");
            }
        }
    }

    /**
     * Assign a role to a user
     */
    private function assignRole()
    {
        $roleName = $this->option('role');
        $userEmail = $this->option('user');

        if (!$roleName || !$userEmail) {
            $this->error('Please provide both --role and --user options');
            return;
        }

        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $this->error("Role '{$roleName}' not found");
            return;
        }

        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            $this->error("User with email '{$userEmail}' not found");
            return;
        }

        if ($user->hasRole($roleName)) {
            $this->warn("User '{$userEmail}' already has role '{$roleName}'");
            return;
        }

        $user->assignRole($roleName);
        $this->info("Successfully assigned role '{$roleName}' to user '{$userEmail}'");
    }

    /**
     * Remove a role from a user
     */
    private function removeRole()
    {
        $roleName = $this->option('role');
        $userEmail = $this->option('user');

        if (!$roleName || !$userEmail) {
            $this->error('Please provide both --role and --user options');
            return;
        }

        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            $this->error("User with email '{$userEmail}' not found");
            return;
        }

        if (!$user->hasRole($roleName)) {
            $this->warn("User '{$userEmail}' does not have role '{$roleName}'");
            return;
        }

        $user->removeRole($roleName);
        $this->info("Successfully removed role '{$roleName}' from user '{$userEmail}'");
    }

    /**
     * List roles for a specific user
     */
    private function listUserRoles()
    {
        $userEmail = $this->option('user');

        if (!$userEmail) {
            $this->error('Please provide --user option');
            return;
        }

        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            $this->error("User with email '{$userEmail}' not found");
            return;
        }

        $this->info("Roles for user '{$userEmail}':");
        $this->line('===============================');
        
        $roles = $user->roles;
        if ($roles->isEmpty()) {
            $this->warn('No roles assigned');
        } else {
            foreach ($roles as $role) {
                $permissionCount = $role->permissions()->count();
                $this->line("• {$role->name} (Permissions: {$permissionCount})");
            }
        }
    }

    /**
     * Sync permissions for a role
     */
    private function syncPermissions()
    {
        $roleName = $this->option('role');
        $permissionName = $this->option('permission');

        if (!$roleName) {
            $this->error('Please provide --role option');
            return;
        }

        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $this->error("Role '{$roleName}' not found");
            return;
        }

        if ($permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if (!$permission) {
                $this->error("Permission '{$permissionName}' not found");
                return;
            }

            if ($role->hasPermissionTo($permissionName)) {
                $this->warn("Role '{$roleName}' already has permission '{$permissionName}'");
            } else {
                $role->givePermissionTo($permissionName);
                $this->info("Successfully granted permission '{$permissionName}' to role '{$roleName}'");
            }
        } else {
            $this->info("Current permissions for role '{$roleName}':");
            $this->line('==========================================');
            
            $permissions = $role->permissions;
            if ($permissions->isEmpty()) {
                $this->warn('No permissions assigned');
            } else {
                foreach ($permissions as $permission) {
                    $this->line("• {$permission->name}");
                }
            }
        }
    }
}


