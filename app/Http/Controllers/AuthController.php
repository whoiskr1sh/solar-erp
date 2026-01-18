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
            $today = now();
            $isWeekend = $today->isSaturday() || $today->isSunday();

            // Check for incomplete todos (pending/in_progress)
            $incompleteTodos = Todo::forUser($user->id)
                ->where('task_date', $today->toDateString())
                ->whereIn('status', ['pending', 'in_progress'])
                ->get();

            // Carry forward today's incomplete tasks to tomorrow (if logging out on a weekday)
            if ($incompleteTodos->count() > 0 && !$isWeekend) {
                $tomorrow = $today->copy()->addDay()->toDateString();
                foreach ($incompleteTodos as $todo) {
                    $todo->update([
                        'task_date' => $tomorrow,
                        'is_carried_over' => true,
                    ]);
                }
            }

            // On Saturday or Sunday, block logout if any incomplete tasks remain
            if ($isWeekend && $incompleteTodos->count() > 0) {
                return back()->with('error', 'You cannot logout on Saturday or Sunday until all your tasks for today are completed.');
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
