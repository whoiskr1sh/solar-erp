<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\PerformanceReview;
use App\Models\Appraisal;
use App\Models\JobApplication;
use App\Models\ExpenseClaim;
use App\Models\SalarySlip;
use App\Models\User;
use App\Mail\LeaveRequestNotification;

class HRController extends Controller
{
    /**
     * Display Employee Management page
     */
    public function employeeManagement(Request $request): View
    {
        $query = Employee::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('employee_id', 'like', '%' . $request->search . '%')
                  ->orWhere('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->orderBy('joining_date', 'desc')->paginate(20);

        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('status', 'active')->count(),
            'departments' => Employee::distinct('department')->count(),
            'total_salary' => Employee::sum('salary'),
        ];

        return view('hr.employee-management', compact('employees', 'stats'));
    }

    /**
     * Export employees as CSV (applies same filters as employeeManagement).
     */
    public function exportEmployees(Request $request)
    {
        $query = Employee::query();

        // Apply same filters as employeeManagement
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('employee_id', 'like', '%' . $request->search . '%')
                  ->orWhere('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->orderBy('joining_date', 'desc')->get();

        $filename = 'employees_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($employees) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Employee ID',
                'First Name',
                'Last Name',
                'Full Name',
                'Email',
                'Phone',
                'Department',
                'Designation',
                'Status',
                'Joining Date',
                'Salary',
            ]);

            foreach ($employees as $employee) {
                fputcsv($file, [
                    $employee->employee_id,
                    $employee->first_name,
                    $employee->last_name,
                    $employee->full_name ?? trim(($employee->first_name ?? '') . ' ' . ($employee->last_name ?? '')),
                    $employee->email,
                    $employee->phone,
                    $employee->department,
                    $employee->designation,
                    $employee->status,
                    $employee->joining_date ? $employee->joining_date->format('Y-m-d') : null,
                    $employee->salary,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display Leave Management page
     */
    public function leaveManagement(Request $request, $role = null): View
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $query = LeaveRequest::query();
        
        // Convert role slug from URL to role name format (e.g., "sales-manager" -> "SALES MANAGER")
        // Handle roles with "/" like "PURCHASE MANAGER/EXECUTIVE" -> "purchase-manager-executive" -> "PURCHASE MANAGER EXECUTIVE"
        if ($role) {
            $targetRole = strtoupper(str_replace('-', ' ', $role));
            // Try to match the actual role name - some roles have "/" in them
            // Check if the converted role matches, if not try with "/" instead of space
            $possibleRoleNames = [
                $targetRole,
                str_replace(' ', '/', $targetRole), // For "PURCHASE MANAGER/EXECUTIVE"
            ];
            
            // Find the actual role name that exists
            $actualRole = \Spatie\Permission\Models\Role::whereIn('name', $possibleRoleNames)->first();
            if ($actualRole) {
                $targetRole = $actualRole->name;
            }
        } else {
            $targetRole = $user->roles->first()?->name;
        }

        // Role-based filtering: SUPER ADMIN and HR MANAGER see all leaves, others see only their own
        if ($user->hasRole('SUPER ADMIN') || $user->hasRole('HR MANAGER')) {
            // Admin and HR can see all leaves, but if role is specified in URL, filter by that role
            if ($targetRole && $targetRole !== 'SUPER ADMIN' && $targetRole !== 'HR MANAGER') {
                // Get all users with the specified role
                $roleUserIds = \App\Models\User::whereHas('roles', function($q) use ($targetRole) {
                    $q->where('name', $targetRole);
                })->pluck('employee_id')->filter()->toArray();
                
                if (!empty($roleUserIds)) {
                    $query->whereIn('employee_id', $roleUserIds);
                } else {
                    $query->whereRaw('1 = 0');
                }
            }
            // If no role specified or role is SUPER ADMIN/HR MANAGER, show all leaves (no filter)
        } else {
            // For other roles, show only leaves of the current user's role
            // Get all users with the same role as current user
            $sameRoleUserIds = \App\Models\User::whereHas('roles', function($q) use ($user) {
                $q->whereIn('name', $user->roles->pluck('name'));
            })->pluck('employee_id')->filter()->toArray();
            
            if (!empty($sameRoleUserIds)) {
                $query->whereIn('employee_id', $sameRoleUserIds);
            } else {
                // If user has employee_id, show only their own leaves
                if ($user->employee_id) {
                    $query->where('employee_id', $user->employee_id);
                } else {
                    // If no employee_id, show empty result
                    $query->whereRaw('1 = 0');
                }
            }
        }

        // Apply filters
        if ($request->filled('search')) {
            $query->where('employee_id', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        $leaveRequests = $query->with('employee')->orderBy('start_date', 'desc')->paginate(20);

        // Role-based stats - use the same query logic as above
        if ($user->hasRole('SUPER ADMIN') || $user->hasRole('HR MANAGER')) {
            // Admin and HR see all stats, but if role is specified, filter by that role
            if ($targetRole && $targetRole !== 'SUPER ADMIN' && $targetRole !== 'HR MANAGER') {
                $roleUserIds = \App\Models\User::whereHas('roles', function($q) use ($targetRole) {
                    $q->where('name', $targetRole);
                })->pluck('employee_id')->filter()->toArray();
                
                if (!empty($roleUserIds)) {
                    $statsQuery = LeaveRequest::whereIn('employee_id', $roleUserIds);
                } else {
                    $statsQuery = LeaveRequest::whereRaw('1 = 0');
                }
                
                $stats = [
                    'total_requests' => $statsQuery->count(),
                    'pending_requests' => (clone $statsQuery)->where('status', 'pending')->count(),
                    'approved_requests' => (clone $statsQuery)->where('status', 'approved')->count(),
                    'rejected_requests' => (clone $statsQuery)->where('status', 'rejected')->count(),
                ];
            } else {
                // Show all stats
        $stats = [
            'total_requests' => LeaveRequest::count(),
            'pending_requests' => LeaveRequest::where('status', 'pending')->count(),
            'approved_requests' => LeaveRequest::where('status', 'approved')->count(),
            'rejected_requests' => LeaveRequest::where('status', 'rejected')->count(),
        ];
            }
        } else {
            // Other roles see only their role's stats
            $sameRoleUserIds = \App\Models\User::whereHas('roles', function($q) use ($user) {
                $q->whereIn('name', $user->roles->pluck('name'));
            })->pluck('employee_id')->filter()->toArray();
            
            if (!empty($sameRoleUserIds)) {
                $statsQuery = LeaveRequest::whereIn('employee_id', $sameRoleUserIds);
            } else {
                if ($user->employee_id) {
                    $statsQuery = LeaveRequest::where('employee_id', $user->employee_id);
                } else {
                    $statsQuery = LeaveRequest::whereRaw('1 = 0');
                }
            }
            
            $stats = [
                'total_requests' => $statsQuery->count(),
                'pending_requests' => (clone $statsQuery)->where('status', 'pending')->count(),
                'approved_requests' => (clone $statsQuery)->where('status', 'approved')->count(),
                'rejected_requests' => (clone $statsQuery)->where('status', 'rejected')->count(),
            ];
        }

        return view('hr.leave-management', compact('leaveRequests', 'stats'));
    }

    /**
     * Export leave requests as CSV (applies same filters and role logic as leaveManagement).
     */
    public function exportLeaveManagement(Request $request, $role = null)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $query = LeaveRequest::query();

        if ($role) {
            $targetRole = strtoupper(str_replace('-', ' ', $role));
            $possibleRoleNames = [
                $targetRole,
                str_replace(' ', '/', $targetRole),
            ];

            $actualRole = \Spatie\Permission\Models\Role::whereIn('name', $possibleRoleNames)->first();
            if ($actualRole) {
                $targetRole = $actualRole->name;
            }
        } else {
            $targetRole = $user->roles->first()?->name;
        }

        if ($user->hasRole('SUPER ADMIN') || $user->hasRole('HR MANAGER')) {
            if ($targetRole && $targetRole !== 'SUPER ADMIN' && $targetRole !== 'HR MANAGER') {
                $roleUserIds = \App\Models\User::whereHas('roles', function($q) use ($targetRole) {
                    $q->where('name', $targetRole);
                })->pluck('employee_id')->filter()->toArray();

                if (!empty($roleUserIds)) {
                    $query->whereIn('employee_id', $roleUserIds);
                } else {
                    $query->whereRaw('1 = 0');
                }
            }
        } else {
            $sameRoleUserIds = \App\Models\User::whereHas('roles', function($q) use ($user) {
                $q->whereIn('name', $user->roles->pluck('name'));
            })->pluck('employee_id')->filter()->toArray();

            if (!empty($sameRoleUserIds)) {
                $query->whereIn('employee_id', $sameRoleUserIds);
            } else {
                if ($user->employee_id) {
                    $query->where('employee_id', $user->employee_id);
                } else {
                    $query->whereRaw('1 = 0');
                }
            }
        }

        // Apply same filters as leaveManagement
        if ($request->filled('search')) {
            $query->where('employee_id', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        $leaveRequests = $query->with('employee')->orderBy('start_date', 'desc')->get();

        $filename = 'leave_requests_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($leaveRequests) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Employee Name',
                'Employee ID',
                'Leave Type',
                'Start Date',
                'End Date',
                'Total Days',
                'Status',
                'Reason',
            ]);

            foreach ($leaveRequests as $leaveRequest) {
                fputcsv($file, [
                    $leaveRequest->employee->name ?? null,
                    $leaveRequest->employee_id,
                    $leaveRequest->leave_type,
                    $leaveRequest->start_date ? $leaveRequest->start_date->format('Y-m-d') : null,
                    $leaveRequest->end_date ? $leaveRequest->end_date->format('Y-m-d') : null,
                    $leaveRequest->total_days,
                    $leaveRequest->status,
                    $leaveRequest->reason,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display Attendance page
     */
    public function attendance(Request $request): View
    {
        $query = Attendance::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where('employee_id', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(20);

        $stats = [
            'total_records' => Attendance::count(),
            'present_today' => Attendance::where('date', today())->where('status', 'present')->count(),
            'absent_today' => Attendance::where('date', today())->where('status', 'absent')->count(),
            'late_today' => Attendance::where('date', today())->where('status', 'late')->count(),
        ];

        return view('hr.attendance', compact('attendances', 'stats'));
    }

    /**
     * Export attendance records as CSV (applies same filters as attendance).
     */
    public function exportAttendance(Request $request)
    {
        $query = Attendance::query();

        // Apply same filters as attendance
        if ($request->filled('search')) {
            $query->where('employee_id', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        $filename = 'attendance_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($attendances) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Employee ID',
                'Date',
                'Check In',
                'Check Out',
                'Total Hours',
                'Status',
                'Remarks',
            ]);

            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->employee_id,
                    $attendance->date ? $attendance->date->format('Y-m-d') : null,
                    $attendance->check_in ? $attendance->check_in->format('H:i') : null,
                    $attendance->check_out ? $attendance->check_out->format('H:i') : null,
                    $attendance->total_hours,
                    $attendance->status,
                    $attendance->remarks,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show a single attendance record.
     */
    public function showAttendance(Attendance $attendance): View
    {
        return view('hr.attendance-show', compact('attendance'));
    }

    /**
     * Show the form for editing an attendance record.
     */
    public function editAttendance(Attendance $attendance): View
    {
        return view('hr.attendance-edit', compact('attendance'));
    }

    /**
     * Update an attendance record.
     */
    public function updateAttendance(Request $request, Attendance $attendance)
    {
        $data = $request->validate([
            'employee_id' => 'required|string|max:50',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'total_hours' => 'nullable|numeric|min:0',
            'status' => 'required|in:present,absent,late,half_day',
            'remarks' => 'nullable|string|max:500',
        ]);

        $attendance->update($data);

        return redirect()->route('hr.attendance')->with('success', 'Attendance updated successfully.');
    }

    /**
     * Delete an attendance record.
     */
    public function deleteAttendance(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('hr.attendance')->with('success', 'Attendance deleted successfully.');
    }

    /**
     * Display Salary & Payroll page
     */
    public function salaryPayroll(Request $request): View
    {
        $query = Payroll::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where('employee_id', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payroll_month')) {
            $query->where('payroll_month', $request->payroll_month);
        }

        if ($request->filled('payroll_year')) {
            $query->where('payroll_year', $request->payroll_year);
        }

        $payrolls = $query->orderBy('payroll_year', 'desc')
                         ->orderBy('payroll_month', 'desc')
                         ->paginate(20);

        $stats = [
            'total_payrolls' => Payroll::count(),
            'pending_payrolls' => Payroll::where('status', 'pending')->count(),
            'paid_payrolls' => Payroll::where('status', 'paid')->count(),
            'total_salary_paid' => Payroll::where('status', 'paid')->sum('net_salary'),
        ];

        return view('hr.salary-payroll', compact('payrolls', 'stats'));
    }

    /**
     * Export payroll records as CSV (applies same filters as salaryPayroll).
     */
    public function exportSalaryPayroll(Request $request)
    {
        $query = Payroll::query();

        // Apply same filters as salaryPayroll
        if ($request->filled('search')) {
            $query->where('employee_id', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payroll_month')) {
            $query->where('payroll_month', $request->payroll_month);
        }

        if ($request->filled('payroll_year')) {
            $query->where('payroll_year', $request->payroll_year);
        }

        $payrolls = $query->orderBy('payroll_year', 'desc')
                          ->orderBy('payroll_month', 'desc')
                          ->get();

        $filename = 'payroll_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($payrolls) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Employee ID',
                'Payroll Month',
                'Payroll Year',
                'Basic Salary',
                'Allowances',
                'Deductions',
                'Net Salary',
                'Status',
            ]);

            foreach ($payrolls as $payroll) {
                fputcsv($file, [
                    $payroll->employee_id,
                    $payroll->payroll_month,
                    $payroll->payroll_year,
                    $payroll->basic_salary,
                    $payroll->allowances,
                    $payroll->deductions,
                    $payroll->net_salary,
                    $payroll->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display Performance Management page
     */
    public function performanceManagement(Request $request): View
    {
        $query = PerformanceReview::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where('employee_id', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('review_period')) {
            $query->where('review_period', $request->review_period);
        }

        $performanceReviews = $query->orderBy('review_date', 'desc')->paginate(20);

        $stats = [
            'total_reviews' => PerformanceReview::count(),
            'completed_reviews' => PerformanceReview::where('status', 'completed')->count(),
            'pending_reviews' => PerformanceReview::where('status', 'submitted')->count(),
            'avg_rating' => PerformanceReview::where('status', 'completed')->avg('overall_rating'),
        ];

        return view('hr.performance-management', compact('performanceReviews', 'stats'));
    }

    /**
     * Export performance reviews as CSV (applies same filters as performanceManagement).
     */
    public function exportPerformanceManagement(Request $request)
    {
        $query = PerformanceReview::query();

        // Apply same filters as performanceManagement
        if ($request->filled('search')) {
            $query->where('employee_id', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('review_period')) {
            $query->where('review_period', $request->review_period);
        }

        $performanceReviews = $query->orderBy('review_date', 'desc')->get();

        $filename = 'performance_reviews_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($performanceReviews) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Employee ID',
                'Review Period',
                'Review Date',
                'Overall Rating',
                'Status',
                'Reviewed By',
            ]);

            foreach ($performanceReviews as $review) {
                fputcsv($file, [
                    $review->employee_id,
                    $review->review_period,
                    $review->review_date ? $review->review_date->format('Y-m-d') : null,
                    $review->overall_rating,
                    $review->status,
                    $review->reviewed_by,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display Appraisal & Meetings page
     */
    public function appraisalMeetings(Request $request): View
    {
        $query = Appraisal::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where('employee_id', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('appraisal_type')) {
            $query->where('appraisal_type', $request->appraisal_type);
        }

        $appraisals = $query->orderBy('appraisal_date', 'desc')->paginate(20);

        $stats = [
            'total_appraisals' => Appraisal::count(),
            'scheduled_appraisals' => Appraisal::where('status', 'scheduled')->count(),
            'completed_appraisals' => Appraisal::where('status', 'completed')->count(),
            'avg_performance_score' => Appraisal::where('status', 'completed')->avg('performance_score'),
        ];

        return view('hr.appraisal-meetings', compact('appraisals', 'stats'));
    }

    /**
     * Export appraisals as CSV (applies same filters as appraisalMeetings).
     */
    public function exportAppraisalMeetings(Request $request)
    {
        $query = Appraisal::query();

        // Apply same filters as appraisalMeetings
        if ($request->filled('search')) {
            $query->where('employee_id', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('appraisal_type')) {
            $query->where('appraisal_type', $request->appraisal_type);
        }

        $appraisals = $query->orderBy('appraisal_date', 'desc')->get();

        $filename = 'appraisals_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($appraisals) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Employee ID',
                'Appraisal Type',
                'Appraisal Date',
                'Performance Score',
                'Status',
                'Appraiser Name',
            ]);

            foreach ($appraisals as $appraisal) {
                fputcsv($file, [
                    $appraisal->employee_id,
                    $appraisal->appraisal_type,
                    $appraisal->appraisal_date ? $appraisal->appraisal_date->format('Y-m-d') : null,
                    $appraisal->performance_score,
                    $appraisal->status,
                    $appraisal->appraiser_name,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display Recruitment page
     */
    public function recruitment(Request $request): View
    {
        $query = JobApplication::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('application_number', 'like', '%' . $request->search . '%')
                  ->orWhere('applicant_name', 'like', '%' . $request->search . '%')
                  ->orWhere('job_title', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('job_title')) {
            $query->where('job_title', $request->job_title);
        }

        $jobApplications = $query->orderBy('application_date', 'desc')->paginate(20);

        $stats = [
            'total_applications' => JobApplication::count(),
            'new_applications' => JobApplication::where('status', 'applied')->count(),
            'interview_scheduled' => JobApplication::where('status', 'interview')->count(),
            'selected_candidates' => JobApplication::where('status', 'selected')->count(),
        ];

        return view('hr.recruitment', compact('jobApplications', 'stats'));
    }

    /**
     * Export job applications as CSV (applies same filters as recruitment).
     */
    public function exportRecruitment(Request $request)
    {
        $query = JobApplication::query();

        // Apply same filters as recruitment
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('application_number', 'like', '%' . $request->search . '%')
                  ->orWhere('applicant_name', 'like', '%' . $request->search . '%')
                  ->orWhere('job_title', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('job_title')) {
            $query->where('job_title', $request->job_title);
        }

        $jobApplications = $query->orderBy('application_date', 'desc')->get();

        $filename = 'job_applications_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($jobApplications) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Application Number',
                'Applicant Name',
                'Applicant Email',
                'Job Title',
                'Application Date',
                'Status',
                'Interview Date',
            ]);

            foreach ($jobApplications as $application) {
                fputcsv($file, [
                    $application->application_number,
                    $application->applicant_name,
                    $application->applicant_email,
                    $application->job_title,
                    $application->application_date ? $application->application_date->format('Y-m-d') : null,
                    $application->status,
                    $application->interview_date ? $application->interview_date->format('Y-m-d') : null,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display Employee Self Service Portal page
     */
    public function employeeSelfService(): View
    {
        return view('hr.employee-self-service');
    }

    /**
     * Display Expense & Reimbursement page
     */
    public function expenseReimbursement(Request $request): View
    {
        $query = ExpenseClaim::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('claim_number', 'like', '%' . $request->search . '%')
                  ->orWhere('employee_id', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('expense_type')) {
            $query->where('expense_type', $request->expense_type);
        }

        $expenseClaims = $query->orderBy('expense_date', 'desc')->paginate(20);

        $stats = [
            'total_claims' => ExpenseClaim::count(),
            'pending_claims' => ExpenseClaim::where('status', 'submitted')->count(),
            'approved_claims' => ExpenseClaim::where('status', 'approved')->count(),
            'total_amount' => ExpenseClaim::sum('amount'),
        ];

        return view('hr.expense-reimbursement', compact('expenseClaims', 'stats'));
    }

    /**
     * Export expense claims as CSV (applies same filters as expenseReimbursement).
     */
    public function exportExpenseReimbursement(Request $request)
    {
        $query = ExpenseClaim::query();

        // Apply same filters as expenseReimbursement
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('claim_number', 'like', '%' . $request->search . '%')
                  ->orWhere('employee_id', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('expense_type')) {
            $query->where('expense_type', $request->expense_type);
        }

        $expenseClaims = $query->orderBy('expense_date', 'desc')->get();

        $filename = 'expense_claims_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($expenseClaims) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Claim Number',
                'Employee ID',
                'Expense Type',
                'Expense Date',
                'Amount',
                'Status',
                'Description',
            ]);

            foreach ($expenseClaims as $claim) {
                fputcsv($file, [
                    $claim->claim_number,
                    $claim->employee_id,
                    $claim->expense_type,
                    $claim->expense_date ? $claim->expense_date->format('Y-m-d') : null,
                    $claim->amount,
                    $claim->status,
                    $claim->description,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display Auto Salary Slip page
     */
    public function autoSalarySlip(Request $request): View
    {
        $query = SalarySlip::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('slip_number', 'like', '%' . $request->search . '%')
                  ->orWhere('employee_id', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payroll_month')) {
            $query->where('payroll_month', $request->payroll_month);
        }

        if ($request->filled('payroll_year')) {
            $query->where('payroll_year', $request->payroll_year);
        }

        $salarySlips = $query->orderBy('generated_date', 'desc')->paginate(20);

        $stats = [
            'total_slips' => SalarySlip::count(),
            'generated_slips' => SalarySlip::where('status', 'generated')->count(),
            'sent_slips' => SalarySlip::where('status', 'sent')->count(),
            'total_salary' => SalarySlip::sum('net_salary'),
        ];

        return view('hr.auto-salary-slip', compact('salarySlips', 'stats'));
    }

    /**
     * Export salary slips as CSV (applies same filters as autoSalarySlip).
     */
    public function exportAutoSalarySlip(Request $request)
    {
        $query = SalarySlip::query();

        // Apply same filters as autoSalarySlip
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('slip_number', 'like', '%' . $request->search . '%')
                  ->orWhere('employee_id', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payroll_month')) {
            $query->where('payroll_month', $request->payroll_month);
        }

        if ($request->filled('payroll_year')) {
            $query->where('payroll_year', $request->payroll_year);
        }

        $salarySlips = $query->orderBy('generated_date', 'desc')->get();

        $filename = 'salary_slips_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($salarySlips) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Slip Number',
                'Employee ID',
                'Payroll Month',
                'Payroll Year',
                'Basic Salary',
                'Allowances (HRA + DA + Other)',
                'Deductions (PF + ESI + Tax + Other)',
                'Net Salary',
                'Status',
            ]);

            foreach ($salarySlips as $slip) {
                fputcsv($file, [
                    $slip->slip_number,
                    $slip->employee_id,
                    $slip->payroll_month,
                    $slip->payroll_year,
                    $slip->basic_salary,
                    $slip->hra + $slip->da + $slip->allowances,
                    $slip->pf + $slip->esi + $slip->tax + $slip->other_deductions,
                    $slip->net_salary,
                    $slip->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Store a new leave request
     */
    public function storeLeaveRequest(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'leave_type' => 'required|in:Sick Leave,Personal Leave,Annual Leave',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        // Calculate total days
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        $leaveRequest = LeaveRequest::create([
            'employee_id' => $user->employee_id ?? $user->id,
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        // When a leave is requested, mark the user as unavailable for follow-up
        // so that their leads can be managed/assigned by managers while they are away.
        try {
            $user->update([
                'is_available_for_followup' => false,
                'unavailability_reason' => 'On leave: ' . $validated['reason'],
                'unavailable_until' => $validated['end_date'],
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to update user availability on leave request', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Determine approver based on user role (subordinate → HR, others → Admin)
        $isSubordinate = !$user->hasRole('SUPER ADMIN') && !$user->hasRole('HR MANAGER');
        
        try {
            if ($isSubordinate) {
                // Send to HR for approval
                $hrManagers = User::whereHas('roles', function($q) {
                    $q->where('name', 'HR MANAGER');
                })->get();
                
                foreach ($hrManagers as $hr) {
                    Mail::to($hr->email)->send(new LeaveRequestNotification($leaveRequest, 'pending'));
                }
            } else {
                // Send to Admin for approval
                $admins = User::whereHas('roles', function($q) {
                    $q->where('name', 'SUPER ADMIN');
                })->get();
                
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new LeaveRequestNotification($leaveRequest, 'pending'));
                }
            }
            
            Log::info('Leave request email sent successfully', [
                'leave_request_id' => $leaveRequest->id,
                'employee_id' => $leaveRequest->employee_id,
                'employee_name' => $user->name ?? 'Unknown',
                'user_role' => $user->roles->first()?->name ?? 'No Role',
                'to' => 'thakrarharshil0@gmail.com',
                'leave_type' => $leaveRequest->leave_type,
                'start_date' => $leaveRequest->start_date->format('Y-m-d'),
                'end_date' => $leaveRequest->end_date->format('Y-m-d'),
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the request
            Log::error('Failed to send leave request email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'leave_request_id' => $leaveRequest->id,
                'employee_id' => $leaveRequest->employee_id,
                'employee_name' => $user->name ?? 'Unknown',
                'user_role' => $user->roles->first()?->name ?? 'No Role',
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'mail_config' => [
                    'mailer' => config('mail.default'),
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Leave request submitted successfully. It will be reviewed by HR.');
    }

    /**
     * Approve a leave request (Only HR and Admin)
     * Supports both GET (from email) and POST requests
     */
    public function approveLeaveRequest($id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $leaveRequest = LeaveRequest::findOrFail($id);
        $requester = User::where('employee_id', $leaveRequest->employee_id)->first() 
            ?? User::find($leaveRequest->employee_id);
        
        // Check if requester is subordinate
        $isSubordinate = $requester && !$requester->hasRole('SUPER ADMIN') && !$requester->hasRole('HR MANAGER');
        
        if ($isSubordinate) {
            // Only HR can approve subordinate requests
            if (!$user->hasRole('HR MANAGER') && !$user->hasRole('SUPER ADMIN')) {
            if (request()->get('from') === 'email') {
                return view('hr.email-action-result', [
                    'success' => false,
                        'message' => 'Only HR Manager or Admin can approve leave requests from subordinates.',
                        'leaveRequest' => $leaveRequest
                ]);
            }
            $redirectUrl = request()->headers->get('referer') 
                ?: route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', $user->roles->first()->name ?? 'default'))]);
                return redirect($redirectUrl)->with('error', 'Only HR Manager or Admin can approve leave requests from subordinates.');
            }
        } else {
            // Only Admin can approve non-subordinate requests
            if (!$user->hasRole('SUPER ADMIN')) {
                if (request()->get('from') === 'email') {
                    return view('hr.email-action-result', [
                        'success' => false,
                        'message' => 'Only Admin can approve this leave request.',
                        'leaveRequest' => $leaveRequest
                    ]);
                }
                $redirectUrl = request()->headers->get('referer') 
                    ?: route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', $user->roles->first()->name ?? 'default'))]);
                return redirect($redirectUrl)->with('error', 'Only Admin can approve this leave request.');
            }
        }

        $leaveRequest = LeaveRequest::findOrFail($id);
        
        // Check if already processed
        if ($leaveRequest->status !== 'pending') {
            // If coming from email, show info page
            if (request()->get('from') === 'email') {
                return view('hr.email-action-result', [
                    'success' => false,
                    'message' => 'This leave request has already been processed.',
                    'leaveRequest' => $leaveRequest->fresh() // Refresh to get latest status
                ]);
            }
            return redirect()->route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', $user->roles->first()->name ?? 'hr-manager'))])
                ->with('info', 'This leave request has already been processed.');
        }
        
        // Update status to approved immediately
        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => $user->name,
            'approved_date' => now(),
        ]);
        
        // Refresh the model to ensure we have the latest data
        $leaveRequest->refresh();

        // Send email notification
        try {
            Mail::to('thakrarharshil0@gmail.com')->send(new LeaveRequestNotification($leaveRequest, 'approved'));
            Log::info('Leave approval email sent successfully', [
                'leave_request_id' => $leaveRequest->id,
                'to' => 'thakrarharshil0@gmail.com'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send leave approval email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'leave_request_id' => $leaveRequest->id,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }

        // If coming from email, show success page instead of redirecting
        if (request()->get('from') === 'email') {
            // Refresh to get the latest status
            $leaveRequest->refresh();
            return view('hr.email-action-result', [
                'success' => true,
                'message' => 'Leave request approved successfully!',
                'leaveRequest' => $leaveRequest
            ]);
        }

        return redirect()->route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', $user->roles->first()->name ?? 'hr-manager'))])
            ->with('success', 'Leave request approved successfully.');
    }

    /**
     * Show reject form (for email links)
     */
    public function showRejectForm($id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('HR MANAGER')) {
            return redirect()->route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', $user->roles->first()->name ?? 'default'))])
                ->with('error', 'You do not have permission to reject leave requests.');
        }

        $leaveRequest = LeaveRequest::findOrFail($id);
        
        return view('hr.reject-leave', compact('leaveRequest'));
    }

    /**
     * Reject a leave request (Only HR and Admin based on requester)
     */
    public function rejectLeaveRequest(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $leaveRequest = LeaveRequest::findOrFail($id);
        $requester = User::where('employee_id', $leaveRequest->employee_id)->first() 
            ?? User::find($leaveRequest->employee_id);
        
        // Check if requester is subordinate
        $isSubordinate = $requester && !$requester->hasRole('SUPER ADMIN') && !$requester->hasRole('HR MANAGER');
        
        if ($isSubordinate) {
            // Only HR can reject subordinate requests
            if (!$user->hasRole('HR MANAGER') && !$user->hasRole('SUPER ADMIN')) {
                return redirect()->back()->with('error', 'Only HR Manager or Admin can reject leave requests from subordinates.');
            }
        } else {
            // Only Admin can reject non-subordinate requests
            if (!$user->hasRole('SUPER ADMIN')) {
                return redirect()->back()->with('error', 'Only Admin can reject this leave request.');
            }
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $leaveRequest = LeaveRequest::findOrFail($id);
        
        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => $user->name,
            'approved_date' => now(),
            'comments' => $validated['rejection_reason'],
        ]);

        // Send email notification
        try {
            Mail::to('thakrarharshil0@gmail.com')->send(new LeaveRequestNotification($leaveRequest, 'rejected', $validated['rejection_reason']));
            Log::info('Leave rejection email sent successfully', [
                'leave_request_id' => $leaveRequest->id,
                'to' => 'thakrarharshil0@gmail.com'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send leave rejection email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'leave_request_id' => $leaveRequest->id,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }

        // If coming from email, show success page instead of redirecting
        if ($request->get('from') === 'email' || $request->input('from') === 'email') {
            return view('hr.email-action-result', [
                'success' => true,
                'message' => 'Leave request rejected successfully!',
                'leaveRequest' => $leaveRequest
            ]);
        }

        return redirect()->back()->with('success', 'Leave request rejected.');
    }

    /**
     * Delete a leave request - Requires Admin Approval
     */
    public function deleteLeaveRequest(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $leaveRequest = LeaveRequest::findOrFail($id);
        
        // If user is SUPER ADMIN, delete directly without approval
        if ($user->hasRole('SUPER ADMIN')) {
            $leaveRequest->delete();
            return redirect()->back()->with('success', 'Leave request deleted successfully.');
        }

        // For all other roles, create delete approval request
        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        // Check if already has pending approval
        $existingApproval = \App\Models\DeleteApproval::where('model_type', LeaveRequest::class)
            ->where('model_id', $leaveRequest->id)
            ->where('status', 'pending')
            ->first();

        if ($existingApproval) {
            return redirect()->back()->with('info', 'A deletion request for this leave request is already pending approval.');
        }

        // Store model data before deletion
        $modelData = [
            'employee_id' => $leaveRequest->employee_id,
            'leave_type' => $leaveRequest->leave_type,
            'start_date' => $leaveRequest->start_date->format('Y-m-d'),
            'end_date' => $leaveRequest->end_date->format('Y-m-d'),
            'total_days' => $leaveRequest->total_days,
            'reason' => $leaveRequest->reason,
            'status' => $leaveRequest->status,
        ];

        $deleteApproval = \App\Models\DeleteApproval::create([
            'model_type' => LeaveRequest::class,
            'model_id' => $leaveRequest->id,
            'requested_by' => $user->id,
            'model_name' => 'Leave Request #' . $leaveRequest->id . ' (' . $leaveRequest->leave_type . ' - ' . $leaveRequest->start_date->format('d M, Y') . ')',
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
            'model_data' => $modelData,
        ]);

        // Send email to admin
        try {
            Mail::to('thakrarharshil0@gmail.com')
                ->send(new \App\Mail\DeleteApprovalNotification($deleteApproval));
            
            Log::info('Delete approval email sent successfully', [
                'delete_approval_id' => $deleteApproval->id,
                'model_type' => LeaveRequest::class,
                'model_id' => $leaveRequest->id,
                'requested_by' => $user->name,
                'to' => 'thakrarharshil0@gmail.com',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send delete approval email', [
                'error' => $e->getMessage(),
                'delete_approval_id' => $deleteApproval->id,
            ]);
        }

        return redirect()->back()->with('success', 'Deletion request submitted. It will be reviewed by admin.');
    }

    /**
     * Show edit form for leave request (Only HR and Admin)
     */
    public function editLeaveRequest($id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('HR MANAGER')) {
            return redirect()->back()->with('error', 'You do not have permission to edit leave requests.');
        }

        $leaveRequest = LeaveRequest::findOrFail($id);
        
        return view('hr.edit-leave', compact('leaveRequest'));
    }

    /**
     * Update a leave request (Only HR and Admin)
     */
    public function updateLeaveRequest(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('HR MANAGER')) {
            return redirect()->back()->with('error', 'You do not have permission to update leave requests.');
        }

        $leaveRequest = LeaveRequest::findOrFail($id);
        
        $rules = [
            'status' => 'required|in:pending,approved,rejected',
        ];

        // If status is rejected, rejection_reason is required
        if ($request->status === 'rejected') {
            $rules['rejection_reason'] = 'required|string|max:500';
        }

        $validated = $request->validate($rules);

        // Only update status and comments (rejection reason)
        $updateData = [
            'status' => $validated['status'],
        ];

        // If status is rejected, require and save rejection reason
        if ($validated['status'] === 'rejected') {
            if (empty($validated['rejection_reason'])) {
                return redirect()->back()->withErrors(['rejection_reason' => 'Rejection reason is required when status is rejected.'])->withInput();
            }
            $updateData['comments'] = $validated['rejection_reason'];
        } else {
            // Clear comments if status is not rejected
            $updateData['comments'] = null;
        }

        // If status changed to approved/rejected, update approval info
        if ($validated['status'] !== 'pending') {
            $updateData['approved_by'] = $user->name;
            $updateData['approved_date'] = now();
        } else {
            // If changing back to pending, clear approval info
            $updateData['approved_by'] = null;
            $updateData['approved_date'] = null;
        }

        $leaveRequest->update($updateData);

        return redirect()->route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', $user->roles->first()->name ?? 'hr-manager'))])
            ->with('success', 'Leave request updated successfully.');
    }

    /**
     * Test email sending
     */
    public function testEmail()
    {
        try {
            $testLeaveRequest = LeaveRequest::first();
            
            if (!$testLeaveRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'No leave request found to test with. Please create a leave request first.'
                ]);
            }

            Mail::to('thakrarharshil0@gmail.com')->send(new LeaveRequestNotification($testLeaveRequest, 'pending'));
            
            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to thakrarharshil0@gmail.com',
                'mail_config' => [
                    'mailer' => config('mail.default'),
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                    'from_address' => config('mail.from.address'),
                    'from_name' => config('mail.from.name'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'mail_config' => [
                    'mailer' => config('mail.default'),
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                    'from_address' => config('mail.from.address'),
                    'from_name' => config('mail.from.name'),
                ]
            ], 500);
        }
    }
}
