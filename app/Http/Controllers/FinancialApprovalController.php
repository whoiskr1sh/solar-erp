<?php

namespace App\Http\Controllers;

use App\Models\Advance;
use App\Models\SiteExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialApprovalController extends Controller
{
    public function hrIndex(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('HR MANAGER')) {
            abort(403, 'Unauthorized action. Only HR Manager can access this page.');
        }

        $type = $request->get('type', 'site-expenses');

        if ($type === 'advances') {
            $pendingRequests = Advance::with(['creator', 'employee', 'vendor', 'project'])
                ->where('status', 'pending')
                ->where('approval_level', 'hr')
                ->latest()
                ->paginate(15);
        } else {
            $pendingRequests = SiteExpense::with(['creator', 'project'])
                ->where('status', 'pending')
                ->where('approval_level', 'hr')
                ->latest()
                ->paginate(15);
        }

        $stats = [
            'pending_expenses' => SiteExpense::where('status', 'pending')->where('approval_level', 'hr')->count(),
            'pending_advances' => Advance::where('status', 'pending')->where('approval_level', 'hr')->count(),
        ];

        return view('financial-approvals.hr-index', compact('pendingRequests', 'type', 'stats'));
    }

    public function adminIndex(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('SUPER ADMIN')) {
            abort(403, 'Unauthorized action. Only Super Admin can access this page.');
        }

        $type = $request->get('type', 'site-expenses');

        if ($type === 'advances') {
            $pendingRequests = Advance::with(['creator', 'employee', 'vendor', 'project', 'hrApprover'])
                ->where('status', 'pending')
                ->where('approval_level', 'admin')
                ->latest()
                ->paginate(15);
        } else {
            $pendingRequests = SiteExpense::with(['creator', 'project', 'hrApprover'])
                ->where('status', 'pending')
                ->where('approval_level', 'admin')
                ->latest()
                ->paginate(15);
        }

        $stats = [
            'pending_expenses' => SiteExpense::where('status', 'pending')->where('approval_level', 'admin')->count(),
            'pending_advances' => Advance::where('status', 'pending')->where('approval_level', 'admin')->count(),
        ];

        return view('financial-approvals.admin-index', compact('pendingRequests', 'type', 'stats'));
    }
}
