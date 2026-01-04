@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
            <p class="mt-2 text-gray-600">Comprehensive charts and analytics for your Solar ERP system</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button onclick="exportCharts()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Charts
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Projects -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Projects</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_projects']) }}</p>
                </div>
            </div>
        </div>

        <!-- Active Projects -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Projects</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['active_projects']) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900">₹{{ number_format($stats['total_revenue'], 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed Tasks</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['completed_tasks']) }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <label class="text-sm font-medium text-gray-700">Time Period:</label>
                <select id="timePeriod" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                    <option value="7">Last 7 Days</option>
                    <option value="30" selected>Last 30 Days</option>
                    <option value="90">Last 90 Days</option>
                    <option value="365">Last Year</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="refreshCharts()" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    Refresh
                </button>
                <button onclick="toggleFullscreen()" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    Fullscreen
                </button>
            </div>
        </div>
    </div>

    <!-- Revenue Trends Chart -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-medium text-gray-900">Revenue Trends by Month</h3>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Total Revenue:</span>
                <span class="text-lg font-bold text-teal-600" id="totalRevenue">₹{{ number_format($stats['total_revenue'], 0) }}</span>
            </div>
        </div>
        <div class="h-80">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Project Status Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Project Status Pie Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Project Status Distribution</h3>
            <div class="h-80">
                <canvas id="projectStatusChart"></canvas>
            </div>
        </div>

        <!-- Lead Conversion Funnel -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Lead Conversion Funnel</h3>
            <div class="h-80">
                <canvas id="leadFunnelChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Task Completion Rate -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Task Completion Rate</h3>
            <div class="h-64">
                <canvas id="taskCompletionChart"></canvas>
            </div>
        </div>

        <!-- Solar Panel Sales -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Solar Panel Sales</h3>
            <div class="h-64">
                <canvas id="solarSalesChart"></canvas>
            </div>
        </div>

        <!-- Customer Satisfaction -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Customer Satisfaction</h3>
            <div class="h-64">
                <canvas id="satisfactionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Advanced Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Installation Progress -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Installation Progress Timeline</h3>
            <div class="h-80">
                <canvas id="installationChart"></canvas>
            </div>
        </div>

        <!-- Regional Performance -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Regional Performance</h3>
            <div class="h-80">
                <canvas id="regionalChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

<script>
// Global variables for charts
let revenueChart, projectStatusChart, leadFunnelChart, taskCompletionChart, solarSalesChart, satisfactionChart, installationChart, regionalChart;

// Chart configurations
const chartConfigs = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
            labels: {
                usePointStyle: true,
                padding: 20
            }
        }
    }
};

// Load chart data from server
async function loadChartData(period = 30) {
    try {
        const response = await fetch(`{{ route('analytics.chart-data') }}?period=${period}`);
        const data = await response.json();
        
        updateCharts(data);
    } catch (error) {
        console.error('Error loading chart data:', error);
    }
}

// Update all charts with new data
function updateCharts(data) {
    // Update Revenue Chart
    revenueChart.data.labels = data.revenue.labels;
    revenueChart.data.datasets[0].data = data.revenue.data;
    revenueChart.update();
    
    // Update total revenue display
    document.getElementById('totalRevenue').textContent = '₹' + data.revenue.total.toLocaleString();
    
    // Update Project Status Chart
    projectStatusChart.data.labels = data.projects.labels;
    projectStatusChart.data.datasets[0].data = data.projects.data;
    projectStatusChart.data.datasets[0].backgroundColor = data.projects.colors;
    projectStatusChart.update();
    
    // Update Lead Funnel Chart
    leadFunnelChart.data.labels = data.leads.labels;
    leadFunnelChart.data.datasets[0].data = data.leads.data;
    leadFunnelChart.data.datasets[0].backgroundColor = data.leads.colors;
    leadFunnelChart.update();
    
    // Update Task Completion Chart
    taskCompletionChart.data.labels = data.tasks.labels;
    taskCompletionChart.data.datasets[0].data = data.tasks.data;
    taskCompletionChart.update();
    
    // Update Solar Sales Chart
    solarSalesChart.data.labels = data.sales.labels;
    solarSalesChart.data.datasets[0].data = data.sales.data;
    solarSalesChart.data.datasets[0].backgroundColor = data.sales.colors;
    solarSalesChart.update();
    
    // Update Satisfaction Chart
    satisfactionChart.data.labels = data.satisfaction.labels;
    satisfactionChart.data.datasets[0].data = data.satisfaction.data;
    satisfactionChart.update();
    
    // Update Installation Chart
    installationChart.data.labels = data.installation.labels;
    installationChart.data.datasets[0].data = data.installation.completed;
    installationChart.data.datasets[1].data = data.installation.inProgress;
    installationChart.update();
    
    // Update Regional Chart
    regionalChart.data.datasets = data.regional;
    regionalChart.update();
}

// Initialize charts with default data
function initializeCharts() {
    // Revenue Trends Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Revenue (₹)',
                data: [],
                borderColor: '#0d9488',
                backgroundColor: 'rgba(13, 148, 136, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#0d9488',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            ...chartConfigs,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                ...chartConfigs.plugins,
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: ₹' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Project Status Pie Chart
    const projectStatusCtx = document.getElementById('projectStatusChart').getContext('2d');
    projectStatusChart = new Chart(projectStatusCtx, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [],
                borderWidth: 3,
                borderColor: '#ffffff'
            }]
        },
        options: {
            ...chartConfigs,
            plugins: {
                ...chartConfigs.plugins,
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // Lead Conversion Funnel
    const leadFunnelCtx = document.getElementById('leadFunnelChart').getContext('2d');
    leadFunnelChart = new Chart(leadFunnelCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Leads',
                data: [],
                backgroundColor: [],
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            ...chartConfigs,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                ...chartConfigs.plugins,
                legend: {
                    display: false
                }
            }
        }
    });

    // Task Completion Rate
    const taskCompletionCtx = document.getElementById('taskCompletionChart').getContext('2d');
    taskCompletionChart = new Chart(taskCompletionCtx, {
        type: 'radar',
        data: {
            labels: [],
            datasets: [{
                label: 'Completion %',
                data: [],
                borderColor: '#0d9488',
                backgroundColor: 'rgba(13, 148, 136, 0.2)',
                borderWidth: 2,
                pointBackgroundColor: '#0d9488',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2
            }]
        },
        options: {
            ...chartConfigs,
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 20
                    }
                }
            }
        }
    });

    // Solar Panel Sales
    const solarSalesCtx = document.getElementById('solarSalesChart').getContext('2d');
    solarSalesChart = new Chart(solarSalesCtx, {
        type: 'polarArea',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            ...chartConfigs,
            plugins: {
                ...chartConfigs.plugins,
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Customer Satisfaction
    const satisfactionCtx = document.getElementById('satisfactionChart').getContext('2d');
    satisfactionChart = new Chart(satisfactionCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Satisfaction Score',
                data: [],
                backgroundColor: 'rgba(13, 148, 136, 0.8)',
                borderColor: '#0d9488',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            ...chartConfigs,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                ...chartConfigs.plugins,
                legend: {
                    display: false
                }
            }
        }
    });

    // Installation Progress
    const installationCtx = document.getElementById('installationChart').getContext('2d');
    installationChart = new Chart(installationCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Completed',
                data: [],
                backgroundColor: '#10b981',
                borderRadius: 4
            }, {
                label: 'In Progress',
                data: [],
                backgroundColor: '#f59e0b',
                borderRadius: 4
            }]
        },
        options: {
            ...chartConfigs,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    // Regional Performance
    const regionalCtx = document.getElementById('regionalChart').getContext('2d');
    regionalChart = new Chart(regionalCtx, {
        type: 'scatter',
        data: {
            datasets: []
        },
        options: {
            ...chartConfigs,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Projects Completed'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Customer Satisfaction (%)'
                    }
                }
            }
        }
    });
}

// Chart functions
function refreshCharts() {
    const period = document.getElementById('timePeriod').value;
    loadChartData(period);
}

function exportCharts() {
    // Export all charts as images
    const charts = [revenueChart, projectStatusChart, leadFunnelChart, taskCompletionChart, solarSalesChart, satisfactionChart, installationChart, regionalChart];
    
    charts.forEach((chart, index) => {
        const link = document.createElement('a');
        link.download = `chart-${index + 1}.png`;
        link.href = chart.toBase64Image();
        link.click();
    });
}

function toggleFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}

// Initialize charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    loadChartData(); // Load initial data
    
    // Update charts when time period changes
    document.getElementById('timePeriod').addEventListener('change', function() {
        loadChartData(this.value);
    });
});
</script>
@endsection

