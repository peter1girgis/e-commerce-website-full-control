<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ads;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Categories;
use App\Models\home_products;
use App\Models\Product;
use App\Models\website_settings;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // public function addToCart(Request $request, $id)
    //     {
    //         if (!auth()->check()) {
    //             return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
    //         }

    //         $request->validate([
    //             'quantity' => 'required|integer|min:1',
    //         ]);

    //         $quantity = $request->input('quantity', 1);
    //         $product  = Product::findOrFail($id);

    //         $cartItem = Cart::where('user_id', auth()->id())
    //             ->where('product_id', $product->id)
    //             ->first();

    //         if ($cartItem) {
    //             $cartItem->quantity += $quantity;
    //             $cartItem->total_amount = $cartItem->unit_amount * $cartItem->quantity;
    //             $cartItem->save();

    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'Product quantity updated in cart successfully!',
    //                 'total_cart_items' => Cart::where('user_id', auth()->id())->count(),
    //             ]);
    //         }

    //         Cart::create([
    //             'user_id'      => auth()->id(),
    //             'product_id'   => $product->id,
    //             'quantity'     => $quantity,
    //             'unit_amount'  => $product->price,
    //             'total_amount' => $product->price * $quantity,
    //         ]);

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Product added to cart successfully!',
    //             'total_cart_items' => Cart::where('user_id', auth()->id())->count(),
    //         ]);
    //     }
    public function index()
    {
        $website_setting = website_settings::where('is_active',1)->first() ;
        $products = home_products::with('product')->orderBy('position','asc')->take(website_settings::where('is_active',1)->first()->home_products_count)->get() ;
        $ads = ads::where('is_active',1)->orderBy('position','asc')->take(website_settings::where('is_active',1)->first()->home_ads_count)->get() ;
        $brands = Brand::where('is_active', 1)->take(website_settings::where('is_active',1)->first()->home_brands_count)->get();
        $categories = Categories::where('is_active', 1)->take(website_settings::where('is_active',1)->first()->home_categories_count)->get();

        return response()->json([
            'status' => 'success',
            'brands' => $brands,
            'categories' => $categories,
            'website_sitting' => $website_setting ,
            'ads' => $ads ,
            'products' => $products ,
        ]);
    }
}
