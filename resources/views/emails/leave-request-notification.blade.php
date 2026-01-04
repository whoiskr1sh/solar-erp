<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0d9488;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .info-box {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #0d9488;
            border-radius: 4px;
        }
        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .action-buttons {
            text-align: center;
            margin: 25px 0;
            padding: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s;
        }
        .btn-approve {
            background-color: #10b981;
            color: white;
        }
        .btn-approve:hover {
            background-color: #059669;
        }
        .btn-reject {
            background-color: #ef4444;
            color: white;
        }
        .btn-reject:hover {
            background-color: #dc2626;
        }
        .btn-view {
            background-color: #3b82f6;
            color: white;
        }
        .btn-view:hover {
            background-color: #2563eb;
        }
        .footer {
            background-color: #f3f4f6;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-radius: 0 0 5px 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        table td:first-child {
            font-weight: bold;
            width: 40%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Leave Request Notification</h1>
    </div>
    
    <div class="content">
        @if($status == 'pending')
            <p>Dear HR Team,</p>
            <p>A new leave request has been submitted and requires your review.</p>
        @elseif($status == 'approved')
            <p>Dear HR Team,</p>
            <p>A leave request has been approved.</p>
        @else
            <p>Dear HR Team,</p>
            <p>A leave request has been rejected.</p>
        @endif
        
        @if($status == 'approved')
            <div class="status-approved">
                ✓ Leave Request Approved
            </div>
        @elseif($status == 'rejected')
            <div class="status-rejected">
                ✗ Leave Request Rejected
            </div>
        @else
            <div class="status-pending">
                ⏳ New Leave Request Submitted - Action Required
            </div>
        @endif
        
        <div class="info-box">
            <h3 style="margin-top: 0;">Leave Request Details</h3>
            <table>
                <tr>
                    <td>Employee ID:</td>
                    <td>{{ $leaveRequest->employee_id }}</td>
                </tr>
                <tr>
                    <td>Leave Type:</td>
                    <td>{{ $leaveRequest->leave_type }}</td>
                </tr>
                <tr>
                    <td>Start Date:</td>
                    <td>{{ $leaveRequest->start_date->format('d M, Y') }}</td>
                </tr>
                <tr>
                    <td>End Date:</td>
                    <td>{{ $leaveRequest->end_date->format('d M, Y') }}</td>
                </tr>
                <tr>
                    <td>Total Days:</td>
                    <td>{{ $leaveRequest->total_days }} day(s)</td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td><strong>{{ ucfirst($leaveRequest->status) }}</strong></td>
                </tr>
                <tr>
                    <td>Reason:</td>
                    <td>{{ $leaveRequest->reason }}</td>
                </tr>
                @if($leaveRequest->approved_by)
                <tr>
                    <td>Approved By:</td>
                    <td>{{ $leaveRequest->approved_by }}</td>
                </tr>
                @endif
                @if($leaveRequest->approved_date)
                <tr>
                    <td>Action Date:</td>
                    <td>{{ $leaveRequest->approved_date->format('d M, Y h:i A') }}</td>
                </tr>
                @endif
                @if($comments)
                <tr>
                    <td>Comments:</td>
                    <td>{{ $comments }}</td>
                </tr>
                @endif
            </table>
        </div>
        
        @if($status == 'pending')
            <div class="action-buttons">
                <p style="margin-bottom: 15px; font-weight: bold;">Quick Actions:</p>
                <a href="{{ url('/hr/leave-request/' . $leaveRequest->id . '/approve?from=email') }}" class="btn btn-approve" style="background-color: #10b981; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 0 10px; display: inline-block;">
                    ✓ Approve Leave
                </a>
                <a href="{{ url('/hr/leave-request/' . $leaveRequest->id . '/reject?from=email') }}" class="btn btn-reject" style="background-color: #ef4444; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 0 10px; display: inline-block;">
                    ✗ Reject Leave
                </a>
                <br><br>
                <a href="{{ url('/hr/' . strtolower(str_replace(' ', '-', 'HR MANAGER')) . '/leave-management') }}" class="btn btn-view" style="background-color: #3b82f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;">
                    View in Dashboard
                </a>
            </div>
            <p style="margin-top: 20px;">Please review and take appropriate action on this leave request.</p>
        @elseif($status == 'approved')
            <p>The leave request has been approved. Please update the employee's leave balance accordingly.</p>
            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ url('/hr/' . strtolower(str_replace(' ', '-', 'HR MANAGER')) . '/leave-management') }}" class="btn btn-view" style="background-color: #3b82f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                    View in Dashboard
                </a>
            </div>
        @elseif($status == 'rejected')
            <p>The leave request has been rejected. Please inform the employee about the decision.</p>
            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ url('/hr/' . strtolower(str_replace(' ', '-', 'HR MANAGER')) . '/leave-management') }}" class="btn btn-view" style="background-color: #3b82f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                    View in Dashboard
                </a>
            </div>
        @endif
        
        <p>Thank you,<br>Solar ERP System</p>
    </div>
    
    <div class="footer">
        <p>This is an automated email from Solar ERP System. Please do not reply to this email.</p>
    </div>
</body>
</html>