<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Approval Required</title>
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
            background-color: #dc2626;
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
            border-left: 4px solid #dc2626;
            border-radius: 4px;
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
        .warning-box {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>⚠️ Delete Approval Required</h1>
    </div>
    
    <div class="content">
        <p>Dear Admin,</p>
        
        <div class="warning-box">
            <strong>⚠️ A deletion request has been submitted and requires your approval.</strong>
        </div>
        
        <div class="info-box">
            <h3 style="margin-top: 0;">Deletion Request Details</h3>
            <table>
                <tr>
                    <td>Item to Delete:</td>
                    <td>{{ $deleteApproval->model_name }}</td>
                </tr>
                <tr>
                    <td>Type:</td>
                    <td>{{ class_basename($deleteApproval->model_type) }}</td>
                </tr>
                <tr>
                    <td>Requested By:</td>
                    <td>{{ $deleteApproval->requester->name ?? 'Unknown' }} ({{ $deleteApproval->requester->email ?? 'N/A' }})</td>
                </tr>
                <tr>
                    <td>User Role:</td>
                    <td>{{ $deleteApproval->requester->roles->first()?->name ?? 'No Role' }}</td>
                </tr>
                <tr>
                    <td>Requested At:</td>
                    <td>{{ $deleteApproval->created_at->format('d M, Y h:i A') }}</td>
                </tr>
                @if($deleteApproval->reason)
                <tr>
                    <td>Reason:</td>
                    <td>{{ $deleteApproval->reason }}</td>
                </tr>
                @endif
            </table>
        </div>
        
        <div class="action-buttons">
            <p style="margin-bottom: 15px; font-weight: bold;">Quick Actions:</p>
            <a href="{{ url('/admin/delete-approval/' . $deleteApproval->id . '/approve?from=email') }}" class="btn btn-approve" style="background-color: #10b981; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 0 10px; display: inline-block;">
                ✓ Approve Deletion
            </a>
            <a href="{{ url('/admin/delete-approval/' . $deleteApproval->id . '/reject?from=email') }}" class="btn btn-reject" style="background-color: #ef4444; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 0 10px; display: inline-block;">
                ✗ Reject Deletion
            </a>
        </div>
        
        <p><strong>Warning:</strong> Once approved, this action cannot be undone. Please review carefully before approving.</p>
        
        <p>Thank you,<br>Solar ERP System</p>
    </div>
    
    <div class="footer">
        <p>This is an automated email from Solar ERP System. Please do not reply to this email.</p>
    </div>
</body>
</html>


