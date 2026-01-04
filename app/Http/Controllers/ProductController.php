<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low_stock') {
                $query->whereRaw('current_stock <= min_stock_level');
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('current_stock', 0);
            } elseif ($request->stock_status === 'in_stock') {
                $query->whereRaw('current_stock > min_stock_level');
            }
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('hsn_code', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->latest()->paginate(15);
        
        $stats = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'low_stock' => Product::whereRaw('current_stock <= min_stock_level')->count(),
            'out_of_stock' => Product::where('current_stock', 0)->count(),
            'total_value' => Product::sum(\DB::raw('current_stock * purchase_price')),
        ];

        $categories = Product::distinct()->pluck('category')->filter();

        return view('products.index', compact('products', 'stats', 'categories'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'unit' => 'required|string|max:20',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'current_stock' => 'required|integer|min:0',
            'hsn_code' => 'nullable|string|max:20',
            'gst_rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        \Log::info('ProductController@show called', ['product_id' => $product?->id, 'product_name' => $product?->name]);
        
        if (!$product) {
            \Log::error('Product is null in show method');
            return redirect()->route('products.index')->with('error', 'Product not found.');
        }
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found.');
        }
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'unit' => 'required|string|max:20',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'current_stock' => 'required|integer|min:0',
            'hsn_code' => 'nullable|string|max:20',
            'gst_rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    public function showStockAdjustment(Product $product)
    {
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found.');
        }
        return view('products.stock-adjustment', compact('product'));
    }

    public function stockAdjustment(Request $request, Product $product)
    {
        $request->validate([
            'adjustment_type' => 'required|in:add,remove',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $oldStock = $product->current_stock;
        
        if ($request->adjustment_type === 'add') {
            $product->current_stock += $request->quantity;
        } else {
            $product->current_stock = max(0, $product->current_stock - $request->quantity);
        }

        $product->save();

        return redirect()->route('products.show', $product)->with('success', 
            "Stock adjusted successfully! {$oldStock} → {$product->current_stock}");
    }

    public function lowStock()
    {
        $products = Product::whereRaw('current_stock <= min_stock_level')
            ->where('is_active', true)
            ->orderBy('current_stock', 'asc')
            ->paginate(20);

        return view('products.low-stock', compact('products'));
    }
}
