@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Resource Allocation</h1>
            <p class="text-muted">{{ $resourceAllocation->allocation_code }}</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('resource-allocations.show', $resourceAllocation) }}" class="btn btn-info">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                View
            </a>
            <a href="{{ route('resource-allocations.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Allocations
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Allocation Details</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('resource-allocations.update', $resourceAllocation) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $resourceAllocation->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="resource_type" class="form-label">Resource Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('resource_type') is-invalid @enderror" 
                                            id="resource_type" name="resource_type" required onchange="toggleCostFields()">
                                        <option value="">Select Type</option>
                                        <option value="human" {{ old('resource_type', $resourceAllocation->resource_type) == 'human' ? 'selected' : '' }}>Human</option>
                                        <option value="equipment" {{ old('resource_type', $resourceAllocation->resource_type) == 'equipment' ? 'selected' : '' }}>Equipment</option>
                                        <option value="material" {{ old('resource_type', $resourceAllocation->resource_type) == 'material' ? 'selected' : '' }}>Material</option>
                                        <option value="financial" {{ old('resource_type', $resourceAllocation->resource_type) == 'financial' ? 'selected' : '' }}>Financial</option>
                                        <option value="other" {{ old('resource_type', $resourceAllocation->resource_type) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('resource_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $resourceAllocation->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                    <select class="form-control @error('priority') is-invalid @enderror" 
                                            id="priority" name="priority" required>
                                        <option value="">Select Priority</option>
                                        <option value="low" {{ old('priority', $resourceAllocation->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority', $resourceAllocation->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority', $resourceAllocation->priority) == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="critical" {{ old('priority', $resourceAllocation->priority) == 'critical' ? 'selected' : '' }}>Critical</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="planned" {{ old('status', $resourceAllocation->status) == 'planned' ? 'selected' : '' }}>Planned</option>
                                        <option value="allocated" {{ old('status', $resourceAllocation->status) == 'allocated' ? 'selected' : '' }}>Allocated</option>
                                        <option value="in_use" {{ old('status', $resourceAllocation->status) == 'in_use' ? 'selected' : '' }}>In Use</option>
                                        <option value="completed" {{ old('status', $resourceAllocation->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status', $resourceAllocation->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="project_id" class="form-label">Project <span class="text-danger">*</span></label>
                                    <select class="form-control @error('project_id') is-invalid @enderror" 
                                            id="project_id" name="project_id" required onchange="loadActivities()">
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id', $resourceAllocation->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="activity_id" class="form-label">Activity</label>
                                    <select class="form-control @error('activity_id') is-invalid @enderror" 
                                            id="activity_id" name="activity_id">
                                        <option value="">Select Activity</option>
                                        @foreach($activities as $activity)
                                            <option value="{{ $activity->id }}" {{ old('activity_id', $resourceAllocation->activity_id) == $activity->id ? 'selected' : '' }}>{{ $activity->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('activity_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="resource_name" class="form-label">Resource Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('resource_name') is-invalid @enderror" 
                                           id="resource_name" name="resource_name" value="{{ old('resource_name', $resourceAllocation->resource_name) }}" required>
                                    @error('resource_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="resource_category" class="form-label">Resource Category</label>
                                    <input type="text" class="form-control @error('resource_category') is-invalid @enderror" 
                                           id="resource_category" name="resource_category" value="{{ old('resource_category', $resourceAllocation->resource_category) }}">
                                    @error('resource_category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="resource_specifications" class="form-label">Resource Specifications</label>
                            <textarea class="form-control @error('resource_specifications') is-invalid @enderror" 
                                      id="resource_specifications" name="resource_specifications" rows="2">{{ old('resource_specifications', $resourceAllocation->resource_specifications) }}</textarea>
                            @error('resource_specifications')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="allocated_to" class="form-label">Allocated To</label>
                                    <select class="form-control @error('allocated_to') is-invalid @enderror" 
                                            id="allocated_to" name="allocated_to">
                                        <option value="">Select User</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('allocated_to', $resourceAllocation->allocated_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('allocated_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="allocation_start_date" class="form-label">Planned Start Date</label>
                                    <input type="datetime-local" class="form-control @error('allocation_start_date') is-invalid @enderror" 
                                           id="allocation_start_date" name="allocation_start_date" 
                                           value="{{ old('allocation_start_date', $resourceAllocation->allocation_start_date ? $resourceAllocation->allocation_start_date->format('Y-m-d\TH:i') : '') }}">
                                    @error('allocation_start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="allocation_end_date" class="form-label">Planned End Date</label>
                                    <input type="datetime-local" class="form-control @error('allocation_end_date') is-invalid @enderror" 
                                           id="allocation_end_date" name="allocation_end_date" 
                                           value="{{ old('allocation_end_date', $resourceAllocation->allocation_end_date ? $resourceAllocation->allocation_end_date->format('Y-m-d\TH:i') : '') }}">
                                    @error('allocation_end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="actual_start_date" class="form-label">Actual Start Date</label>
                                    <input type="datetime-local" class="form-control @error('actual_start_date') is-invalid @enderror" 
                                           id="actual_start_date" name="actual_start_date" 
                                           value="{{ old('actual_start_date', $resourceAllocation->actual_start_date ? $resourceAllocation->actual_start_date->format('Y-m-d\TH:i') : '') }}">
                                    @error('actual_start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="actual_end_date" class="form-label">Actual End Date</label>
                                    <input type="datetime-local" class="form-control @error('actual_end_date') is-invalid @enderror" 
                                           id="actual_end_date" name="actual_end_date" 
                                           value="{{ old('actual_end_date', $resourceAllocation->actual_end_date ? $resourceAllocation->actual_end_date->format('Y-m-d\TH:i') : '') }}">
                                    @error('actual_end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="allocated_quantity" class="form-label">Allocated Quantity <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('allocated_quantity') is-invalid @enderror" 
                                           id="allocated_quantity" name="allocated_quantity" value="{{ old('allocated_quantity', $resourceAllocation->allocated_quantity) }}" required onchange="calculateCost()">
                                    @error('allocated_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="actual_quantity" class="form-label">Actual Quantity</label>
                                    <input type="number" step="0.01" class="form-control @error('actual_quantity') is-invalid @enderror" 
                                           id="actual_quantity" name="actual_quantity" value="{{ old('actual_quantity', $resourceAllocation->actual_quantity) }}">
                                    @error('actual_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="quantity_unit" class="form-label">Unit <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('quantity_unit') is-invalid @enderror" 
                                           id="quantity_unit" name="quantity_unit" value="{{ old('quantity_unit', $resourceAllocation->quantity_unit) }}" required>
                                    @error('quantity_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Cost Fields -->
                        <div id="cost-fields">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="hourly_rate" class="form-label">Hourly Rate (Rs.)</label>
                                        <input type="number" step="0.01" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                               id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $resourceAllocation->hourly_rate) }}" onchange="calculateCost()">
                                        @error('hourly_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="unit_cost" class="form-label">Unit Cost (Rs.)</label>
                                        <input type="number" step="0.01" class="form-control @error('unit_cost') is-invalid @enderror" 
                                               id="unit_cost" name="unit_cost" value="{{ old('unit_cost', $resourceAllocation->unit_cost) }}" onchange="calculateCost()">
                                        @error('unit_cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="total_estimated_cost" class="form-label">Estimated Cost (Rs.)</label>
                                        <input type="number" step="0.01" class="form-control @error('total_estimated_cost') is-invalid @enderror" 
                                               id="total_estimated_cost" name="total_estimated_cost" value="{{ old('total_estimated_cost', $resourceAllocation->total_estimated_cost) }}">
                                        @error('total_estimated_cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="total_actual_cost" class="form-label">Actual Cost (Rs.)</label>
                                        <input type="number" step="0.01" class="form-control @error('total_actual_cost') is-invalid @enderror" 
                                               id="total_actual_cost" name="total_actual_cost" value="{{ old('total_actual_cost', $resourceAllocation->total_actual_cost) }}">
                                        @error('total_actual_cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="budget_allocated" class="form-label">Budget Allocated (Rs.)</label>
                                        <input type="number" step="0.01" class="form-control @error('budget_allocated') is-invalid @enderror" 
                                               id="budget_allocated" name="budget_allocated" value="{{ old('budget_allocated', $resourceAllocation->budget_allocated) }}">
                                        @error('budget_allocated')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="utilization_percentage" class="form-label">Utilization Percentage</label>
                                    <input type="number" step="0.01" class="form-control @error('utilization_percentage') is-invalid @enderror" 
                                           id="utilization_percentage" name="utilization_percentage" 
                                           min="0" max="100" value="{{ old('utilization_percentage', $resourceAllocation->utilization_percentage) }}">
                                    @error('utilization_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="utilization_notes" class="form-label">Utilization Notes</label>
                            <textarea class="form-control @error('utilization_notes') is-invalid @enderror" 
                                      id="utilization_notes" name="utilization_notes" rows="2">{{ old('utilization_notes', $resourceAllocation->utilization_notes) }}</textarea>
                            @error('utilization_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="completion_notes" class="form-label">Completion Notes</label>
                            <textarea class="form-control @error('completion_notes') is-invalid @enderror" 
                                      id="completion_notes" name="completion_notes" rows="2">{{ old('completion_notes', $resourceAllocation->completion_notes) }}</textarea>
                            @error('completion_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3">{{ old('notes', $resourceAllocation->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tags" class="form-label">Tags</label>
                                    <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                           id="tags" name="tags" value="{{ old('tags', $resourceAllocation->tags ? implode(',', $resourceAllocation->tags) : '') }}" placeholder="Enter tags separated by commas">
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_critical" name="is_critical" value="1" {{ old('is_critical', $resourceAllocation->is_critical) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_critical">
                                        Critical Resource
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_billable" name="is_billable" value="1" {{ old('is_billable', $resourceAllocation->is_billable) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_billable">
                                        Billable Resource
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Allocation
                            </button>
                            <a href="{{ route('resource-allocations.show', $resourceAllocation) }}" class="btn btn-secondary ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Current Status</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="progress mx-auto mb-2" style="width: 100px; height: 100px;">
                            <div class="progress-bar {{ $resourceAllocation->utilization_bar_color }}" role="progressbar" 
                                 style="width: {{ $resourceAllocation->utilization_percentage }}%" 
                                 aria-valuenow="{{ $resourceAllocation->utilization_percentage }}" 
                                 aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <h4 class="font-weight-bold">{{ $resourceAllocation->utilization_percentage }}%</h4>
                        <p class="text-muted">Utilization</p>
                    </div>

                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <h5 class="font-weight-bold text-primary">{{ $resourceAllocation->allocated_quantity }}</h5>
                            <p class="text-muted small">Allocated</p>
                        </div>
                        <div class="col-6">
                            <h5 class="font-weight-bold text-success">{{ $resourceAllocation->actual_quantity }}</h5>
                            <p class="text-muted small">Actual</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="font-weight-bold text-info">{{ $resourceAllocation->formatted_total_estimated_cost }}</h6>
                            <p class="text-muted small">Estimated</p>
                        </div>
                        <div class="col-6">
                            <h6 class="font-weight-bold text-warning">{{ $resourceAllocation->formatted_total_actual_cost }}</h6>
                            <p class="text-muted small">Actual</p>
                        </div>
                    </div>

                    <hr>

                    <div class="text-center">
                        <span class="badge {{ $resourceAllocation->status_badge }} mb-2">{{ ucfirst($resourceAllocation->status) }}</span>
                        <br>
                        <span class="badge {{ $resourceAllocation->priority_badge }} mb-2">{{ ucfirst($resourceAllocation->priority) }}</span>
                        <br>
                        <span class="badge {{ $resourceAllocation->resource_type_badge }}">{{ ucfirst($resourceAllocation->resource_type) }}</span>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Info</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="font-weight-bold">Status Changes</h6>
                        <ul class="list-unstyled text-sm">
                            <li><span class="badge badge-info">Planned</span> → <span class="badge badge-warning">Allocated</span></li>
                            <li><span class="badge badge-warning">Allocated</span> → <span class="badge badge-success">In Use</span></li>
                            <li><span class="badge badge-success">In Use</span> → <span class="badge badge-secondary">Completed</span></li>
                            <li>Any status → <span class="badge badge-danger">Cancelled</span></li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="font-weight-bold">Cost Calculation</h6>
                        <ul class="list-unstyled text-sm">
                            <li><strong>Human:</strong> Quantity × Hourly Rate</li>
                            <li><strong>Equipment/Material:</strong> Quantity × Unit Cost</li>
                            <li><strong>Financial:</strong> Fixed amount</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6 class="font-weight-bold">Utilization Guidelines</h6>
                        <ul class="list-unstyled text-sm">
                            <li><span class="badge badge-success">0-50%</span> Underutilized</li>
                            <li><span class="badge badge-primary">51-79%</span> Normal</li>
                            <li><span class="badge badge-warning">80-99%</span> High</li>
                            <li><span class="badge badge-danger">100%+</span> Overallocated</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCostFields() {
    const resourceType = document.getElementById('resource_type').value;
    const costFields = document.getElementById('cost-fields');
    const hourlyRateField = document.getElementById('hourly_rate');
    const unitCostField = document.getElementById('unit_cost');
    
    if (resourceType === 'human') {
        costFields.style.display = 'block';
        hourlyRateField.required = true;
        unitCostField.required = false;
    } else if (resourceType === 'equipment' || resourceType === 'material') {
        costFields.style.display = 'block';
        hourlyRateField.required = false;
        unitCostField.required = true;
    } else {
        costFields.style.display = 'block'; // Show for editing
        hourlyRateField.required = false;
        unitCostField.required = false;
    }
    
    calculateCost();
}

function calculateCost() {
    const resourceType = document.getElementById('resource_type').value;
    const quantity = parseFloat(document.getElementById('allocated_quantity').value) || 0;
    const hourlyRate = parseFloat(document.getElementById('hourly_rate').value) || 0;
    const unitCost = parseFloat(document.getElementById('unit_cost').value) || 0;
    
    let estimatedCost = 0;
    
    if (resourceType === 'human') {
        estimatedCost = quantity * hourlyRate;
    } else if (resourceType === 'equipment' || resourceType === 'material') {
        estimatedCost = quantity * unitCost;
    }
    
    document.getElementById('total_estimated_cost').value = estimatedCost.toFixed(2);
}

function loadActivities() {
    const projectId = document.getElementById('project_id').value;
    const activitySelect = document.getElementById('activity_id');
    
    // Clear existing options except the first one
    const firstOption = activitySelect.querySelector('option:first-child');
    activitySelect.innerHTML = '';
    activitySelect.appendChild(firstOption);
    
    if (projectId) {
        // In a real application, you would make an AJAX call to fetch activities
        // For now, we'll show a placeholder
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'Loading activities...';
        activitySelect.appendChild(option);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleCostFields();
    calculateCost();
});
</script>
@endsection


