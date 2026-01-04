@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Settings</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your application preferences and account settings</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <!-- Settings Navigation -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button onclick="showTab('general')" id="general-tab" class="border-teal-500 dark:border-teal-400 text-teal-600 dark:text-teal-400 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    General
                </button>
                <button onclick="showTab('notifications')" id="notifications-tab" class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Notifications
                </button>
                <button onclick="showTab('privacy')" id="privacy-tab" class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Privacy
                </button>
                <button onclick="showTab('security')" id="security-tab" class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Security
                </button>
            </nav>
        </div>

        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PUT')
            
            <div class="p-6">
                <!-- General Settings -->
                <div id="general-content" class="tab-content">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">General Preferences</h3>
                            
                            <div class="space-y-4">
                                <!-- Language -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white dark:text-white">Language</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-400">Choose your preferred language</p>
                                    </div>
                                    <select name="language" class="px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                                        <option value="en" {{ ($settings['general']['language'] ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="hi" {{ ($settings['general']['language'] ?? 'en') == 'hi' ? 'selected' : '' }}>Hindi</option>
                                        <option value="es" {{ ($settings['general']['language'] ?? 'en') == 'es' ? 'selected' : '' }}>Spanish</option>
                                        <option value="fr" {{ ($settings['general']['language'] ?? 'en') == 'fr' ? 'selected' : '' }}>French</option>
                                    </select>
                                </div>

                                <!-- Timezone -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white dark:text-white">Timezone</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-400">Set your timezone</p>
                                    </div>
                                    <select name="timezone" class="px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                                        <option value="UTC" {{ ($settings['general']['timezone'] ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                        <option value="Asia/Kolkata" {{ ($settings['general']['timezone'] ?? 'UTC') == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata</option>
                                        <option value="America/New_York" {{ ($settings['general']['timezone'] ?? 'UTC') == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                        <option value="Europe/London" {{ ($settings['general']['timezone'] ?? 'UTC') == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                    </select>
                                </div>

                                <!-- Date Format -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white">Date Format</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Choose your preferred date format</p>
                                    </div>
                                    <select name="date_format" class="px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                                        <option value="Y-m-d" {{ ($settings['general']['date_format'] ?? 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                        <option value="d-m-Y" {{ ($settings['general']['date_format'] ?? 'Y-m-d') == 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY</option>
                                        <option value="m/d/Y" {{ ($settings['general']['date_format'] ?? 'Y-m-d') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                        <option value="d/m/Y" {{ ($settings['general']['date_format'] ?? 'Y-m-d') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                    </select>
                                </div>

                                <!-- Time Format -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white">Time Format</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Choose 12-hour or 24-hour format</p>
                                    </div>
                                    <select name="time_format" class="px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                                        <option value="24" {{ ($settings['general']['time_format'] ?? '24') == '24' ? 'selected' : '' }}>24 Hour</option>
                                        <option value="12" {{ ($settings['general']['time_format'] ?? '24') == '12' ? 'selected' : '' }}>12 Hour</option>
                                    </select>
                                </div>

                                <!-- Theme -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white dark:text-white">Theme</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-400">Choose your preferred theme</p>
                                    </div>
                                    <select name="theme" id="theme-select" onchange="applyTheme(this.value)" class="px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                                        <option value="light" {{ ($settings['general']['theme'] ?? 'light') == 'light' ? 'selected' : '' }}>Light</option>
                                        <option value="dark" {{ ($settings['general']['theme'] ?? 'light') == 'dark' ? 'selected' : '' }}>Dark</option>
                                    </select>
                                </div>

                                <!-- Items per page -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white">Items per page</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Number of items to display per page</p>
                                    </div>
                                    <select name="items_per_page" class="px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                                        <option value="10" {{ ($settings['general']['items_per_page'] ?? 15) == 10 ? 'selected' : '' }}>10</option>
                                        <option value="15" {{ ($settings['general']['items_per_page'] ?? 15) == 15 ? 'selected' : '' }}>15</option>
                                        <option value="25" {{ ($settings['general']['items_per_page'] ?? 15) == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ ($settings['general']['items_per_page'] ?? 15) == 50 ? 'selected' : '' }}>50</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications Settings -->
                <div id="notifications-content" class="tab-content hidden">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Notification Preferences</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white">Email Notifications</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Receive notifications via email</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="email_notifications" value="1" class="sr-only peer" {{ ($settings['notifications']['email_notifications'] ?? true) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white">SMS Notifications</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Receive notifications via SMS</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="sms_notifications" value="1" class="sr-only peer" {{ ($settings['notifications']['sms_notifications'] ?? false) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white">Push Notifications</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Receive push notifications</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="push_notifications" value="1" class="sr-only peer" {{ ($settings['notifications']['push_notifications'] ?? true) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white">Task Reminders</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Get reminded about upcoming tasks</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="task_reminders" value="1" class="sr-only peer" {{ ($settings['notifications']['task_reminders'] ?? true) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white">Project Updates</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Get notified about project status changes</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="project_updates" value="1" class="sr-only peer" {{ ($settings['notifications']['project_updates'] ?? true) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white">Invoice Notifications</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Get notified about invoice updates</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="invoice_notifications" value="1" class="sr-only peer" {{ ($settings['notifications']['invoice_notifications'] ?? true) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white">Commission Notifications</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Get notified about commission updates</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="commission_notifications" value="1" class="sr-only peer" {{ ($settings['notifications']['commission_notifications'] ?? true) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Privacy Settings -->
                <div id="privacy-content" class="tab-content hidden">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Privacy Preferences</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white">Data Sharing</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Allow sharing of anonymized data for analytics</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="data_sharing" value="1" class="sr-only peer" {{ ($settings['privacy']['data_sharing'] ?? false) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white">Analytics Tracking</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Allow analytics tracking for improvement</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="analytics_tracking" value="1" class="sr-only peer" {{ ($settings['privacy']['analytics_tracking'] ?? true) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white">Marketing Emails</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Receive marketing and promotional emails</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="marketing_emails" value="1" class="sr-only peer" {{ ($settings['privacy']['marketing_emails'] ?? false) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div id="security-content" class="tab-content hidden">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Security Preferences</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white">Two-Factor Authentication</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Enable two-factor authentication for extra security</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="two_factor_enabled" value="1" class="sr-only peer" {{ ($settings['security']['two_factor_enabled'] ?? false) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white">Session Timeout (minutes)</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Auto-logout after inactivity</p>
                                    </div>
                                    <select name="session_timeout" class="px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                                        <option value="15" {{ ($settings['security']['session_timeout'] ?? 30) == 15 ? 'selected' : '' }}>15 minutes</option>
                                        <option value="30" {{ ($settings['security']['session_timeout'] ?? 30) == 30 ? 'selected' : '' }}>30 minutes</option>
                                        <option value="60" {{ ($settings['security']['session_timeout'] ?? 30) == 60 ? 'selected' : '' }}>1 hour</option>
                                        <option value="120" {{ ($settings['security']['session_timeout'] ?? 30) == 120 ? 'selected' : '' }}>2 hours</option>
                                        <option value="480" {{ ($settings['security']['session_timeout'] ?? 30) == 480 ? 'selected' : '' }}>8 hours</option>
                                    </select>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white">Login Notifications</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Get notified about new login attempts</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="login_notifications" value="1" class="sr-only peer" {{ ($settings['security']['login_notifications'] ?? true) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="button" onclick="resetToDefaults()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Reset to Defaults
                    </button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('[id$="-tab"]').forEach(tab => {
        tab.classList.remove('border-teal-500', 'text-teal-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-teal-500', 'text-teal-600');
}

function resetToDefaults() {
    if (confirm('Are you sure you want to reset all settings to defaults? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("settings.reset") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

function applyTheme(theme) {
    const html = document.documentElement;
    if (theme === 'dark') {
        html.classList.add('dark');
    } else {
        html.classList.remove('dark');
    }
    // Save to localStorage as backup
    localStorage.setItem('theme', theme);
}

// Apply theme on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme');
    const currentTheme = '{{ ($settings['general']['theme'] ?? 'light') }}';
    const theme = savedTheme || currentTheme;
    applyTheme(theme);
    
    // Update select if it exists
    const themeSelect = document.getElementById('theme-select');
    if (themeSelect) {
        themeSelect.value = theme;
    }
});
</script>
@endsection