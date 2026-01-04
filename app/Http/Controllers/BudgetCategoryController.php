<?php

namespace App\Http\Controllers;

use App\Models\BudgetCategory;
use Illuminate\Http\Request;

class BudgetCategoryController extends Controller
{
    public function index()
    {
        $categories = BudgetCategory::withCount('budgets')->get();
        return view('budget-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('budget-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:budget_categories,name',
            'description' => 'nullable|string',
            'color' => 'required|string',
            'is_active' => 'boolean',
        ]);

        BudgetCategory::create($request->all());

        return redirect()->route('budget-categories.index')
            ->with('success', 'Budget category created successfully.');
    }

    public function show(BudgetCategory $budgetCategory)
    {
        $budgetCategory->load(['budgets' => function ($query) {
            $query->orderBy('created_at', 'desc')->paginate(10);
        }]);
        
        return view('budget-categories.show', compact('budgetCategory'));
    }

    public function edit(BudgetCategory $budgetCategory)
    {
        return view('budget-categories.edit', compact('budgetCategory'));
    }

    public function update(Request $request, BudgetCategory $budgetCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:budget_categories,name,' . $budgetCategory->id,
            'description' => 'nullable|string',
            'color' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $budgetCategory->update($request->all());

        return redirect()->route('budget-categories.index')
            ->with('success', 'Budget category updated successfully.');
    }

    public function destroy(BudgetCategory $budgetCategory)
    {
        if ($budgetCategory->budgets()->count() > 0) {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot delete category. It has associated budgets.']);
        }

        $budgetCategory->delete();

        return redirect()->route('budget-categories.index')
            ->with('success', 'Budget category deleted successfully.');
    }
}
