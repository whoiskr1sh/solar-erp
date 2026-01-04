<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $user->settings ?? [];
        
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'language' => 'nullable|string|in:en,hi,es,fr',
            'timezone' => 'nullable|string',
            'date_format' => 'nullable|string|in:Y-m-d,d-m-Y,m/d/Y,d/m/Y',
            'time_format' => 'nullable|string|in:12,24',
            'theme' => 'nullable|string|in:light,dark',
            'items_per_page' => 'nullable|integer|min:5|max:100',
            'email_notifications' => 'nullable|boolean',
            'sms_notifications' => 'nullable|boolean',
            'push_notifications' => 'nullable|boolean',
            'task_reminders' => 'nullable|boolean',
            'project_updates' => 'nullable|boolean',
            'invoice_notifications' => 'nullable|boolean',
            'commission_notifications' => 'nullable|boolean',
            'data_sharing' => 'nullable|boolean',
            'analytics_tracking' => 'nullable|boolean',
            'marketing_emails' => 'nullable|boolean',
            'two_factor_enabled' => 'nullable|boolean',
            'session_timeout' => 'nullable|integer|min:5|max:480',
            'login_notifications' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        
        $settings = [
            'general' => [
                'language' => $request->language ?? 'en',
                'timezone' => $request->timezone ?? 'UTC',
                'date_format' => $request->date_format ?? 'Y-m-d',
                'time_format' => $request->time_format ?? '24',
                'theme' => $request->theme ?? 'light',
                'items_per_page' => $request->items_per_page ?? 15,
            ],
            'notifications' => [
                'email_notifications' => $request->boolean('email_notifications', true),
                'sms_notifications' => $request->boolean('sms_notifications', false),
                'push_notifications' => $request->boolean('push_notifications', true),
                'task_reminders' => $request->boolean('task_reminders', true),
                'project_updates' => $request->boolean('project_updates', true),
                'invoice_notifications' => $request->boolean('invoice_notifications', true),
                'commission_notifications' => $request->boolean('commission_notifications', true),
            ],
            'privacy' => [
                'data_sharing' => $request->boolean('data_sharing', false),
                'analytics_tracking' => $request->boolean('analytics_tracking', true),
                'marketing_emails' => $request->boolean('marketing_emails', false),
            ],
            'security' => [
                'two_factor_enabled' => $request->boolean('two_factor_enabled', false),
                'session_timeout' => $request->session_timeout ?? 30,
                'login_notifications' => $request->boolean('login_notifications', true),
            ]
        ];

        $user->update(['settings' => $settings]);

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully!');
    }

    public function resetDefaults()
    {
        $user = Auth::user();
        
        $defaultSettings = [
            'general' => [
                'language' => 'en',
                'timezone' => 'UTC',
                'date_format' => 'Y-m-d',
                'time_format' => '24',
                'theme' => 'light',
                'items_per_page' => 15,
            ],
            'notifications' => [
                'email_notifications' => true,
                'sms_notifications' => false,
                'push_notifications' => true,
                'task_reminders' => true,
                'project_updates' => true,
                'invoice_notifications' => true,
                'commission_notifications' => true,
            ],
            'privacy' => [
                'data_sharing' => false,
                'analytics_tracking' => true,
                'marketing_emails' => false,
            ],
            'security' => [
                'two_factor_enabled' => false,
                'session_timeout' => 30,
                'login_notifications' => true,
            ]
        ];

        $user->update(['settings' => $defaultSettings]);

        return redirect()->route('settings.index')->with('success', 'Settings reset to defaults successfully!');
    }
}