<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Categories;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->where('is_active', 1);


        if ($request->filled('categories')) {
            $categories = explode(',', $request->categories);
            $query->whereIn('category_id', $categories);
        }


        if ($request->filled('brands')) {
            $brands = explode(',', $request->brands);
            $query->whereIn('brand_id', $brands);
        }


        if ($request->boolean('is_featured')) {
            $query->where('is_featured', 1);
        }

        if ($request->boolean('on_sale')) {
            $query->where('on_sale', 1);
        }


        if ($request->filled('price_range')) {
            $query->whereBetween('price', [0, $request->price_range]);
        }


        if ($request->sort === 'price') {
            $query->orderBy('price');
        } else {
            $query->latest();
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // ✅ Pagination
        $products = $query->paginate(12);

        return response()->json([
            'filters' => [
                'brands' => Brand::where('is_active', 1)->get(['id', 'name', 'slug']),
                'categories' => Categories::where('is_active', 1)->get(['id', 'name', 'slug']),
            ],
            'products' => $products,
        ]);
    }
}
