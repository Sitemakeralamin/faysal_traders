<?php

namespace App\Http\Controllers;

use PDF;
use Auth;
use Cart;
use File;
use Mail;
use Alert;
use Image;
use Carbon\Carbon;
use App\Models\Blog;
use App\Models\Page;
use App\Models\User;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Colors;
use App\Models\Coupon;
use App\Models\Slider;
use App\Models\Wallet;
use App\Mail\OrderMail;
use App\Models\AboutUs;
use App\Models\Gallery;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use App\Models\District;
use App\Models\Wishlist;
use App\Mail\ContactMail;
use App\Models\Variation;
use App\Models\FilterHead;

use App\Models\Subscriber;
use App\Models\WalletEntry;
use Illuminate\Support\Str;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Models\ProductStocks;
use App\Models\FlashSaleOffer;
use App\Models\ProductsReviews;
use App\Models\SliderSideBanner;
use App\Models\VariationProduct;
use Illuminate\Support\Facades\DB;
use App\Models\ProductWithCategory;
use Illuminate\Support\Facades\Hash;
use Facade\FlareClient\Http\Response;
use App\Models\FlashSaleOfferProducts;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\BkashController;
use App\Http\Controllers\SslCommerzPaymentController;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $sliders = Slider::where('is_active', 1)->orderBy('serial_number', 'ASC')->get();
        $sliderSideBanner = SliderSideBanner::where('is_active', 1)->orderBy('serial_number', 'ASC')->take(2)->get();
        $brands = Brand::where(['is_featured' => 1, 'is_active' => 1])->orderBy('position', 'ASC')->latest()->get();
        $featured_categories = Category::where('is_active', 1)->where('is_featured', 1)->orderBy('position', 'ASC')->get(['id', 'slug', 'title', 'image', 'parent_id']);

        //recomanded product
        $recommendedProducts = Product::where('is_active', 1)->where('is_featured', 1)->latest()->take(10)->get();

        $recentProducts = Product::where('is_active', 1)
        ->orderBy('created_at', 'desc')
        ->get();

        $todaysDeal = Product::where('is_active', 1)->where('todays_deal', 1)
        ->with(['reviews' => function($query) {
        $query->where('is_active', 1);
        }])->latest()->first();

        $trendingProducts = Product::where('is_active', 1)->where('is_tranding', 1)->latest()->take(10)->get();
  

        // dd( $recommendedProducts, $todaysDeal,  $trendingProducts);
        return view('user.index', compact('brands', 'featured_categories', 'sliders', 'sliderSideBanner', 'recommendedProducts', 'todaysDeal', 'trendingProducts', 'recentProducts'));
    }

    public function products(Request $request)
    {
        $min_price = $request->min_price;
        $max_price = $request->max_price;

        $search = $request->search;
        $category_id = $request->category_id;
        $brand_id = $request->brand_id;

        $category = null;
          if ($category_id) {
                $category = Category::with('children')->find($category_id);
        }

        $filterHeads = FilterHead::with('options')->get();

        $categories = Category::where('parent_id', 0)->orderBy('position', 'ASC')->get();

        $brands = Brand::orderBy('position', 'ASC')->get();

        return view('user.pages.shop', compact('categories', 'category', 'filterHeads', 'brands', 'search', 'category_id', 'brand_id', 'min_price', 'max_price'));
    }

    public function shop_products_data(Request $request)
    {
        /* return response()->json([
            'brand' =>$request->brand_array,
        ]); */
        $lastID = $request->lastID;
        $search = $request->search;
        $category_id = $request->category_id;
        $brand_id = $request->brand_id;
        $brand_array = $request->brand_array;
        $min_price = $request->min_price;
        $max_price = $request->max_price;

        $output = '';

        // Base query
        $query = Product::query()->where('is_active', 1);

        // Filter by category
        if (!empty($category_id)) {
            $categories_id = $this->all_categories_ids($category_id);

            if (!empty($categories_id)) {
                $productWithCategoryIds = ProductWithCategory::whereIn('category_id', $categories_id)
                    ->pluck('product_id');
                // Log the product IDs
                //\Log::info("productWithCategoryIds: " . implode(",", $productWithCategoryIds->toArray()));

                if ($productWithCategoryIds->isNotEmpty()) {
                    $query = $query->whereIn('id', $productWithCategoryIds);
                } else {
                    // If no products found within price range, return empty result
                    return response()->json([
                        'output' => '<div class="col-md-12 col my-5" id="load_more" style="width: 100% !important;">
                                        <div style="text-align: center;" class="text-center"><h2><b>Data Not Found</b></h2></div>
                                    </div>'
                    ]);
                }
            }
        }

        //filter head
        if ($request->filled('filter_data_json')) {
            $filters = json_decode($request->filter_data_json, true);
            $filteredProductIds = null;

            foreach ($filters as $headId => $optionIds) {
                $productIdsByOptions = DB::table('product_filter_head_option')
                    ->whereIn('filter_head_option_id', $optionIds)
                    ->pluck('product_id')
                    ->toArray();

                // Early return if no matching products for current filter
                if (empty($productIdsByOptions)) {
                    return response()->json([
                        'output' => '<div class="col-md-12 col my-5" id="load_more" style="width: 100% !important;">
                                        <div class="text-center"><h2><b>Data Not Found</b></h2></div>
                                    </div>'
                    ]);
                }

                // First loop sets base result, next ones narrow it
                $filteredProductIds = is_null($filteredProductIds)
                    ? $productIdsByOptions
                    : array_intersect($filteredProductIds, $productIdsByOptions);

                // If nothing remains after intersection, stop
                if (empty($filteredProductIds)) {
                    return response()->json([
                        'output' => '<div class="col-md-12 col my-5" id="load_more" style="width: 100% !important;">
                                        <div class="text-center"><h2><b>Data Not Found</b></h2></div>
                                    </div>'
                    ]);
                }
            }


            if (!empty($filteredProductIds)) {
                $query = $query->whereIn('id', $filteredProductIds);
            }
        }



        if (!empty($brand_array)) {
            $brand_array = is_array($brand_array) ? $brand_array : explode(',', $brand_array);
            $query->whereIn('brand_id', $brand_array);
        } elseif (!empty($brand_id)) {
            $query->where('brand_id', $brand_id);
        }

        // if (!empty($brand_array)) {
        //     $brand_array = is_array($brand_array) ? $brand_array : explode(',', $brand_array);
        //     $query = $query->whereIn('brand_id', $brand_array)->orderBy('category_id');
        // }

        // Filter by price range
        if (!empty($min_price) && !empty($max_price)) {
            $min_price = (float) $min_price;
            $max_price = (float) $max_price;

            $productStockIds = ProductStocks::whereBetween('price', [$min_price, $max_price])
                ->groupBy('product_id')->orderBy('price', 'ASC')
                ->pluck('product_id');

            // Log the product IDs
            //\Log::info("productStockIds: " . implode(",", $productStockIds->toArray()));

            if ($productStockIds->isNotEmpty()) {
                $query = $query->whereIn('id', $productStockIds);
            } else {
                // If no products found within price range, return empty result
                return response()->json([
                    'output' => '<div class="col-md-12 col my-5" id="load_more" style="width: 100% !important;">
                                    <div style="text-align: center;" class="text-center"><h2><b>Data Not Found</b></h2></div>
                                 </div>'
                ]);
            }
        }

        // Filter by search term
        if (!empty($search)) {
            $query = $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        // Apply pagination
        /* if (!empty($lastID)) {
            $query = $query->where('id', '<', $lastID);
        } */

        // Fetch the products
        $products = $query->get(['id', 'slug', 'discount_type', 'call_for_price', 'discount_amount', 'type', 'title', 'thumbnail_image']);

        // Log the final query
        //\Log::info("Executed query: " . $query->toSql(), $query->getBindings());

        // Generate output
        if ($products->isNotEmpty()) {
            $displayedProducts = [];
            foreach ($products as $product) {
                if (!in_array($product->id, $displayedProducts)) {
                    $output .= view('user.partials.product', compact('product'))->render();
                    $displayedProducts[] = $product->id;
                }
            }
        } else {
            $output .= '<div class="col-md-12 col my-5" id="load_more" style="width: 100% !important;">
                            <div style="text-align: center;" class="text-center"><h2><b>Data Not Found</b></h2></div>
                        </div>';
        }

        return response()->json(['output' => $output]);
    }

    private function all_categories_ids($category_id = false)
    {
        $category_info = Category::find($category_id);
        $categories_id = array($category_id);
        if ($category_info) {
            if (count($category_info->child) > 0) {
                foreach ($category_info->child as $sub_category) {
                    if (count(optional($sub_category)->child) > 0) {
                        foreach ($sub_category->child as $sub_sub_category) {
                            array_push($categories_id, optional($sub_sub_category)->id);
                        }
                    } else {
                        array_push($categories_id, optional($sub_category)->id);
                    }
                }
            }
        }
        return $categories_id;
    }

    public function shop_products($slug)
    {
        $products = Product::where('is_active', 1)->orderBy('id', 'DESC')->select(['id', 'discount_type', 'type', 'title', 'thumbnail_image']);
        if ($slug == 'best-selling') {
            $products = $products->orderBy('sold_qty', 'DESC');
        } else if ($slug == 'featured') {
            $products = $products->where('is_featured', 1);
        } else if ($slug == 'traending-now') {
            $products = $products->where('is_tranding', 1);
        }


        $products = $products->paginate(60);
        return view('user.pages.group_wise_products', compact('products', 'slug'));
    }

    public function single_product($id, $slug)
    {
        $product = Product::find($id);
        if (!is_null($product)) {
            $similar_products = Product::where('category_id', $product->category_id)->where('id', '<>', $product->id)->inRandomOrder()->limit(10)->get();
            return view('user.pages.single-product', compact('product', 'similar_products'));
        } else {
            session()->flash('error', 'Page Not Found');
            return back();
        }
    }

    public function product_quick_view(Request $request)
    {
        $id = $request->pruduct_id;
        $product = Product::find($id);
        $output = '';
        $output .= view('user.partials.popup_proudcts_details', compact('product'))->render();
        return Response()->json($output);
    }

    public function product_variation_check(Request $request)
    {

        $color_info = $request->color_info;
        $color_id = $request->color;
        $product_id = $request->product_id;
        $color = '';
        $color_dependent_variation = '';
        $color_dependent_variation_status = 0;

        $image = '';
        $image_status = 0;
        $price_info = '';
        $variation_status = 0;

        $variation_name = '';
        $attribute_variation = $request->attribute_variation;

        $onlyColorstatus = 0;
        $checkMultiTypeVariation = ProductStocks::where('product_id', $product_id)->where('variant', '!=', NULL)->first(['id']);
        if (is_null($checkMultiTypeVariation)) {
            $onlyColorstatus = 1;
        }

        $info = ProductStocks::where('product_id', $product_id);

        if ($onlyColorstatus == 0) {
            $info = $info->where('id', $attribute_variation);
        }

        if ($color_info == 1) {
            $info = $info->where('color', $color_id);
        }

        $info = $info->where('is_active', 1);
        $info = $info->first();

        if (is_null($info) && $color_info == 1) {
            $color_dependent_variation_status = 1;
            $color = Colors::where('id', $color_id)->first()->name;
            $color_variation_info = ProductStocks::where('color', $color_id)->where('product_id', $product_id)->where('is_active', 1)->get();
            if (count($color_variation_info) > 0) {
                foreach ($color_variation_info as $variation) {
                    $color_dependent_variation .= '<input id="attribute_id_' . $variation->id . '" onchange="select_variation(' . $product_id . ')" value="' . $variation->id . '" name="attribute_variation" type="radio" ><label class="variant__size--value" for="attribute_id_' . $variation->id . '">' . $variation->variant_output . '</label>';
                }
            } else {
                $color_dependent_variation .= '';
            }
        }

        $product_info = Product::where('id', $product_id)->first(['unit_type', 'discount_type', 'discount_amount']);

        if (!is_null($info)) {
            $variation_name = $info->variant_output;
            $variation_status = 1;

            if ($product_info->discount_type <> 'no') {
                if ($product_info->discount_type == 'flat') {
                    $new_price = $info->price - optional($product_info)->discount_amount;
                } else if ($product_info->discount_type == 'percentage') {
                    $discount_amount_tk = (optional($product_info)->discount_amount * $info->price) / 100;
                    $new_price =  $info->price - $discount_amount_tk;
                }
                $price_info .= '<span class="current__price"> Price: <b>' . number_format($new_price) . '৳</b></span><span class="price__divided"></span>';
                $price_info .= '<span class="old__price"><b>' . number_format($info->price) . '৳</b></span>';
            } else {
                $price_info .= '<span class="current__price">Price:<b>' . number_format($info->price) . '৳</b></span>';
            }
        }

        $image = optional($info)->image;

        return response()->json([
            'color_name' => $color,
            'color_dependent_variation' => $color_dependent_variation,
            'color_dependent_variation_status' => $color_dependent_variation_status,
            'id' => optional($info)->id,
            'price' => optional($info)->price,
            'qty' => optional($info)->qty,
            'image_status' => $image_status,
            'image' => $image,
            'variation_name' => $variation_name,
            'variation_status' => $variation_status,
            'id' => optional($info)->id,
            'unit_type' => optional($product_info)->unit_type,
            'price_info' => $price_info,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('search');
        $filterResult = Product::where('title', 'LIKE', '%' . $query . '%')
            ->orWhere('description', 'LIKE', '%' . $query . '%')
            ->where('is_active', 1)
            ->pluck('title');
        return $filterResult;
    }

    public function ajax_product_search(Request $request)
    {
        $input = $request->input;
        $category_id = $request->category_id;
        $output = '';
        if ($category_id == '') {
            $products = Product::where('title', 'LIKE', '%' . $input . '%')->orWhere('description', 'LIKE', '%' . $input . '%')->limit(10)->get(['id', 'title', 'thumbnail_image', 'slug']);
        } else {
            $products = Product::where('category_id', $category_id)
                ->where(function ($query) use ($input) {
                    $query->where('title', 'LIKE', '%' . $input . '%')
                        ->orWhere('description', 'LIKE', '%' . $input . '%')
                        ->orWhere('meta_keywords', 'LIKE', '%' . $input . '%')
                        ->orWhere('meta_title', 'LIKE', '%' . $input . '%')
                        ->orWhere('meta_description', 'LIKE', '%' . $input . '%')
                        ->orWhere('specification', 'LIKE', '%' . $input . '%')
                    ;
                })
                ->limit(10)->get(['id', 'title', 'thumbnail_image']);
        }

        if ($input <> '') {
            if (count($products) > 0) {
                foreach ($products as $product) {
                    $output .= '<div class="col-md-12">
                                    <div class="shadow border m-2 rounded">
                                        <a class="widget__categories--sub__menu--link d-flex align-items-center" href="' . route('product.show', $product->slug) . '">
                                            <img style="width: 7.8rem !important;" class="widget__categories--sub__menu--img p-1 rounded" src="' . asset('images/product/' . $product->thumbnail_image) . '" alt="categories-img">
                                            <span class="widget__categories--sub__menu--text">' . $product->title . '</span>
                                        </a>
                                    </div>
                                </div>';
                }
            } else {
                $output .= '<div class="col-md-12">
                                <div class="m-2 rounded text-center">
                                    <h2 class="py-3 text-center">No Products Found!!!</h2>
                                    <a class="continue__shipping--btn primary__btn rounded-pill mb-3" href="' . route('products') . '">View Shop</a>
                                </div>
                            </div>';
            }
        } else {
        }

        return Response()->json($output);
    }

    public function ajax_featured_products()
    {
        $output = '';
        $featured_products = Product::where(['is_active' => 1, 'is_featured' => 1])->inRandomOrder()->limit(10)->get();
        $view = view('user.partials.ajax_featured_products', compact('featured_products'))->render();
        return Response()->json($view);
    }

    // public function ajax_recent_products(){
    //     $output = '';
    //     $featured_products = Product::where(['is_active' => 1])->latest()->limit(10)->get();
    //     $view = view('user.partials.ajax_featured_products', compact('featured_products'))->render();
    //     return Response()->json($view);
    // }

    public function ajax_trending_now()
    {
        $output = '';
        $trending_now = Product::where(['is_active' => 1, 'is_tranding' => 1])->inRandomOrder()->limit(10)->get();
        $view = view('user.partials.ajax_trending_now', compact('trending_now'))->render();
        return Response()->json($view);
    }

    public function ajax_todays_deal()
    {
        $output = '';
        $todays_deal = Product::where(['is_active' => 1, 'todays_deal' => 1])->inRandomOrder()->limit(10)->get();
        $view = view('user.partials.ajax_todays_deal', compact('todays_deal'))->render();
        return Response()->json($view);
    }

    public function ajax_best_selling_products()
    {
        $output = '';
        $best_selling_products = Product::orderBy('sold_qty', 'DESC')->where(['is_active' => 1])->limit(10)->get();
        $view = view('user.partials.ajax_best_selling_products', compact('best_selling_products'))->render();
        return Response()->json($view);
    }

    public function ajax_flash_sale_offer()
    {
        $flash_sale_offer = FlashSaleOffer::where(['is_active' => 1])->first();
        $view = view('user.partials.ajax_flash_sale_offer', compact('flash_sale_offer'))->render();
        return Response()->json($view);
    }

    public function ajax_flash_sale_offer_details($id, $slug)
    {
        $flash_sale_offer = FlashSaleOffer::find($id);
        if (is_null($flash_sale_offer)) {
            return Redirect()->back()->with('error', 'No Offer Found!');
        }

        $products = FlashSaleOfferProducts::where('flash_sale_id', $flash_sale_offer->id)->paginate(15);
        return view('user.pages.flash_sale_offer', compact('flash_sale_offer', 'products'));
    }


    public function product_filter(Request $request)
    {
        $category_id = $request->category_id;
        $brand_id = $request->brand_id;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        if ($category_id != 'all' && $brand_id != 'all') {
            $products = Product::where('category_id', $category_id)->where('brand_id', $brand_id)->whereBetween('price', [$min_price, $max_price])->where('is_active', 1)->get();
        } else if ($category_id != 'all' && $brand_id == 'all') {
            $products = Product::where('category_id', $category_id)->whereBetween('price', [$min_price, $max_price])->where('is_active', 1)->get();
        } else if ($category_id == 'all' && $brand_id != 'all') {
            $products = Product::where('brand_id', $brand_id)->whereBetween('price', [$min_price, $max_price])->where('is_active', 1)->get();
        } else {
            $products = Product::where('is_active', 1)->whereBetween('price', [$min_price, $max_price])->get();
        }

        $product_filtered = '';

        foreach ($products as $product) {
            $product_filtered .= '
                <div class="product-wrap product text-center" style="">
                    <div style="border: 1px solid blue;padding-bottom: 15px;margin: 0px 5px;">
                    <figure class="product-media">
                        <a href="' . route('product.show', $product->slug) . '">
                            <img src="' . asset('images/product/' . $product->image) . '" alt="Product"
                                width="216" height="243" />
                        </a>
                        <div class="product-action-vertical">
                            <a onclick="addToCart(' . $product->id . ')" class="btn-product-icon w-icon-cart peoduct_cart"
                                title="Add to cart"></a>
                            <a onclick="addToWishlist(' . $product->id . ')" class="btn-product-icon w-icon-heart peoduct_cart"
                                title="Add to wishlist"></a>

                        </div>
                    </figure>
                    <div class="product-details">
                        <h4 class="product-name"><a href="' . route('product.show', $product->slug) . '">' . $product->title . '</a>
                        </h4>
                        <p>' . $product->weight . $product->unit . '</p>
                        <div class="product-price">';
            if ($product->type == 'single') {
                if ($product->is_sale == 1) {
                    $product_filtered .= '<ins class="new-price">' . env('CURRENCY') .  $product->discount_price . env('UAE_CURRENCY') . '</ins><del class="old-price">' . env('CURRENCY') . $product->price . env('UAE_CURRENCY') . '</del>';
                } else {
                    $product_filtered .= '<ins class="new-price">' . env('CURRENCY') .  $product->price . env('UAE_CURRENCY') . '</ins>';
                }
            } else {
                if (count($product->variation) == 1) {

                    $product_filtered .= '<ins class="new-price">' . env('CURRENCY') . $product->variation->first()->price . env('UAE_CURRENCY') . '</ins>';
                } else {
                    $product_filtered .= '<ins class="new-price">' . env('CURRENCY') . $product->variation->where('price', $product->variation->min('price'))->first()->price . env('UAE_CURRENCY') . '-' .  env('CURRENCY') . $product->variation->where('price', $product->variation->max('price'))->first()->price  . env('UAE_CURRENCY') . '</ins>';
                }
            }
            $product_filtered .= '</div>
                        <button onclick="addToCart(' . $product->id . ')" class="btn btn-primary added_to_cart_' . $product->id;
            if (!is_null(Cart::content()->where('id', $product->id)->first())) {
                $product_filtered .= ' added_to_cart';
            } else {
                $product_filtered .= ' ';
            }

            $product_filtered .= '" id="">';
            if (!is_null(Cart::content()->where('id', $product->id)->first())) {
                $product_filtered .= 'Added To Cart';
            } else {
                $product_filtered .= 'Add to Cart';
            }
            $product_filtered .= '</button></div>
                    </div>
                </div>
                        ';
        }
        return ['product_filtered' => $product_filtered];
    }

    public function generate_product_filter() {}

    public function about()
    {
        $info = AboutUs::first();
        return view('user.pages.about_us', compact('info'));
    }

    public function contact()
    {
        return view('user.pages.contact-us');
    }

    public function send_message(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        //Mail::send(new ContactMail($request));

        session()->flash('success', 'Thank you for contacting us, we will be in touch within 24 to 48 hours');
        return redirect()->route('contact');
    }

    public function subscribe(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
        ]);

        $subscriber = Subscriber::where('email', $request->email)->first();
        if (is_null($subscriber)) {
            $subscriber = new Subscriber;
            $subscriber->email = $request->email;
            $subscriber->save();

            // Alert::success('Thanks, Welcome to our NEWSLETTER', '');
            return redirect()->back()->with('success', 'Thanks, Welcome to our NEWSLETTER');
        } else {
            // Alert::error('Thanks, You already subscribed us!', '');
            return redirect()->back()->with('success', 'Thanks, You already subscribed us!');
        }
    }

    public function my_orders()
    {
        if (Auth::check()) {
            $orders = Order::where('customer_id', Auth::id())->get();
            return view('user.pages.customer.orders', compact('orders'));
        } else {
            return redirect()->route('index');
        }
    }

    public function view_order($id)
    {
        if (Auth::check()) {
            $order = Order::where('code', $id)->first();
            if (!is_null($order)) {
                return $this->get_order_info($order->id);
            } else {
                return back()->with('error', 'Invalid order code!');
            }
        } else {
            return redirect()->route('index');
        }
    }

    public function my_wishlist()
    {
        if (Auth::check()) {
            $wishlists = Wishlist::where('customer_id', Auth::id())->get();
            return view('user.pages.customer.wishlist', compact('wishlists'));
        } else {
            return redirect()->route('login')->with('error', 'You are not logged in!');
        }
    }

    public function my_account()
    {
        if (Auth::check()) {
            if (user()->type == 1) {
                return redirect()->route('home');
            } else {
                $orders = Order::where('customer_id', Auth::id())->get();
                $wishlists = Wishlist::where('customer_id', Auth::id())->get();
                return view('user.pages.customer.account', compact('orders', 'wishlists'));
            }
        } else {
            return redirect()->route('index');
        }
    }

    public function customer_profile()
    {
        if (Auth::check()) {
            if (user()->type == 1) {
                return redirect()->route('home');
            } else {
                $user_info = user();
                $districts = District::orderBy('name', 'ASC')->get();
                return view('user.pages.customer.customer_profile', compact('user_info', 'districts'));
            }
        } else {
            return redirect()->route('index');
        }
    }

    public function customer_reviews()
    {
        if (Auth::check()) {
            if (user()->type == 1) {
                return redirect()->route('home');
            } else {
                $user_info = user();
                $orders = Order::where('customer_id', Auth::id())->where('order_status', 'delivered')->select(['code', 'id', 'created_at'])->paginate(10);
                return view('user.pages.customer.review_index', compact('user_info', 'orders'));
            }
        } else {
            return redirect()->route('index');
        }
    }

    public function write_reviews($order_product_order_id)
    {
        if (Auth::check()) {
            if (user()->type == 1) {
                return redirect()->route('home');
            } else {
                $user_info = user();
                $order_product_info = OrderProduct::find($order_product_order_id);
                if (is_null($order_product_info)) {
                    return redirect()->back()->with('error', 'Order Product info not found');
                }

                $order_info = Order::where('customer_id', Auth::id())->where('code', $order_product_info->order_code)->first();
                return view('user.pages.customer.write_review', compact('user_info', 'order_info', 'order_product_info'));
            }
        } else {
            return redirect()->route('index');
        }
    }

    public function customer_reviews_submit(Request $request)
    {

        if (Auth::check()) {
            if (user()->type == 1) {
                return redirect()->route('home');
            } else {
                $user_info = user();
                $order_product_info = OrderProduct::find($request->order_product_info_id);
                if (is_null($order_product_info)) {
                    return redirect()->back()->with('error', 'Order Product info not found');
                }

                $check_review = ProductsReviews::where(['order_product_id' => $order_product_info->id, 'order_code' => $order_product_info->order_code])->first(['id']);
                if (!is_null($check_review)) {
                    return redirect()->back()->with('error', 'Review is already Exist!');
                }

                $new_review = new ProductsReviews;
                $new_review->customer_id = $user_info->id;
                $new_review->order_code = $order_product_info->order_code;
                $new_review->order_product_id = $order_product_info->id;
                $new_review->product_id = $order_product_info->product_id;
                $new_review->review_text = $request->review_text;
                $new_review->is_active = 1;
                $new_review->created_at = now();
                $status = $new_review->save();

                if ($status) {
                    return Redirect()->route('customer.reviews')->with('success', 'Review Placed Successfully.');
                } else {
                    return redirect()->back()->with('error', 'Network Error! Please Try again.');
                }
            }
        } else {
            return redirect()->route('index');
        }
    }





    public function customer_account_update(Request $request, $id)
    {
        $customer = User::find($id);
        if (!is_null($customer)) {
            $customer->name = $request->name;
            $customer->phone = $request->phone;
            $customer->address = $request->address;

            // image save
            if ($request->image) {
                $image = $request->file('image');
                $img = time() . '.' . $image->getClientOriginalExtension();
                $location = public_path('images/customer/' . $img);
                Image::make($image)->save($location);
                $customer->image = $img;
            }

            // // NID save
            // if ($request->nid){
            //     $nid = $request->file('nid');
            //     $img = time() . '.' . $nid->getClientOriginalExtension();
            //     $location = public_path('images/customer/nid/'. $img);
            //     Image::make($nid)->save($location);
            //     $customer->nid = $img;
            // }

            $customer->save();
            return redirect()->back()->with('success', 'Profile Updated!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function change_password(Request $request)
    {
        $user = user();
        $c_password = $request->c_password;
        $n_password = $request->n_password;
        $cf_password = $request->cf_password;
        //dd(Hash::make($c_password));
        if (Hash::check($request->c_password, $user->password)) {
            if ($n_password == $cf_password) {
                $user->password = Hash::make($n_password);
                $user->save();
                Alert::success('Password has been updated', '');
                return back();
            } else {
                Alert::error('Password do not match !', '');
                return back();
            }
        } else {
            Alert::error('Your current password is wrong !', '');
            return back();
        }
    }

    public function coupone_store(Request $request)
    {
        if (Auth::check()) {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255',
                'discount' => 'required|numeric',
                'coupon_type' => 'required|string',
                'valid_to' => 'required|date',
            ]);
            $coupon = new Coupon;
            $coupon->name = $request->name;
            $coupon->code = $request->code;
            if ($request->coupon_type == 'percent') {
                $coupon->discount = $request->discount;
            }
            if ($request->coupon_type == 'flat') {
                $coupon->amount = $request->discount;
            }
            $coupon->valid_from = date('Y-m-d');
            $coupon->valid_to = $request->valid_to;
            if ($request->has('single_use')) {
                $coupon->single_use = 1;
            }
            $coupon->affiliate_id = Auth::id();

            $coupon->save();

            Alert::success('Coupon Added Successfully');
            return back();
        } else {
            return redirect()->route('index');
        }
    }

    public function coupone_update(Request $request, $id)
    {
        if (user()->type == 2) {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255',
                'discount' => 'required|numeric',
                'valid_to' => 'required|date',
            ]);
            $coupon = Coupon::find($id);
            if (!is_null($coupon)) {
                $coupon->name = $request->name;
                $coupon->code = $request->code;
                $coupon->discount = $request->discount;
                $coupon->valid_to = $request->valid_to;
                if ($request->coupon_type == 'percent') {
                    $coupon->discount = $request->discount;
                    $coupon->amount = NULL;
                }
                if ($request->coupon_type == 'flat') {
                    $coupon->amount = $request->discount;
                    $coupon->discount = NULL;
                }
                $coupon->save();

                Alert::success('Coupon updated Successfully');
                return back();
            } else {
                session()->flash('error', 'Something went wrong!');
                return back();
            }
        }
    }

    public function coupone_delete($id)
    {
        if (user()->type == 2) {
            $coupon = Coupon::find($id);
            if (!is_null($coupon)) {
                $coupon->delete();
                Alert::success('Coupon has been deleted');
                return back();
            } else {
                Alert::error('Something went wrong!');
                return back();
            }
        } else {
            Alert::error('Something went wrong!');
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        //
    }

    public function generateUniqueCode()
    {

        $characters = '0123456789';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < 6) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code . $character;
        }
        $code = date('y') . '-' . $code;

        if (Order::where('code', $code)->exists()) {
            $this->generateUniqueCode();
        }

        return $code;
    }

    public function generate_order_code()
    {
        $order_count = Order::count('id');
        $count_plus = $order_count + 1;
        return 'FT' . $count_plus;
    }

    public function order_create(Request $request)
    {

        $cart_info = Cart::content();

        if (count($cart_info) <= 0) {
            return Redirect()->route('products')->with('error', 'Your Cart is empty!');
        }

        $total = Cart::subtotal();
        $name = $request->name;
        $phone = $request->phone;
        $email = $request->email;

        $total_payable = $total;

        $order = new Order;
        $payment_type = $request->payment_type;

        $discount = 0;
        if (Session::has('coupon_discount')) {
            $order->coupon_status = 1;
            //$order->coupon_code = 1;
            $discount = Session::get('coupon_discount');
            $order->coupon_discount_amount = $discount;
        } else {
            $order->coupon_status = 0;
        }

        $district_id = $request->district_id;
        $district_info = District::find($district_id);
        $shipping_charge = 0;

        if (!is_null($district_info)) {
            $shipping_charge = $district_info->shipping_charge;
        }

        if ($payment_type == 'cod') {
            if (env('DELIVERY_CHARGE_ADVANCED') == true) {
                $total_payable = ($total) - $discount;
            } else {
                $total_payable = ($total + $shipping_charge) - $discount;
            }
        } else {
            $total_payable = ($total + $shipping_charge) - $discount;
        }

        $order_code = $this->generate_order_code();
        $order->code = $order_code;

        if (user()) {
            $order->customer_id = Auth::id();
        }
        $order->price = $total;
        $order->name = $name;
        $order->email = $email;
        $order->phone = $phone;
        $order->district_id = $request->district_id;
        $order->area_id = $request->area_id;
        $order->shipping_address = $request->shipping_address;
        $order->delivery_charge = $shipping_charge;
        $order->order_status = 'pending';
        $order->payment_status = 'unpaid';
        $order->total_payable = $total_payable;
        $order_status = $order->save();


        session()->put('order_code', $order_code);

        if ($order_status) {
            foreach (Cart::content() as $cart) {
                $order_product = new OrderProduct;
                $order_product->order_code = $order_code;
                $order_product->product_id = $cart->options->product_id;
                $order_product->variations = $cart->weight;
                $order_product->price = $cart->options->new_price;
                $order_product->qty = $cart->qty;
                $order_product->total = ($cart->options->new_price) * $cart->qty;
                $order_product->save();
            }

            if ($payment_type == 'cod') {
                $order->payment_method = 'cash on delivery';
                $order->save();
                /* DELIVERY_CHARGE_ADVANCED  */
                if (env('DELIVERY_CHARGE_ADVANCED') == true) {
                    if (env('BKASH') == true) {
                        session()->put('grand_total', $request->shipping_charge);
                        $bkashController = new BkashController();
                        return $bkashController->createPayment($request);
                    }
                }
            } else if ($payment_type == 'online') {
                if (env('SSlCOMMERZ') == true) {
                    $request->request->add(['tran_id' => $order_code, 'value_a' => 'order_payment']);
                    $sslcommerz = new SslCommerzPaymentController;
                    return $sslcommerz->index($request);
                }
            } else if ($payment_type == 'bkash') {
                if (env('BKASH') == true) {
                    session()->put('grand_total', $total_payable);
                    $order->payment_method = 'bkash';
                    $order->save();
                    // Instantiate BkashController
                    $bkashController = new BkashController();
                    return $bkashController->createPayment($request);
                }
            }
            if (config('app.email')) {
                if (!is_null($request->email)) {
                    Mail::send(new OrderMail($order));
                }
            }
            return redirect()->route('order.complete', $order->code);
        } else {
            return Redirect()->back()->with('error', 'Network Error!');
        }
    }


    public function order_complete($code)
    {
        $order = Order::where('code', $code)->first();
        if (!is_null($order)) {

            foreach (Cart::content() as $cart) {
                $product_id = $cart->options->product_id;
                $variation = $cart->weight;
                $qty = $cart->qty;

                $product = Product::find($product_id);
                if (!is_null($product)) {
                    if ($variation == 0) {
                        $stock_info = $product->single_stock;
                        $stock_info->qty -= $qty;
                        if ($stock_info->qty < 0) {
                            $stock_info->qty = 0;
                        }
                        $stock_info->save();
                    } else {
                        $stock_info = ProductStocks::find($variation);
                        if (!is_null($stock_info)) {
                            $stock_info->qty -= $qty;
                            if ($stock_info->qty < 0) {
                                $stock_info->qty = 0;
                            }
                            $stock_info->save();
                        }
                    }
                }

                //running

                Cart::remove($cart->rowId);
            }
            Session::forget('coupon_discount');

            if (config('app.sms')) {
                $phone = optional($order)->phone;
                $msg = 'Dear Sir/Madam, Your order(' . $order->code . ') has been Placed successfully. Thanks for shopping with us.';
                User::send_sms($phone, $msg);
            }
            return $this->get_order_info($order->id);
            //return view('user.pages.order_complete', compact('order'));

        } else {
            return back()->with('error', 'Invalid order code!');
        }
    }

    public function get_shipping_charge(Request $request)
    {
        $district_id = $request->district_id;
        $district_info = District::find($district_id);
        $shipping_charge = 0;
        $wallet_amount = 0;
        if (!is_null($district_info)) {
            $shipping_charge = $district_info->shipping_charge;
        }

        if (Auth::check()) {
            $wallet_amount = user()->wallet_amount;
        }

        return Response()->json([
            'shipping_charge' => $shipping_charge,
            'wallet_amount' => $wallet_amount,
        ]);
    }

    public function order_track()
    {
        return view('user.pages.track-order');
    }

    public function order_track_result(Request $request)
    {
        $code = $request->code;
        $order = Order::where('code', $code)->first();
        if (!is_null($order)) {
            return $this->get_order_info($order->id);
        } else {
            return back()->with('error', 'Invalid order code!');
        }
    }

    public function get_order_info($id)
    {
        $order = Order::find($id);
        if (!is_null($order)) {
            return view('user.pages.order_complete', compact('order'));
        } else {
            return back()->with('error', 'Invalid order code!');
        }
    }

    public function user_blog_index()
    {
        $blogs = Blog::orderBy('id', 'DESC')->select(['id', 'title', 'image', 'created_at'])->paginate(6);
        return view('user.pages.blog_index', compact('blogs'));
    }

    public function user_blog_details($id, $slug)
    {
        $blog_details = Blog::with('user')->where('id', $id)->first();
        if (is_null($blog_details)) {
            return Redirect()->back()->with('error', 'No News Found!');
        }

        return view('user.pages.blog_details', compact('blog_details'));
    }


    /**
     * category wise show product
     */
    public function getProductByCategory(Request $request, $slug)
    {
        // Find parent category by title (you may use slug later for better SEO)
        $category = Category::where('slug', $slug)->where('parent_id', 0)->firstOrFail();
        $min_price = $request->min_price;
        $max_price = $request->max_price;

        $search = $request->search;
        $category_id = $category->id;
        $brand_id = $request->brand_id;

        $mainCategoryName =  Str::ucfirst($slug);

        $categories = Category::where('parent_id', 0)->orderBy('position', 'ASC')->get();

        $brands = Brand::orderBy('position', 'ASC')->get();

        return view('user.pages.shop', compact('categories', 'brands', 'search', 'category_id', 'brand_id', 'min_price', 'max_price', 'mainCategoryName'));
    }


    public function getProductBySubCategory(Request $request, $main_slug, $sub_slug)
    {
        // Find parent category by title (you may use slug later for better SEO)
        $mainCatId = Category::where('slug', $main_slug)->where('parent_id', 0)->firstOrFail();

        $category = Category::where('slug', $sub_slug)->where('parent_id', $mainCatId->id)->firstOrFail();

        $min_price = $request->min_price;
        $max_price = $request->max_price;

        $search = $request->search;
        $category_id = $category->id;
        $brand_id = $request->brand_id;

        $mainCategoryName =  Str::ucfirst($main_slug);

        $subCategoryName =  Str::ucfirst($sub_slug);

        $categories = Category::where('parent_id', 0)->orderBy('position', 'ASC')->get();

        $brands = Brand::orderBy('position', 'ASC')->get();

        $filterHeads = $category->filterHeads()->with('options')->get();

        return view('user.pages.shop', compact('categories', 'brands', 'search', 'category_id', 'brand_id', 'min_price', 'max_price', 'mainCategoryName', 'subCategoryName', 'category', 'filterHeads'));
    }



    /**
     * Display products by child category with optional filters (brand, price range, search).
     *
     * This method validates a 3-level category structure: Main → Sub → Child.
     * It fetches the correct child category and passes the necessary data to the frontend view.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $main_slug    The slug of the main (top-level) category
     * @param string $sub_slug     The slug of the subcategory (child of main)
     * @param string $child_slug   The slug of the child category (child of subcategory)
     *
     * @return \Illuminate\View\View
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getProductByChildCategory(Request $request, $main_slug, $sub_slug, $child_slug)
    {
        // Step 1: Validate the main category (must be top-level, parent_id = 0)
        $mainCategory = Category::where('slug', $main_slug)
            ->where('parent_id', 0)
            ->firstOrFail();

        // Step 2: Validate the subcategory under the main category
        $subCategory = Category::where('slug', $sub_slug)
            ->where('parent_id', $mainCategory->id)
            ->firstOrFail();

        // Step 3: Validate the child category under the subcategory
        $category = Category::where('slug', $child_slug)
            ->where('parent_id', $subCategory->id)
            ->firstOrFail();

        // Step 4: Retrieve filter inputs from the request
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $search = $request->search;
        $brand_id = $request->brand_id;
        $category_id = $category->id;

        // Step 5: Prepare breadcrumb names (UI display purposes)
        $mainCategoryName = Str::ucfirst($main_slug);
        $subCategoryName = Str::ucfirst($sub_slug);
        $childCategoryName = Str::ucfirst($child_slug);

        // Step 6: Load necessary data for the view
        $categories = Category::where('parent_id', 0)
            ->orderBy('position', 'ASC')
            ->get();

        $brands = Brand::orderBy('position', 'ASC')->get();

        $filterHeads = $category->filterHeads()->with('options')->get();

        // Step 7: Return the shop view with all required variables
        return view('user.pages.shop', compact(
            'categories',
            'brands',
            'search',
            'category_id',
            'brand_id',
            'min_price',
            'max_price',
            'mainCategoryName',
            'subCategoryName',
            'childCategoryName',
            'category',
            'filterHeads'
        ));
    }



    public function showProduct(Request $request, $slug)
    {
        // Shared filter values
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $search = $request->search;
        $brand_id = null;
        $category_id = null;
        $category = null;

        // Shared dropdowns
        $categories = Category::where('parent_id', 0)->orderBy('position', 'ASC')->get();
        $brands = Brand::orderBy('position', 'ASC')->get();

        //brand
        if ($brand = Brand::where('slug', $slug)->first()) {
            $brand_id = $brand->id;      

            $brandName = Str::ucfirst($slug);
            
            $filterHeads = FilterHead::with('options')->get();

            return view('user.pages.shop', compact(
                'categories',
                'brands',
                'search',
                'category_id',
                'brand_id',
                'min_price',
                'max_price',
                'brandName',
                'category',
                'filterHeads'
            ));
        }


        //parent category
        if ($category = Category::where('slug', $slug)->where('parent_id', 0)->first()) {
            $category_id = $category->id;
            $mainCategoryName = Str::ucfirst($slug);

            $filterHeads = $category->filterHeads()->with('options')->get();

            return view('user.pages.shop', compact(
                'categories',
                'brands',
                'search',
                'category_id',
                'brand_id',
                'min_price',
                'max_price',
                'mainCategoryName',
                'category',
                'filterHeads'
            ));
        }

        //product
        if ($product = Product::where('slug', $slug)->with(['reviews' => function($query) {
        $query->where('is_active', 1);
        }])->first()) {
            $similar_products = Product::where('category_id', $product->category_id)
                ->where('id', '<>', $product->id)
                ->inRandomOrder()
                ->limit(10)
                ->get();


            $productCat = Category::find($product->category_id);

            $parentCategory = null;
            $subCategory = null;
            $childCategory = null;

            /* Thow Step Category */
            /*  if ($productCat) {
                if ($productCat->parent_id == 0) {
                    $parentCategory = $productCat;
                } else {
                    $subCategory = $productCat;
                    $parentCategory = Category::find($subCategory->parent_id);
                }
            } */

            /* Three Step Category */
            if ($productCat) {
                if ($productCat->parent_id == 0) {
                    // It's a top-level (parent) category
                    $parentCategory = $productCat;
                } else {
                    $parent = Category::find($productCat->parent_id);

                    if ($parent && $parent->parent_id == 0) {
                        // It's a sub-category
                        $parentCategory = $parent;
                        $subCategory = $productCat;
                    } else {
                        // It's a child-category
                        $parentCategory = Category::find($parent->parent_id);
                        $subCategory = $parent;
                        $childCategory = $productCat;
                    }
                }
            }

            return view('user.pages.single-product', compact(
                'product',
                'similar_products',
                'productCat',
                'parentCategory',
                'subCategory',
                'childCategory',
            ));
        }

        //Page
        if ($page_info = Page::where('page_slug', $slug)->first()) {
            return view('user.pages.others_page', compact('page_info'));
        }

        //Nothing matched
        abort(404);
    }
}
