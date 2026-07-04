<?php

use App\Models\Page;
use App\Models\Colors;
use App\Models\Product;
use App\Models\Category;
use App\Models\Variation;
use App\Models\ProductStocks;

if (!function_exists('user')) {

    /**
     * Return current logged in user
     */
    function user()
    {
        if (session()->has('user')) {
            return session('user');
        }

        $user = auth()->user();

        if ($user) {
            session(['user' => $user]);
            return session('user');
        }

        return null;
    }

}

if (!function_exists('order_status')) {
    function order_status($order_status = 'pending') {
        switch ($order_status) {
            case 'pending':
                return 'primary';
            case 'processing':
                return 'dark';
            case 'shipped':
                return 'warning';
            case 'delivered':
                return 'success';
            case 'canceled':
                return 'danger';
            default:
                return 'default';
        }
    }
}

if(!function_exists('admin_image')){
    function admin_image($image){
        if($image && file_exists(public_path('images/admin/' . $image))){
            return asset('images/admin/' . $image);
        }else{
            return asset('images/admin.jpg');
        }
    }
}

if(!function_exists('brand_image')){
    function brand_image($image){
        if($image && file_exists(public_path('images/brand/' . $image))){
            return asset('images/brand/' . $image);
        }else{
            return asset('images/brand.png');
        }
    }
}

if(!function_exists('category_image')){
    function category_image($image){
        if($image && file_exists(public_path('images/category/' . $image))){
            return asset('images/category/' . $image);
        }else{
            return asset('images/category.jpeg');
        }
    }
}
if(!function_exists('featured_categories')){
    function featured_categories() {
        $categories = Category::where(['is_menu_active'=>1, 'is_active'=>1])->orderBy('menu_position', 'ASC')->limit(8)->get();
        return $categories;
    }
}

if(!function_exists('all_cateegories')){
    function all_cateegories() {
        $all_categories = Category::orderBy('title', 'ASC')->get(['title', 'id']);
        return $all_categories;

    }
}

if(!function_exists('business_info')){
    function business_info() {
        $business = App\Models\Setting::find(1);
        return $business;
    }
}

if(!function_exists('color_info')){
    function color_info($id) {
        $info = Colors::find($id);
        return $info;
    }
}

if(!function_exists('variation_info')){
    function variation_info($id) {
        $info = Variation::find($id);
        return $info;
    }
}
if(!function_exists('single_variation_info')){
    function single_variation_info($variant_id, $product_id) {
        $info = ProductStocks::where('variant', $variant_id)->where('product_id', $product_id)->where('is_active', 1)->get(['id', 'variant', 'variant_output']);
        return $info;
    }
}
if(!function_exists('variation_stock_info')){
    function variation_stock_info($id) {
        $info = ProductStocks::find($id);
        return $info;
    }
}

if(!function_exists('other_pages')){
    function other_pages() {
        $info = Page::where('type','page')->get(['page_slug','name']);
        return $info;
    }
}

if(!function_exists('branch')){
    function branch() {
        $info = Page::where('type','branch')->get(['page_slug','name']);
        return $info;
    }
}

if (!function_exists('is_active_route')) {
    function is_active_route($routeName, $output = 'active') {
        return request()->routeIs($routeName) ? $output : '';
    }
}



if (!function_exists('get_product_display_data')) {
    function get_product_display_data(Product $product)
    {
        $stock_price = DB::table('product_stocks')
            ->where('product_id', $product->id)
            ->whereNull('variant')
            ->whereNull('color')
            ->first(['price', 'qty']);
        
        $data = [
            'sale_text' => '',
            'is_out_of_stock' => false,
            'current_price' => 0,
            'original_price' => 0,
            'price_range' => ['min' => 0, 'max' => 0], // Initialize with default values
            'show_options' => false
        ];

        // Discount text
        if ($product->discount_type == 'flat') {
            $data['sale_text'] = '-'.$product->discount_amount." ". env('CURRENCY');
        } elseif ($product->discount_type == 'percentage') {
            $data['sale_text'] = '-'.$product->discount_amount."%";
        }

        // Stock and pricing logic
        if ($product->type == 'single') {
            $data['is_out_of_stock'] = optional($stock_price)->qty <= 0;
            $data['current_price'] = optional($stock_price)->price ?? 0;
            
            if ($data['is_out_of_stock']) {
                $data['sale_text'] = 'Out of Stock';
            } elseif ($product->discount_type != 'no') {
                if ($product->discount_type == 'flat') {
                    $data['current_price'] -= $product->discount_amount;
                } else {
                    $discount = ($product->discount_amount * $data['current_price']) / 100;
                    $data['current_price'] -= $discount;
                }
                $data['original_price'] = optional($stock_price)->price ?? 0;
            }
        } else {
            // Variable product
            try {
                $variations = $product->variation_stock;
                
                if ($variations && $variations->isNotEmpty()) {
                    $min_price = $variations->min('price') ?? 0;
                    $max_price = $variations->max('price') ?? 0;

                    if ($product->discount_type == 'flat') {
                        $min_price -= $product->discount_amount;
                        $max_price -= $product->discount_amount;
                    } elseif ($product->discount_type == 'percentage') {
                        $discount = ($product->discount_amount * $min_price) / 100;
                        $min_price -= $discount;
                        $max_price -= $discount;
                    }

                    $data['price_range'] = [
                        'min' => max(0, $min_price), // Ensure price doesn't go negative
                        'max' => max(0, $max_price)
                    ];
                }
                $data['show_options'] = true;
            } catch (\Exception $e) {
                // Log error if needed
                $data['price_range'] = ['min' => 0, 'max' => 0];
            }
        }

        return $data;
    }
}




