<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Mark incomplete tasks from previous days as carried over
        Todo::forUser($user->id)
            ->where('task_date', '<', now()->toDateString())
            ->whereIn('status', ['pending', 'in_progress'])
            ->where('is_carried_over', false)
            ->update(['is_carried_over' => true]);
        
        // Get today's todos, with carried over tasks first
        // Also include any tasks that are blocking logout
        // (completed without remarks or not completed without reason)
        $todayTodos = Todo::forUser($user->id)
            ->where(function($query) {
                // All of today's tasks + carried over pending/in_progress tasks
                $query->where(function($q) {
                    $q->where('task_date', now()->toDateString())
                      ->orWhere(function($q2) {
                          $q2->where('task_date', '<', now()->toDateString())
                              ->whereIn('status', ['pending', 'in_progress'])
                              ->where('is_carried_over', true);
                      });
                })
                // Any completed tasks (any date) without remarks
                ->orWhere(function($q) {
                    $q->where('status', 'completed')
                      ->where(function($r) {
                          $r->whereNull('remarks')
                            ->orWhere('remarks', '');
                      });
                })
                // Any not_completed carried over tasks without reason
                ->orWhere(function($q) {
                    $q->where('status', 'not_completed')
                      ->where('is_carried_over', true)
                      ->where(function($r) {
                          $r->whereNull('not_completed_reason')
                            ->orWhere('not_completed_reason', '');
                      });
                });
            })
            ->orderBy('is_carried_over', 'desc')
            ->orderBy('priority', 'desc')
            // Show newest tasks at the top within the same group
            ->orderBy('created_at', 'desc')
            ->get();

        // Check if there are incomplete todos
        $hasIncompleteTodos = Todo::forUser($user->id)
            ->where(function($query) {
                $query->where('task_date', now()->toDateString())
                      ->orWhere(function($q) {
                          $q->where('task_date', '<', now()->toDateString())
                            ->whereIn('status', ['pending', 'in_progress'])
                            ->where('is_carried_over', true);
                      });
            })
            ->whereIn('status', ['pending', 'in_progress'])
            ->exists();

        // Check for completed todos without remarks
        $completedWithoutRemarks = Todo::forUser($user->id)
            ->where(function($query) {
                $query->where('task_date', now()->toDateString())
                      ->orWhere(function($q) {
                          $q->where('task_date', '<', now()->toDateString())
                            ->where('status', 'completed');
                      });
            })
            ->where('status', 'completed')
            ->where(function($q) {
                $q->whereNull('remarks')
                  ->orWhere('remarks', '');
            })
            ->count();

        // Check for not_completed todos without reason
        $notCompletedWithoutReason = Todo::forUser($user->id)
            ->where(function($query) {
                $query->where('task_date', now()->toDateString())
                      ->orWhere(function($q) {
                          $q->where('task_date', '<', now()->toDateString())
                            ->where('status', 'not_completed')
                            ->where('is_carried_over', true);
                      });
            })
            ->where('status', 'not_completed')
            ->where(function($q) {
                $q->whereNull('not_completed_reason')
                  ->orWhere('not_completed_reason', '');
            })
            ->count();

        return view('todos.index', compact('todayTodos', 'hasIncompleteTodos', 'completedWithoutRemarks', 'notCompletedWithoutReason'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        // Allow creating new tasks regardless of carried over tasks.
        // Business rule: carried over tasks remain visible but should not block adding new tasks.

        Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority ?? 'medium',
            'user_id' => Auth::id(),
            'task_date' => now()->toDateString(),
            'is_daily_task' => true,
            'status' => 'pending',
        ]);

        if ($request->has('from_modal')) {
            // If user clicked "Add Task & Add More", stay on dashboard so modal logic can prompt again
            if ($request->input('add_more') === '1') {
                return redirect()->route('dashboard')->with('success', 'Todo added successfully. You can add more tasks.');
            }

            // Default behavior: allow user to continue using the system
            return redirect()->route('dashboard')->with('success', 'Todo added successfully. You can now continue using the system.');
        }

        return redirect()->route('todos.index')->with('success', 'Todo added successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);
        
        if ($todo->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['SUPER ADMIN', 'SALES MANAGER'])) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,not_completed',
            'remarks' => 'required|string|min:2',
            // Reason is optional at validator level; front-end + business logic enforce it when needed
            'not_completed_reason' => 'nullable|string',
        ]);

        $updateData = [
            'status' => $request->status,
            'remarks' => $request->remarks,
        ];

        if ($request->status === 'completed') {
            $updateData['completion_date'] = now()->toDateString();
            $updateData['is_carried_over'] = false;
        } elseif ($request->status === 'not_completed') {
            $updateData['not_completed_reason'] = $request->not_completed_reason;
            $updateData['is_carried_over'] = true;
            // Keep the original task_date for carried over tasks
        }

        $todo->update($updateData);

        return redirect()->route('todos.index')->with('success', 'Todo status updated successfully.');
    }

    public function allTodos()
    {
        if (!Auth::user()->hasAnyRole(['SUPER ADMIN', 'SALES MANAGER'])) {
            abort(403, 'Unauthorized access.');
        }

        $todos = Todo::with(['user', 'assignedBy'])
            ->orderBy('task_date', 'desc')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('todos.all', compact('todos', 'users'));
    }

    public function assign(Request $request)
    {
        if (!Auth::user()->hasAnyRole(['SUPER ADMIN', 'SALES MANAGER'])) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority ?? 'medium',
            'user_id' => $request->user_id,
            'assigned_by' => Auth::id(),
            'due_date' => $request->due_date,
            'task_date' => now()->toDateString(),
            'is_daily_task' => false,
            'status' => 'pending',
        ]);

        return redirect()->route('todos.all')->with('success', 'Todo assigned successfully.');
    }

    public function transfer(Request $request, $id)
    {
        if (!Auth::user()->hasAnyRole(['SUPER ADMIN', 'SALES MANAGER'])) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $todo = Todo::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $todo->update([
            'user_id' => $request->user_id,
            'assigned_by' => Auth::id(),
        ]);

        return redirect()->route('todos.all')->with('success', 'Todo transferred successfully.');
    }

    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        
        if ($todo->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['SUPER ADMIN', 'SALES MANAGER'])) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $todo->delete();

        return redirect()->back()->with('success', 'Todo deleted successfully.');
    }

    public function checkIncompleteTodos()
    {
        $user = Auth::user();
        
        // Check for incomplete todos (pending/in_progress)
        $incompleteTodos = Todo::forUser($user->id)
            ->where(function($query) {
                $query->where('task_date', now()->toDateString())
                      ->orWhere(function($q) {
                          $q->where('task_date', '<', now()->toDateString())
                            ->whereIn('status', ['pending', 'in_progress'])
                            ->where('is_carried_over', true);
                      });
            })
            ->whereIn('status', ['pending', 'in_progress'])
            ->get();

        // Check for not_completed todos without reason
        $notCompletedWithoutReason = Todo::forUser($user->id)
            ->where(function($query) {
                $query->where('task_date', now()->toDateString())
                      ->orWhere(function($q) {
                          $q->where('task_date', '<', now()->toDateString())
                            ->where('status', 'not_completed')
                            ->where('is_carried_over', true);
                      });
            })
            ->where('status', 'not_completed')
            ->where(function($q) {
                $q->whereNull('not_completed_reason')
                  ->orWhere('not_completed_reason', '');
            })
            ->count();

        // Check for completed todos without remarks
        $completedWithoutRemarks = Todo::forUser($user->id)
            ->where(function($query) {
                $query->where('task_date', now()->toDateString())
                      ->orWhere(function($q) {
                          $q->where('task_date', '<', now()->toDateString())
                            ->where('status', 'completed');
                      });
            })
            ->where('status', 'completed')
            ->where(function($q) {
                $q->whereNull('remarks')
                  ->orWhere('remarks', '');
            })
            ->count();

        $hasIssues = $incompleteTodos->count() > 0 || $notCompletedWithoutReason > 0 || $completedWithoutRemarks > 0;

        return response()->json([
            'has_incomplete' => $hasIssues,
            'incomplete_count' => $incompleteTodos->count(),
            'not_completed_without_reason' => $notCompletedWithoutReason,
            'completed_without_remarks' => $completedWithoutRemarks,
            'message' => $this->getLogoutErrorMessage($incompleteTodos->count(), $notCompletedWithoutReason, $completedWithoutRemarks)
        ]);
    }

    private function getLogoutErrorMessage($incompleteCount, $notCompletedWithoutReason, $completedWithoutRemarks)
    {
        $messages = [];
        
        if ($incompleteCount > 0) {
            $messages[] = "You have {$incompleteCount} incomplete task(s). Please update their status before logging out.";
        }
        
        if ($notCompletedWithoutReason > 0) {
            $messages[] = "You have {$notCompletedWithoutReason} not completed task(s) without reason. Please provide a reason for not completing them.";
        }
        
        if ($completedWithoutRemarks > 0) {
            $messages[] = "You have {$completedWithoutRemarks} completed task(s) without remarks. Please add remarks for completed tasks.";
        }
        
        return implode(' ', $messages);
    }

    public function getTodosForModal()
    {
        $user = Auth::user();
        
        $todayTodos = Todo::forUser($user->id)
            ->where(function($query) {
                // All of today's tasks + carried over pending/in_progress tasks
                $query->where(function($q) {
                    $q->where('task_date', now()->toDateString())
                      ->orWhere(function($q2) {
                          $q2->where('task_date', '<', now()->toDateString())
                              ->whereIn('status', ['pending', 'in_progress'])
                              ->where('is_carried_over', true);
                      });
                })
                // Any completed tasks (any date) without remarks
                ->orWhere(function($q) {
                    $q->where('status', 'completed')
                      ->where(function($r) {
                          $r->whereNull('remarks')
                            ->orWhere('remarks', '');
                      });
                })
                // Any not_completed carried over tasks without reason
                ->orWhere(function($q) {
                    $q->where('status', 'not_completed')
                      ->where('is_carried_over', true)
                      ->where(function($r) {
                          $r->whereNull('not_completed_reason')
                            ->orWhere('not_completed_reason', '');
                      });
                });
            })
            ->orderBy('is_carried_over', 'desc')
            ->orderBy('priority', 'desc')
            // Newest tasks first within each group
            ->orderBy('created_at', 'desc')
            ->get();

        $hasTodos = $todayTodos->count() > 0;

        // Check if user has any todos for today
        $hasTodayTodos = Todo::forUser($user->id)
            ->where('task_date', now()->toDateString())
            ->exists();

        // Determine if the daily todo modal should be shown for this session
        // We want it to appear once every time the user logs in,
        // even if they already have tasks for today.
        $hasModalShownThisSession = session()->get('daily_todo_modal_shown', false);

        // If it hasn't been shown yet in this login session, mark it as shown now
        if (! $hasModalShownThisSession) {
            session()->put('daily_todo_modal_shown', true);
        }

        $showModal = ! $hasModalShownThisSession;

        return response()->json([
            'todos' => $todayTodos,
            'has_todos' => $hasTodos,
            'has_today_todos' => $hasTodayTodos,
            'incomplete_count' => $todayTodos->whereIn('status', ['pending', 'in_progress'])->count(),
            // Frontend will show the modal if this is true OR if there are no todos for today
            'show_modal' => $showModal
        ]);
    }
}
