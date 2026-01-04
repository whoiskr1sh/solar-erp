@extends('layouts.app')

@section('title', 'Project Gantt Chart')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Project Gantt Chart</h1>
            <p class="mt-2 text-gray-600">Visual timeline and task tracking for {{ $project->name }}</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <button onclick="exportGantt()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Chart
            </button>
            <button onclick="refreshGantt()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
            <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Back to Project
            </a>
        </div>
    </div>

    <!-- Project Info -->
        <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Project Code</p>
                    <p class="text-lg font-bold text-gray-900">{{ $project->project_code }}</p>
            </div>
        </div>

            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Status</p>
                    <p class="text-lg font-bold text-gray-900">{{ ucfirst($project->status) }}</p>
            </div>
        </div>

            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Budget</p>
                    <p class="text-lg font-bold text-gray-900">₹{{ number_format($project->budget ?? 0) }}</p>
            </div>
        </div>

            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Tasks</p>
                    <p class="text-lg font-bold text-gray-900">{{ $tasks->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-4">
                <div>
                    <label for="view-mode" class="block text-sm font-medium text-gray-700 mb-2">View Mode</label>
                    <select id="view-mode" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="Day">Day View</option>
                        <option value="Week">Week View</option>
                        <option value="Month" selected>Month View</option>
                    </select>
                </div>
                <div>
                    <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-2">Status Filter</label>
                    <select id="status-filter" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="zoomIn()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm">
                    Zoom In
                </button>
                <button onclick="zoomOut()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm">
                    Zoom Out
                </button>
                <button onclick="fitToScreen()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm">
                    Fit to Screen
                </button>
            </div>
        </div>
    </div>

    <!-- Gantt Chart Container -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Project Timeline</h3>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Tasks: {{ $tasks->count() }}</span>
                <span class="text-sm text-gray-500">|</span>
                <span class="text-sm text-gray-500">Duration: {{ $project->start_date && $project->end_date ? $project->start_date->diffInDays($project->end_date) . ' days' : 'N/A' }}</span>
            </div>
        </div>
        
        <!-- Gantt Chart -->
        <div id="gantt-chart" class="w-full" style="height: 500px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); overflow-x: auto; overflow-y: hidden; min-width: 100%;">
            <!-- Chart will be rendered here -->
        </div>
    </div>

    <!-- Task Details -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Task Details</h3>
            <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Task
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tasks as $task)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($task->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $task->assignedTo->name ?? 'Unassigned' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $task->start_date ? $task->start_date->format('M d, Y') : 'Not set' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $task->due_date ? $task->due_date->format('M d, Y') : 'Not set' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="h-2 rounded-full {{ $task->status === 'completed' ? 'bg-green-500' : ($task->status === 'in_progress' ? 'bg-blue-500' : 'bg-yellow-500') }}" style="width: {{ $task->progress_percentage }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600">{{ $task->progress_percentage }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $task->status_badge }}">
                                {{ ucfirst($task->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('tasks.show', $task) }}" class="text-teal-600 hover:text-teal-900 mr-3">View</a>
                            <a href="{{ route('tasks.edit', $task) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No tasks found. <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="text-teal-600 hover:text-teal-900">Create your first task</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Frappe Gantt CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.css">

<!-- Frappe Gantt JS -->
<script src="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"></script>

<style>
/* Professional Gantt Chart Styling */
#gantt-chart {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: none;
    border-radius: 0;
    padding: 0;
    box-shadow: none;
    position: relative;
    width: 100%;
    min-width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
}

#gantt-chart svg {
    max-width: none;
    min-width: 100%;
    width: auto;
    height: auto;
    background: transparent;
}

.gantt-container {
    overflow-x: auto;
    overflow-y: hidden;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #gantt-chart {
        height: 300px !important;
        padding: 12px;
    }
}

/* Clean Gantt styling */
.gantt .bar {
    font-size: 12px;
    font-weight: 500;
    transition: all 0.2s ease;
    cursor: pointer;
    border-radius: 4px;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.gantt .bar:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.gantt .bar-label {
    font-size: 11px;
    font-weight: 500;
    color: white;
    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
}

.gantt .grid-row {
    height: 24px;
    transition: background-color 0.1s ease;
}

.gantt .grid-row:hover {
    background-color: #f9fafb;
}

.gantt .grid-header {
    height: 40px;
    background: #f8fafc;
    color: #374151;
    font-weight: 600;
    border-bottom: 1px solid #e5e7eb;
}

/* Professional bar colors */
.gantt .bar-wrapper .bar {
    background: #3b82f6;
    border: 1px solid #2563eb;
}

.gantt .bar-wrapper .bar.gantt-blue {
    background: #3b82f6;
    border-color: #2563eb;
}

.gantt .bar-wrapper .bar.gantt-green {
    background: #10b981;
    border-color: #059669;
}

.gantt .bar-wrapper .bar.gantt-yellow {
    background: #f59e0b;
    border-color: #d97706;
}

.gantt .bar-wrapper .bar.gantt-red {
    background: #ef4444;
    border-color: #dc2626;
}

.gantt .bar-wrapper .bar.gantt-gray {
    background: #6b7280;
    border-color: #4b5563;
}

.gantt .bar-wrapper .bar.gantt-purple {
    background: #8b5cf6;
    border-color: #7c3aed;
}

.gantt .bar-wrapper .bar.gantt-pink {
    background: #ec4899;
    border-color: #db2777;
}

.gantt .bar-wrapper .bar.gantt-indigo {
    background: #6366f1;
    border-color: #4f46e5;
}

/* Progress bars */
.gantt .bar-progress {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 2px;
}

/* Grid lines */
.gantt .grid-line {
    stroke: #e5e7eb;
    stroke-width: 1;
}

.gantt .grid-header-line {
    stroke: #d1d5db;
    stroke-width: 1;
}

/* Simple loading animation */
@keyframes fadeIn {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

#gantt-chart {
    animation: fadeIn 0.3s ease-out;
}

/* Custom scrollbar */
#gantt-chart::-webkit-scrollbar {
    height: 6px;
}

#gantt-chart::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

#gantt-chart::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

#gantt-chart::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

<script>
let ganttChart = null;
let tasks = @json($tasks);

// Initialize the Gantt chart
document.addEventListener('DOMContentLoaded', function() {
    initializeGanttChart();
    setupEventListeners();
    
    // Make chart responsive
    window.addEventListener('resize', function() {
        if (ganttChart) {
            setTimeout(function() {
                ganttChart.refresh();
            }, 100);
        }
    });
});

function setupEventListeners() {
    document.getElementById('view-mode').addEventListener('change', function() {
        if (ganttChart) {
            ganttChart.change_view_mode(this.value);
        }
    });
    
    document.getElementById('status-filter').addEventListener('change', function() {
        updateGanttChart();
    });
}

function initializeGanttChart() {
    const ganttData = prepareGanttData();
    
    ganttChart = new Gantt("#gantt-chart", ganttData, {
        header_height: 50,
        column_width: 30,
        step: 24,
        view_modes: ['Day', 'Week', 'Month'],
        bar_height: 20,
        bar_corner_radius: 4,
        arrow_curve: 5,
        padding: 20,
        popup_trigger: 'click',
        language: 'en',
        on_click: function (task) {
            console.log('Task clicked:', task);
        },
        on_date_change: function(task, start, end) {
            console.log('Task date changed:', task, start, end);
            updateTaskDates(task.id, start, end);
        },
        on_progress_change: function(task, progress) {
            console.log('Task progress changed:', task, progress);
            updateTaskProgress(task.id, progress);
        },
        on_view_change: function(mode) {
            console.log('View mode changed:', mode);
        }
    });
    
    // Ensure chart takes full width
    setTimeout(function() {
        const chartElement = document.getElementById('gantt-chart');
        if (chartElement && ganttChart) {
            chartElement.style.width = '100%';
            chartElement.style.minWidth = '100%';
            ganttChart.refresh();
        }
    }, 100);
}

function prepareGanttData() {
    const ganttData = [];
    
    tasks.forEach((task, index) => {
        if (task.start_date && task.due_date) {
            ganttData.push({
                id: task.id,
                name: task.title,
                start: task.start_date,
                end: task.due_date,
                progress: task.progress_percentage / 100,
                custom_class: getTaskClass(task.status),
                dependencies: task.dependencies ? task.dependencies.join(',') : ''
            });
        }
    });
    
    return ganttData;
}

function getTaskClass(status) {
    switch(status) {
        case 'completed':
            return 'gantt-green';
        case 'in_progress':
            return 'gantt-blue';
        case 'pending':
            return 'gantt-yellow';
        case 'cancelled':
            return 'gantt-red';
        default:
            return 'gantt-gray';
    }
}

function updateGanttChart() {
    const statusFilter = document.getElementById('status-filter').value;
    let filteredTasks = tasks;
    
    if (statusFilter) {
        filteredTasks = tasks.filter(task => task.status === statusFilter);
    }
    
    tasks = filteredTasks;
    
    if (ganttChart) {
        const ganttData = prepareGanttData();
        ganttChart.refresh(ganttData);
    }
}

function refreshGantt() {
    location.reload();
}

function exportGantt() {
    if (ganttChart) {
        // Export as SVG
        const svg = document.querySelector('#gantt-chart svg');
        const svgData = new XMLSerializer().serializeToString(svg);
        const svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
        const svgUrl = URL.createObjectURL(svgBlob);
        
        const downloadLink = document.createElement('a');
        downloadLink.href = svgUrl;
        downloadLink.download = 'gantt-chart-{{ $project->project_code }}-' + new Date().toISOString().slice(0, 10) + '.svg';
        downloadLink.click();
        
        URL.revokeObjectURL(svgUrl);
    }
}

function zoomIn() {
    if (ganttChart) {
        // Frappe Gantt doesn't have built-in zoom, but we can change view mode
        const currentMode = document.getElementById('view-mode').value;
        if (currentMode === 'Month') {
            document.getElementById('view-mode').value = 'Week';
        } else if (currentMode === 'Week') {
            document.getElementById('view-mode').value = 'Day';
        }
        ganttChart.change_view_mode(document.getElementById('view-mode').value);
    }
}

function zoomOut() {
    if (ganttChart) {
        const currentMode = document.getElementById('view-mode').value;
        if (currentMode === 'Day') {
            document.getElementById('view-mode').value = 'Week';
        } else if (currentMode === 'Week') {
            document.getElementById('view-mode').value = 'Month';
        }
        ganttChart.change_view_mode(document.getElementById('view-mode').value);
    }
}

function fitToScreen() {
    if (ganttChart) {
        document.getElementById('view-mode').value = 'Month';
        ganttChart.change_view_mode('Month');
    }
}

function updateTaskDates(taskId, startDate, endDate) {
    fetch(`{{ route('projects.update-task-dates', $project) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            task_id: taskId,
            start_date: startDate,
            end_date: endDate
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Task dates updated successfully', 'success');
        } else {
            showNotification('Failed to update task dates', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to update task dates', 'error');
    });
}

function updateTaskProgress(taskId, progress) {
    fetch(`{{ route('projects.update-task-progress', $project) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            task_id: taskId,
            progress: progress * 100
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Task progress updated successfully', 'success');
        } else {
            showNotification('Failed to update task progress', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to update task progress', 'error');
    });
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>

<style>
/* Custom Gantt chart styles */
.gantt-green {
    background-color: #10b981 !important;
}

.gantt-blue {
    background-color: #3b82f6 !important;
}

.gantt-yellow {
    background-color: #f59e0b !important;
}

.gantt-red {
    background-color: #ef4444 !important;
}

.gantt-gray {
    background-color: #6b7280 !important;
}

.gantt-container {
    font-family: 'Inter', sans-serif;
}

.gantt .bar {
    cursor: pointer;
}

.gantt .bar:hover {
    opacity: 0.8;
}

.gantt .bar-progress {
    background-color: rgba(255, 255, 255, 0.3);
}

.gantt .bar-label {
    color: white;
    font-weight: 500;
    font-size: 12px;
}
</style>
@endsection