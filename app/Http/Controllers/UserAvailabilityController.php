<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserAvailabilityController extends Controller
{
    /**
     * Toggle user availability for follow-up
     */
    public function toggle(Request $request)
    {
        $user = Auth::user();
        
        // Convert string '0'/'1' to boolean
        $isAvailable = filter_var($request->is_available, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        
        // Handle string '0'/'1' from form
        if ($isAvailable === null) {
            $isAvailable = $request->is_available === '1' || $request->is_available === 1 || $request->is_available === true;
        }
        
        // Make reason required when marking as unavailable
        $rules = [
            'is_available' => 'required',
            'unavailable_until' => 'nullable|date|after_or_equal:today',
        ];
        
        // If marking as unavailable, reason is required
        if (!$isAvailable) {
            $rules['reason'] = 'required|string|max:500';
        } else {
            $rules['reason'] = 'nullable|string|max:500';
        }
        
        $request->validate($rules);

        $user->update([
            'is_available_for_followup' => $isAvailable,
            'unavailability_reason' => $isAvailable ? null : $request->reason,
            'unavailable_until' => $isAvailable ? null : $request->unavailable_until,
        ]);

        $status = $isAvailable ? 'available' : 'unavailable';
        
        return redirect()->back()->with('success', "You are now marked as {$status} for follow-up.");
    }

    /**
     * List unavailable users (for Sales Manager and Admin)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Only Sales Manager and Admin can view unavailable users
        if (!$user->hasRole('SALES MANAGER') && !$user->hasRole('SUPER ADMIN')) {
            abort(403, 'You do not have permission to view unavailable users.');
        }
        
        // Query for unavailable users using the same logic as isAvailableForFollowup()
        // A user is unavailable if:
        // 1. is_available_for_followup is false, OR
        // 2. is_available_for_followup is true AND unavailable_until is set and is in the future
        $query = User::where('is_active', true)
            ->where(function($q) {
                $q->where('is_available_for_followup', false)
                  ->orWhere(function($q2) {
                      $q2->where('is_available_for_followup', true)
                         ->whereNotNull('unavailable_until')
                         ->where('unavailable_until', '>', now());
                  });
            })
            ->with('roles');
        
        // Filter by role if provided
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $unavailableUsers = $query->orderBy('updated_at', 'desc')->paginate(20);
        
        // Get all roles for filter
        $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();
        
        // Statistics - using the same logic as the query
        $unavailableQuery = User::where('is_active', true)
            ->where(function($q) {
                $q->where('is_available_for_followup', false)
                  ->orWhere(function($q2) {
                      $q2->where('is_available_for_followup', true)
                         ->whereNotNull('unavailable_until')
                         ->where('unavailable_until', '>', now());
                  });
            });
        
        $availableQuery = User::where('is_active', true)
            ->where('is_available_for_followup', true)
            ->where(function($q) {
                $q->whereNull('unavailable_until')
                  ->orWhere('unavailable_until', '<=', now());
            });
        
        $stats = [
            'total_unavailable' => $unavailableQuery->count(),
            'total_available' => $availableQuery->count(),
            'unavailable_with_date' => User::where('is_active', true)
                ->where(function($q) {
                    $q->where('is_available_for_followup', false)
                      ->orWhere(function($q2) {
                          $q2->where('is_available_for_followup', true)
                             ->whereNotNull('unavailable_until')
                             ->where('unavailable_until', '>', now());
                      });
                })
                ->whereNotNull('unavailable_until')
                ->where('unavailable_until', '>', now())
                ->count(),
        ];
        
        return view('admin.unavailable-users.index', compact('unavailableUsers', 'roles', 'stats'));
    }
}
