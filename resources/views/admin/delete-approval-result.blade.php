<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $success ? 'Success' : 'Error' }} - Delete Approval</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
            padding: 40px;
            text-align: center;
        }
        .icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }
        .success .icon {
            background: #d1fae5;
            color: #10b981;
        }
        .error .icon {
            background: #fee2e2;
            color: #ef4444;
        }
        h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #1f2937;
        }
        .success h1 {
            color: #10b981;
        }
        .error h1 {
            color: #ef4444;
        }
        p {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .details {
            background: #f9fafb;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        .details h3 {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .details-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .details-item:last-child {
            border-bottom: none;
        }
        .details-item strong {
            color: #374151;
        }
        .details-item span {
            color: #6b7280;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 20px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #2563eb;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }
        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }
        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="container {{ $success ? 'success' : 'error' }}">
        <div class="icon">
            @if($success)
                ✓
            @else
                ✗
            @endif
        </div>
        <h1>{{ $success ? 'Action Completed!' : 'Action Failed' }}</h1>
        <p>{{ $message }}</p>
        
        @if(isset($deleteApproval))
        <div class="details">
            <h3>Deletion Request Details</h3>
            <div class="details-item">
                <strong>Item:</strong>
                <span>{{ $deleteApproval->model_name }}</span>
            </div>
            <div class="details-item">
                <strong>Type:</strong>
                <span>{{ class_basename($deleteApproval->model_type) }}</span>
            </div>
            <div class="details-item">
                <strong>Requested By:</strong>
                <span>{{ $deleteApproval->requester->name ?? 'Unknown' }}</span>
            </div>
            <div class="details-item">
                <strong>Status:</strong>
                <span class="status-badge {{ $deleteApproval->status === 'approved' ? 'status-approved' : ($deleteApproval->status === 'rejected' ? 'status-rejected' : '') }}">
                    {{ ucfirst($deleteApproval->status) }}
                </span>
            </div>
            @if($deleteApproval->approved_at)
            <div class="details-item">
                <strong>Processed At:</strong>
                <span>{{ $deleteApproval->approved_at->format('d M, Y h:i A') }}</span>
            </div>
            @endif
        </div>
        @endif
        
        <a href="{{ route('dashboard') }}" class="btn">
            Go to Dashboard
        </a>
    </div>
</body>
</html>


