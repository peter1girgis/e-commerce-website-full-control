<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\ads;
use App\Models\Brand;
use App\Models\Categories;
use App\Models\home_products;
use App\Models\Product;
use App\Models\website_settings;
use Jantinnerezo\LivewireAlert\Concerns\SweetAlert2;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
#[Title('HOME - PeterStore')]
class HomePage extends Component
{
    use WithPagination ;
    use SweetAlert2;
    public function getComponentProperty()
    {
        return $this;
    }
    public function addToCart($product_id){
        $total_count = CartManagement::addItemToCart($product_id);
        $this->dispatch('update-cart-count' , total_count: $total_count)->to(Navbar::class);
        $this->alert([
            'type' => 'success',
            'title' => 'Product added to cart successfully!',
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
            'iconColor' => '#74fc86',
            'background' => '#ebfaed',
            'icon' => 'success',
            'showConfirmButton' => false,
            'showCloseButton' => false,
            // 'showClass' => [
            //     'popup' => 'animate__animated animate__fadeInDown',
            // ],
            // 'hideClass' => [
            //     'popup' => 'animate__animated animate__fadeOutUp',
            // ],
        ]);
        // CartManagement::clearCartItemsFromCookie();
    }
    public function render()
    {

        return view('livewire.home-page',[
            'websiteSetting' => website_settings::where('is_active',1)->first(),
            'products' => home_products::with('product')->orderBy('position','asc')->take(website_settings::where('is_active',1)->first()->home_products_count)->get(),
            'ads' => ads::where('is_active',1)->orderBy('position','asc')->take(website_settings::where('is_active',1)->first()->home_ads_count)->get(),
            'brands' => Brand::where('is_active',1)->take(website_settings::where('is_active',1)->first()->home_brands_count)->get(),
            'categories' => Categories::where('is_active',1)->take(website_settings::where('is_active',1)->first()->home_categories_count)->get(),
        ]);
    }
}
