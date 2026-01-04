<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Get notifications from database
        $notifications = Notification::forUser($userId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // If no notifications exist, create some sample ones
        if ($notifications->isEmpty()) {
            $sampleNotifications = [
                [
                    'user_id' => $userId,
                    'title' => 'New Lead Assigned',
                    'message' => 'You have been assigned a new lead: Solar Installation Project',
                    'type' => 'lead',
                    'read' => false,
                ],
                [
                    'user_id' => $userId,
                    'title' => 'Task Due Today',
                    'message' => 'Site survey task is due today',
                    'type' => 'task',
                    'read' => false,
                ],
                [
                    'user_id' => $userId,
                    'title' => 'Invoice Overdue',
                    'message' => 'Invoice #INV-001 is overdue',
                    'type' => 'invoice',
                    'read' => true,
                ],
                [
                    'user_id' => $userId,
                    'title' => 'Quotation Accepted',
                    'message' => 'Quotation #QT-001 has been accepted by client',
                    'type' => 'quotation',
                    'read' => true,
                ],
                [
                    'user_id' => $userId,
                    'title' => 'Project Update',
                    'message' => 'Project "Solar Farm Installation" has been updated',
                    'type' => 'project',
                    'read' => true,
                ],
                [
                    'user_id' => $userId,
                    'title' => 'Payment Received',
                    'message' => 'Payment of ₹50,000 received for Invoice #INV-002',
                    'type' => 'payment',
                    'read' => true,
                ]
            ];
            
            // Create sample notifications
            foreach ($sampleNotifications as $notificationData) {
                Notification::create($notificationData);
            }
            
            // Refresh notifications
            $notifications = Notification::forUser($userId)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Calculate stats
        $stats = [
            'total' => $notifications->count(),
            'unread' => $notifications->where('read', false)->count(),
            'tasks' => $notifications->where('type', 'task')->count(),
            'leads' => $notifications->where('type', 'lead')->count(),
            'invoices' => $notifications->where('type', 'invoice')->count(),
            'quotations' => $notifications->where('type', 'quotation')->count(),
        ];

        return view('notifications.index', compact('notifications', 'stats'));
    }

    public function markAsRead(Request $request, $id)
    {
        $userId = Auth::id();
        
        $notification = Notification::forUser($userId)->find($id);
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ]);
        }
        
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $userId = Auth::id();
        
        $updated = Notification::forUser($userId)
            ->unread()
            ->update([
                'read' => true,
                'read_at' => now()
            ]);
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
            'updated_count' => $updated
        ]);
    }

    public function getUnreadCount()
    {
        $userId = Auth::id();
        
        $unreadCount = Notification::forUser($userId)->unread()->count();
        
        return response()->json([
            'count' => $unreadCount
        ]);
    }

    // Method to reset notifications (for testing purposes)
    public function resetNotifications()
    {
        $userId = Auth::id();
        
        // Mark all notifications as unread instead of deleting
        $updated = Notification::forUser($userId)
            ->update([
                'read' => false,
                'read_at' => null
            ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Notifications reset successfully',
            'updated_count' => $updated
        ]);
    }
}
