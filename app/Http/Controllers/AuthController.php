<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Todo;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Get all users with their roles (including inactive users)
        // Ensure we always return an array, even if empty
        try {
            $users = User::with('roles')
                ->orderBy('name')
                ->get()
                ->map(function($user) {
                    return [
                        'name' => $user->name ?? 'Unknown',
                        'email' => $user->email ?? '',
                        'password' => 'password123', // Default password for all demo users
                        'role' => $user->roles->first()->name ?? 'No Role',
                        'is_active' => $user->is_active ?? true,
                    ];
                })
                ->toArray();
        } catch (\Exception $e) {
            // If there's any error, return empty array
            \Log::error('Error fetching users for login page: ' . $e->getMessage());
            $users = [];
        }
        
        return view('auth.login', compact('users'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Reset daily todo modal flag so it shows once per new login
            $request->session()->forget('daily_todo_modal_shown');
            
            // Get authenticated user and update last login time
            /** @var User $user */
            $user = Auth::user();
            if ($user) {
                $user->last_login_at = now();
                $user->save();

                // Redirect based on user role (original behavior)
                return $this->redirectBasedOnRole($user);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Redirect user based on their role
     */
    private function redirectBasedOnRole($user)
    {
        $role = $user->roles->first();
        
        if (!$role) {
            return redirect('/dashboard');
        }

        switch ($role->name) {
            case 'SUPER ADMIN':
                return redirect('/dashboard');
            case 'SALES MANAGER':
                return redirect('/dashboard/sales-manager');
            case 'TELE SALES':
                return redirect('/dashboard/tele-sales');
            case 'FIELD SALES':
                return redirect('/dashboard/field-sales');
            case 'PROJECT MANAGER':
                return redirect('/dashboard/project-manager');
            case 'PROJECT ENGINEER':
                return redirect('/dashboard/project-engineer');
            case 'LIASONING EXECUTIVE':
                return redirect('/dashboard/liaisoning');
            case 'QUALITY ENGINEER':
                return redirect('/dashboard/quality-engineer');
            case 'PURCHASE MANAGER/EXECUTIVE':
                return redirect('/dashboard/purchase-manager');
            case 'ACCOUNT EXECUTIVE':
                return redirect('/dashboard/account-executive');
            case 'STORE EXECUTIVE':
                return redirect('/dashboard/store-executive');
            case 'SERVICE ENGINEER':
                return redirect('/dashboard/service-engineer');
            case 'HR MANAGER':
                return redirect('/dashboard/hr-manager');
            default:
                return redirect('/dashboard');
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            // Check for incomplete todos (pending/in_progress)
            $incompleteTodos = Todo::forUser($user->id)
                ->where(function($query) {
                    $query->where('task_date', now()->toDateString())
                          ->orWhere(function($q) {
                              $q->where('task_date', '<', now()->toDateString())
                                ->whereIn('status', ['pending', 'in_progress'])
                                ->where('is_carried_over', true);
                          });
                })
                ->whereIn('status', ['pending', 'in_progress'])
                ->count();

            // Check for not_completed todos without reason
            $notCompletedWithoutReason = Todo::forUser($user->id)
                ->where(function($query) {
                    $query->where('task_date', now()->toDateString())
                          ->orWhere(function($q) {
                              $q->where('task_date', '<', now()->toDateString())
                                ->where('status', 'not_completed')
                                ->where('is_carried_over', true);
                          });
                })
                ->where('status', 'not_completed')
                ->where(function($q) {
                    $q->whereNull('not_completed_reason')
                      ->orWhere('not_completed_reason', '');
                })
                ->count();

            // Check for completed todos without remarks
            $completedWithoutRemarks = Todo::forUser($user->id)
                ->where(function($query) {
                    $query->where('task_date', now()->toDateString())
                          ->orWhere(function($q) {
                              $q->where('task_date', '<', now()->toDateString())
                                ->where('status', 'completed');
                          });
                })
                ->where('status', 'completed')
                ->where(function($q) {
                    $q->whereNull('remarks')
                      ->orWhere('remarks', '');
                })
                ->count();

            $errorMessages = [];
            
            if ($incompleteTodos > 0) {
                $errorMessages[] = "You have {$incompleteTodos} incomplete task(s). Please update their status before logging out.";
            }
            
            if ($notCompletedWithoutReason > 0) {
                $errorMessages[] = "You have {$notCompletedWithoutReason} not completed task(s) without reason. Please provide a reason for not completing them.";
            }
            
            if ($completedWithoutRemarks > 0) {
                $errorMessages[] = "You have {$completedWithoutRemarks} completed task(s) without remarks. Please add remarks for completed tasks.";
            }

            if (!empty($errorMessages)) {
                return back()->with('error', implode(' ', $errorMessages));
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
