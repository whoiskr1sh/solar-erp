<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Complaint;
use App\Models\AMC;
use App\Models\OMMaintenance;

class OMController extends Controller
{
    /**
     * Display Complaint Management page
     */
    public function complaintManagement(Request $request): View
    {
        $query = Complaint::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('complaint_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('project_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $complaints = $query->orderBy('reported_date', 'desc')->paginate(20);

        $stats = [
            'total_complaints' => Complaint::count(),
            'open_complaints' => Complaint::where('status', 'open')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
        ];

        return view('om.complaint-management', compact('complaints', 'stats'));
    }

    /**
     * Export complaints as CSV (applies same filters as complaintManagement).
     */
    public function exportComplaintManagement(Request $request)
    {
        $query = Complaint::query();

        // Apply same filters as complaintManagement
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('complaint_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('project_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $complaints = $query->orderBy('reported_date', 'desc')->get();

        $filename = 'complaints_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($complaints) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Complaint Number',
                'Customer Name',
                'Project Name',
                'Reported Date',
                'Status',
                'Priority',
            ]);

            foreach ($complaints as $complaint) {
                fputcsv($file, [
                    $complaint->complaint_number,
                    $complaint->customer_name,
                    $complaint->project_name,
                    $complaint->reported_date ? $complaint->reported_date->format('Y-m-d') : null,
                    $complaint->status,
                    $complaint->priority,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display AMC page
     */
    public function amc(Request $request): View
    {
        $query = AMC::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('amc_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('project_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $amcs = $query->orderBy('start_date', 'desc')->paginate(20);

        $stats = [
            'total_amcs' => AMC::count(),
            'active_amcs' => AMC::where('status', 'active')->count(),
            'expired_amcs' => AMC::where('status', 'expired')->count(),
            'total_contract_value' => AMC::sum('contract_value'),
        ];

        return view('om.amc', compact('amcs', 'stats'));
    }

    /**
     * Export AMCs as CSV (applies same filters as amc).
     */
    public function exportAmc(Request $request)
    {
        $query = AMC::query();

        // Apply same filters as amc
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('amc_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('project_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $amcs = $query->orderBy('start_date', 'desc')->get();

        $filename = 'amcs_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($amcs) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'AMC Number',
                'Customer Name',
                'Project Name',
                'Start Date',
                'End Date',
                'Status',
                'Contract Value',
            ]);

            foreach ($amcs as $amc) {
                fputcsv($file, [
                    $amc->amc_number,
                    $amc->customer_name,
                    $amc->project_name,
                    $amc->start_date ? $amc->start_date->format('Y-m-d') : null,
                    $amc->end_date ? $amc->end_date->format('Y-m-d') : null,
                    $amc->status,
                    $amc->contract_value,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display O&M Project Management page
     */
    public function omProjectManagement(Request $request): View
    {
        $query = OMMaintenance::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('maintenance_id', 'like', '%' . $request->search . '%')
                  ->orWhere('project_name', 'like', '%' . $request->search . '%')
                  ->orWhere('technician_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('maintenance_type')) {
            $query->where('maintenance_type', $request->maintenance_type);
        }

        $maintenances = $query->orderBy('scheduled_date', 'desc')->paginate(20);

        $stats = [
            'total_maintenances' => OMMaintenance::count(),
            'scheduled' => OMMaintenance::where('status', 'scheduled')->count(),
            'in_progress' => OMMaintenance::where('status', 'in_progress')->count(),
            'completed' => OMMaintenance::where('status', 'completed')->count(),
        ];

        return view('om.om-project-management', compact('maintenances', 'stats'));
    }

    /**
     * Export O&M maintenances as CSV (applies same filters as omProjectManagement).
     */
    public function exportOmProjectManagement(Request $request)
    {
        $query = OMMaintenance::query();

        // Apply same filters as omProjectManagement
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('maintenance_id', 'like', '%' . $request->search . '%')
                  ->orWhere('project_name', 'like', '%' . $request->search . '%')
                  ->orWhere('technician_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('maintenance_type')) {
            $query->where('maintenance_type', $request->maintenance_type);
        }

        $maintenances = $query->orderBy('scheduled_date', 'desc')->get();

        $filename = 'om_maintenances_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($maintenances) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Maintenance ID',
                'Project Name',
                'Technician Name',
                'Scheduled Date',
                'Status',
                'Maintenance Type',
            ]);

            foreach ($maintenances as $maintenance) {
                fputcsv($file, [
                    $maintenance->maintenance_id,
                    $maintenance->project_name,
                    $maintenance->technician_name,
                    $maintenance->scheduled_date ? $maintenance->scheduled_date->format('Y-m-d') : null,
                    $maintenance->status,
                    $maintenance->maintenance_type,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}