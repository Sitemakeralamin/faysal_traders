<?php

use App\Models\Product;
use App\Models\Category;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WholesaleController;
use App\Http\Controllers\SslCommerzPaymentController;

Route::get('/invoice', function () {
	return view('admin.invoice.generate');
});

Route::get('/', [App\Http\Controllers\PageController::class, 'index'])->name('index');
//Wholesale Controller
Route::get('/wholesale', [App\Http\Controllers\WholesaleController::class, 'index'])->name('wholesale');
Route::post('/wholesale/store', [App\Http\Controllers\WholesaleController::class, 'store'])->name('wholesale.store');

// SSLCOMMERZ Start
Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

Route::post('/pay', [SslCommerzPaymentController::class, 'index']);
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);


//SSLCOMMERZ END
Route::get('/product_quick_view', [App\Http\Controllers\PageController::class, 'product_quick_view'])->name('product.quick.view');
Route::get('/ajax_product_search', [App\Http\Controllers\PageController::class, 'ajax_product_search'])->name('ajax.product.search');
Route::get('/ajax_cart_qty_update', [App\Http\Controllers\CartController::class, 'update_cart'])->name('ajax.cart.qty.update');
Route::get('/ajax_featured_products', [App\Http\Controllers\PageController::class, 'ajax_featured_products'])->name('ajax.featured.products');
// Route::get('/ajax_recentd_products', [App\Http\Controllers\PageController::class, 'ajax_recent_products'])->name('ajax.recent.products');
Route::get('/ajax_trending_now', [App\Http\Controllers\PageController::class, 'ajax_trending_now'])->name('ajax.trending_now');
Route::get('/ajax_todays_deal', [App\Http\Controllers\PageController::class, 'ajax_todays_deal'])->name('ajax.todays_deal');
Route::get('/ajax_best_selling_products', [App\Http\Controllers\PageController::class, 'ajax_best_selling_products'])->name('ajax.best.selling.products');
Route::get('/ajax_flash_sale_offer', [App\Http\Controllers\PageController::class, 'ajax_flash_sale_offer'])->name('ajax.flash.sale.offer');
Route::get('/flash-sale-offer/{id}/{slug}', [App\Http\Controllers\PageController::class, 'ajax_flash_sale_offer_details'])->name('flash.sale.offer.details');


/* Shop */
Route::get('/shop', [App\Http\Controllers\PageController::class, 'products'])->name('products');

//nayem add 2025-30-04
Route::get('categories/{slug}', [App\Http\Controllers\PageController::class, 'getProductByCategory'])->name('products.category');



Route::post('/shop_products_data', [App\Http\Controllers\PageController::class, 'shop_products_data'])->name('shop.products.data');
Route::get('/shop/{slug}', [App\Http\Controllers\PageController::class, 'shop_products'])->name('products.individual.group');
/* Single Product */
Route::get('/product/{id}/{slug}', [App\Http\Controllers\PageController::class, 'single_product'])->name('single.product');
Route::post('/product/variation_check', [App\Http\Controllers\PageController::class, 'product_variation_check'])->name('single.product.variation.check');
Route::get('/product/ajax_load_cart_data', [App\Http\Controllers\CartController::class, 'ajax_load_cart_data'])->name('ajax.load.cart.data');

Route::get('/about-us', [App\Http\Controllers\PageController::class, 'about'])->name('about');
Route::get('/contact-us', [App\Http\Controllers\PageController::class, 'contact'])->name('contact');
Route::post('/contact-us-message-send', [App\Http\Controllers\PageController::class, 'send_message'])->name('message.send');

Route::get('/search', [App\Http\Controllers\PageController::class, 'search'])->name('search');
Route::post('/subsribe', [App\Http\Controllers\PageController::class, 'subscribe'])->name('subscribe');

// Cart Route
Route::get('/shopping-carts', [App\Http\Controllers\CartController::class, 'index'])->name('carts');
Route::post('/add-to-cart', [App\Http\Controllers\CartController::class, 'add_cart'])->name('cart.add');
Route::post('/update-cart', [App\Http\Controllers\CartController::class, 'update_cart'])->name('cart.update');
Route::post('/remove-from-cart', [App\Http\Controllers\CartController::class, 'remove_cart'])->name('cart.remove');
Route::get('/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('checkout');

// Wishlist
Route::post('/add-to-wishlist', [App\Http\Controllers\WishlistController::class, 'add_wishlist'])->name('wishlist.add');
Route::post('/remove-from-wishlist/{id}', [App\Http\Controllers\WishlistController::class, 'remove_wishlist'])->name('wishlist.remove');

// Coupon Routes
Route::post('/apply-coupon', [App\Http\Controllers\CartController::class, 'apply_coupon'])->name('coupon.apply');
Route::get('/remove-coupon', [App\Http\Controllers\CartController::class, 'remove_coupon'])->name('coupon.remove');

// Order routes
Route::post('/order-create', [App\Http\Controllers\PageController::class, 'order_create'])->name('order.create');
Route::get('/order-complete/{code}', [App\Http\Controllers\PageController::class, 'order_complete'])->name('order.complete');
Route::get('/track-order', [App\Http\Controllers\PageController::class, 'order_track'])->name('order.track');
Route::get('/track-order-status', [App\Http\Controllers\PageController::class, 'order_track_result'])->name('order.track.result');

// Customer Profile
Route::get('/order/generate-invoice/{id}', [App\Http\Controllers\OrderController::class, 'generate_invoice'])->name('order.invoice.generate');

Route::get('/order/{id}', [App\Http\Controllers\PageController::class, 'view_order'])->name('order.view');
Route::get('/my-orders', [App\Http\Controllers\PageController::class, 'my_orders'])->name('customer.orders');
Route::get('/my-wishlist', [App\Http\Controllers\PageController::class, 'my_wishlist'])->name('customer.wishlist');
Route::get('/my-account', [App\Http\Controllers\PageController::class, 'my_account'])->name('customer.account');
Route::get('/my-profile', [App\Http\Controllers\PageController::class, 'customer_profile'])->name('customer.profile');
Route::get('/my-reviews', [App\Http\Controllers\PageController::class, 'customer_reviews'])->name('customer.reviews');
Route::post('/customer/my_reviews_submit', [App\Http\Controllers\PageController::class, 'customer_reviews_submit'])->name('customer.reviews.submit');

Route::get('/write-reviews/{order_product_order_id}', [App\Http\Controllers\PageController::class, 'write_reviews'])->name('write.review');


Route::post('/customer-account-update/{id}', [App\Http\Controllers\PageController::class, 'customer_account_update'])->name('customer.account.update');
Route::post('/customer-password-change', [App\Http\Controllers\PageController::class, 'change_password'])->name('customer.password.change');

// Affiliate Coupon Route
Route::post('/affiliate-coupon-store', [App\Http\Controllers\PageController::class, 'coupone_store'])->name('customer.coupon.store');
Route::post('/affiliate-coupon-update/{id}', [App\Http\Controllers\PageController::class, 'coupone_update'])->name('customer.coupon.update');
Route::post('/affiliate-coupon-delete/{id}', [App\Http\Controllers\PageController::class, 'coupone_delete'])->name('customer.coupon.destroy');
/* blog */
Route::get('/latest-news', [App\Http\Controllers\PageController::class, 'user_blog_index'])->name('user.blog');
Route::get('/latest-news/{id}/{slug}', [App\Http\Controllers\PageController::class, 'user_blog_details'])->name('user.blog.details');

Auth::routes();

Route::post('/custom-register', [App\Http\Controllers\AdminController::class, 'custom_registration'])->name('custom.register');
Route::post('/custom-login', [App\Http\Controllers\AdminController::class, 'custom_login'])->name('custom.login');

Route::get('/verify-account', [App\Http\Controllers\AdminController::class, 'auth_phone_verify'])->name('auth.phone.verify');
Route::post('/verify_phone', [App\Http\Controllers\AdminController::class, 'verify_user_phone'])->name('verify.phone');

Route::get('/resend_verification_code', [App\Http\Controllers\AdminController::class, 'resend_verification_code'])->name('resend.verification.code');

Route::get('/forgot_password_send_otp', [App\Http\Controllers\AdminController::class, 'forgot_password_send_otp'])->name('forgot.pass.send.otp');
Route::post('/password_reset_confirm', [App\Http\Controllers\AdminController::class, 'password_reset_confirm'])->name('pass.reset.confirm');

//bkash  Checkout (URL) User Part 
Route::get('/bkash/pay', [App\Http\Controllers\BkashController::class, 'payment'])->name('url-pay');
Route::post('/bkash/create', [App\Http\Controllers\BkashController::class, 'createPayment'])->name('url-create');
Route::get('/bkash/callback', [App\Http\Controllers\BkashController::class, 'callback'])->name('url-callback');
// Checkout (URL) Admin Part

Route::get('/bkash/refund', [App\Http\Controllers\BkashController::class, 'getRefund'])->name('url-get-refund');
Route::post('/bkash/refund', [App\Http\Controllers\BkashController::class, 'refundPayment'])->name('url-post-refund');
// end bkash

// API Routes
Route::get('get-sub-category/{id}', function ($id) {
	return json_encode(App\Models\Category::where('parent_id', $id)->where('is_active', 1)->get());
});
Route::post('/product-filter', [App\Http\Controllers\PageController::class, 'product_filter'])->name('product.filter');

Route::get('get-area/{id}', function ($id) {
	return json_encode(App\Models\Area::where('district_id', $id)->get());
});

Route::get('/get-shipping-charge', [App\Http\Controllers\PageController::class, 'get_shipping_charge'])->name('shipping_charge.get');
// API Routes End


// Admin Routes
Route::group(['prefix' => '/home', 'middleware' => ['auth', 'verified', 'adminAuth']], function () {
	Route::get('/all', [App\Http\Controllers\WholesaleController::class, 'view'])->name('allOrder');
	Route::POST('/wholeorder/delete/{id}', [App\Http\Controllers\WholesaleController::class, 'destroy'])->name('wholeseal.order.destroy');
	Route::get('', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

	// Admin Routes
	Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
		Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\AdminController::class, 'create'])->name('create');
		Route::post('/store', [App\Http\Controllers\AdminController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\AdminController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\AdminController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\AdminController::class, 'destroy'])->name('destroy');
	});

	// About us setting Routes
	Route::group(['prefix' => 'about-us-settings', 'as' => 'about_us.'], function () {
		Route::get('/index', [App\Http\Controllers\AdminController::class, 'about_us_setting_index'])->name('index');
		Route::post('/store', [App\Http\Controllers\AdminController::class, 'about_us_setting_store'])->name('store');
	});


	// Admin Routes
	Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
		Route::get('/', [App\Http\Controllers\AdminController::class, 'customer_index'])->name('index');
		Route::post('/destroy/{id}', [App\Http\Controllers\AdminController::class, 'customer_destroy'])->name('destroy');
	});

	// Category Routes
	Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
		Route::get('/', [App\Http\Controllers\CategoryController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\CategoryController::class, 'create'])->name('create');
		Route::post('/stote', [App\Http\Controllers\CategoryController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\CategoryController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\CategoryController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('destroy');
	});


	// Brand Routes
	Route::group(['prefix' => 'brand', 'as' => 'brand.'], function () {
		Route::get('/', [App\Http\Controllers\BrandController::class, 'index'])->name('index');
		Route::post('/stote', [App\Http\Controllers\BrandController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\BrandController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\BrandController::class, 'destroy'])->name('destroy');
	});

	// Color Routes
	Route::group(['prefix' => 'color', 'as' => 'color.'], function () {
		Route::get('/', [App\Http\Controllers\ProductController::class, 'color_index'])->name('index');
		Route::post('/stote', [App\Http\Controllers\ProductController::class, 'color_store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\ProductController::class, 'color_edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\ProductController::class, 'color_update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\ProductController::class, 'color_destroy'])->name('destroy');
	});



	// Variation Routes
	Route::group(['prefix' => 'variation', 'as' => 'variation.'], function () {
		Route::get('/', [App\Http\Controllers\VariationController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\VariationController::class, 'create'])->name('create');
		Route::post('/stote', [App\Http\Controllers\VariationController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\VariationController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\VariationController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\VariationController::class, 'destroy'])->name('destroy');
	});

	// Product Routes
	Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
		Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\ProductController::class, 'create'])->name('create');
		Route::post('/stote', [App\Http\Controllers\ProductController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('destroy');
		Route::get('/stock', [App\Http\Controllers\ProductController::class, 'product_stock'])->name('stock');
		Route::post('/stock_qty_update', [App\Http\Controllers\ProductController::class, 'stock_qty_update'])->name('stock.qty.update');

		//variations
		Route::post('/generate/variation', [App\Http\Controllers\ProductController::class, 'generate_variation'])->name('generate.variation');

		//filter head
		Route::get('/generate/head', [App\Http\Controllers\ProductController::class, 'filterHeadIndex'])->name('generate.filter.head');

		Route::post('/generate/head', [App\Http\Controllers\ProductController::class, 'storeFilterHead'])->name('head.store');

		Route::post('/generate/head/options', [App\Http\Controllers\ProductController::class, 'storeFilterHeadOptions'])->name('head.options.store');

		Route::delete('/filter-head-option/{id}', [App\Http\Controllers\ProductController::class, 'destroyFilterHeadOption'])->name('filter.head.option.delete');

	});

	// Flash Sell Routes
	Route::group(['prefix' => 'flash-sale', 'as' => 'flash.sale.'], function () {
		Route::get('/', [App\Http\Controllers\FlashSaleOfferController::class, 'index'])->name('index');

		Route::get('/create', [App\Http\Controllers\FlashSaleOfferController::class, 'create'])->name('create');
		Route::get('/search_product', [App\Http\Controllers\FlashSaleOfferController::class, 'search_product'])->name('search.product');
		Route::post('/stote', [App\Http\Controllers\FlashSaleOfferController::class, 'store'])->name('store');

		Route::get('/edit/{id}', [App\Http\Controllers\FlashSaleOfferController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\FlashSaleOfferController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\FlashSaleOfferController::class, 'destroy'])->name('destroy');
	});

	// Order Routes
	Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
		Route::get('/', [App\Http\Controllers\OrderController::class, 'index'])->name('index');
		Route::get('/status/{id}', [App\Http\Controllers\OrderController::class, 'orders_by_status'])->name('status.filter');
		Route::post('/stote', [App\Http\Controllers\OrderController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\OrderController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\OrderController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\OrderController::class, 'destroy'])->name('destroy');

		Route::post('/change-status/{id}', [App\Http\Controllers\OrderController::class, 'change_status'])->name('status.change');
		Route::post('/change-payment-status/{id}', [App\Http\Controllers\OrderController::class, 'change_payment_status'])->name('payment.status.change');
		// Invoice route

		// Report routes
		Route::get('/current-year', [App\Http\Controllers\OrderController::class, 'current_year'])->name('current.year');
		Route::get('/current-month', [App\Http\Controllers\OrderController::class, 'current_month'])->name('current.month');
		Route::get('/today', [App\Http\Controllers\OrderController::class, 'today'])->name('today');
		Route::get('/search', [App\Http\Controllers\OrderController::class, 'search'])->name('search');
	});

	// Products Reviews Routes
	Route::group(['prefix' => 'product-review', 'as' => 'product.review.'], function () {
		Route::get('/', [App\Http\Controllers\ProductsReviewsController::class, 'index'])->name('index');
		Route::get('/edit/{id}', [App\Http\Controllers\ProductsReviewsController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\ProductsReviewsController::class, 'update'])->name('update');
	});

	// Coupone Routes
	Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
		Route::get('/', [App\Http\Controllers\CouponController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\CouponController::class, 'create'])->name('create');
		Route::post('/stote', [App\Http\Controllers\CouponController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\CouponController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\CouponController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\CouponController::class, 'destroy'])->name('destroy');
	});

	// RegistrationPoint Routes
	Route::group(['prefix' => 'registration-point', 'as' => 'registration.point.'], function () {
		Route::get('/', [App\Http\Controllers\RegistrationPointController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\RegistrationPointController::class, 'create'])->name('create');
		Route::post('/stote', [App\Http\Controllers\RegistrationPointController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\RegistrationPointController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\RegistrationPointController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\RegistrationPointController::class, 'destroy'])->name('destroy');
	});

	// Slider Routes
	Route::group(['prefix' => 'slider', 'as' => 'slider.'], function () {
		Route::get('/', [App\Http\Controllers\SliderController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\SliderController::class, 'create'])->name('create');
		Route::post('/stote', [App\Http\Controllers\SliderController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\SliderController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\SliderController::class, 'update'])->name('update');
		Route::get('/destroy/{id}', [App\Http\Controllers\SliderController::class, 'destroy'])->name('destroy');
	});

	// Slider Side Banner Routes
	Route::group(['prefix' => 'slider_side_banner', 'as' => 'slider_side_banner.'], function () {
		Route::get('/', [App\Http\Controllers\SliderSideBannerController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\SliderSideBannerController::class, 'create'])->name('create');
		Route::post('/stote', [App\Http\Controllers\SliderSideBannerController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\SliderSideBannerController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\SliderSideBannerController::class, 'update'])->name('update');
		Route::get('/destroy/{id}', [App\Http\Controllers\SliderSideBannerController::class, 'destroy'])->name('destroy');
	});

	// 4 banner into home page
	Route::group(['prefix' => 'home-page-banner', 'as' => 'f.banner.'], function () {
		Route::get('/', [App\Http\Controllers\SliderController::class, 'home_page_four_banner_show'])->name('show');
		Route::post('/update', [App\Http\Controllers\SliderController::class, 'home_page_four_banner_update'])->name('update');
	});

	// Pages in Admin
	Route::group(['prefix' => 'page', 'as' => 'page.'], function () {

		Route::get('/', [App\Http\Controllers\AdminPageController::class, 'index'])->name('index');
		Route::get('/edit/{id}', [App\Http\Controllers\AdminPageController::class, 'edit'])->name('edit');
		Route::post('/store', [App\Http\Controllers\AdminPageController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\AdminPageController::class, 'update'])->name('update');
	});

	// Setting Routes
	Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
		Route::get('/', [App\Http\Controllers\SettingController::class, 'index'])->name('index');
		Route::post('/update', [App\Http\Controllers\SettingController::class, 'update'])->name('update');
		Route::get('/reward-point', [App\Http\Controllers\SettingController::class, 'reward_point'])->name('reward.point');
		Route::post('/reward-point/update/{id}', [App\Http\Controllers\SettingController::class, 'reward_point_update'])->name('reward.point.update');
	});

	// Affiliate Routes
	Route::group(['prefix' => 'affiliate', 'as' => 'affiliate.'], function () {
		Route::get('/configuration', [App\Http\Controllers\SettingController::class, 'config'])->name('config');
		Route::post('/config/update/{id}', [App\Http\Controllers\SettingController::class, 'config_update'])->name('config.update');
		Route::get('/request', [App\Http\Controllers\SettingController::class, 'affiliate_request'])->name('request');
		Route::get('/status/{id}/{status}', [App\Http\Controllers\SettingController::class, 'affiliate_status'])->name('status');
		Route::get('/payment-request', [App\Http\Controllers\PaymentController::class, 'payment_request'])->name('payment.request');
		Route::post('/payment-transfer/{id}', [App\Http\Controllers\PaymentController::class, 'payment_transfer'])->name('payment.transfer');
		Route::post('/payment-reject/{id}', [App\Http\Controllers\PaymentController::class, 'payment_reject'])->name('payment.reject');
	});

	// Referral Link Generate
	Route::group(['prefix' => 'referral-link', 'as' => 'referral.link.'], function () {
		Route::get('/', [App\Http\Controllers\SettingController::class, 'referral_link'])->name('index');
	});

	// Profile Routes
	Route::group(['prefix' => 'profile', 'as' => 'user.'], function () {

		Route::get('/', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
		Route::post('/update', [App\Http\Controllers\ProfileController::class, 'profile_update'])->name('profile.update');
		Route::post('/change-password', [App\Http\Controllers\ProfileController::class, 'change_password'])->name('password.change');
	});

	//Subscribers in admin
	Route::get('/subscribers', [App\Http\Controllers\SubscriberController::class, 'index'])->name('admin.subscribers');

	// Gallery Routes
	Route::group(['prefix' => 'gallery', 'as' => 'gallery.'], function () {
		Route::get('/', [App\Http\Controllers\GalleryController::class, 'index'])->name('index');
		Route::post('/stote', [App\Http\Controllers\GalleryController::class, 'store'])->name('store');
		Route::post('/destroy/{id}', [App\Http\Controllers\GalleryController::class, 'destroy'])->name('destroy');
	});

	// District Routes
	Route::group(['prefix' => 'district', 'as' => 'district.'], function () {
		Route::get('/', [App\Http\Controllers\DistrictController::class, 'index'])->name('index');
		Route::post('/stote', [App\Http\Controllers\DistrictController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\DistrictController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\DistrictController::class, 'destroy'])->name('destroy');
	});

	// Area Routes
	Route::group(['prefix' => 'area', 'as' => 'area.'], function () {
		Route::get('/', [App\Http\Controllers\AreaController::class, 'index'])->name('index');
		Route::post('/stote', [App\Http\Controllers\AreaController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\AreaController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\AreaController::class, 'destroy'])->name('destroy');
	});

	// blog Routes
	Route::group(['prefix' => 'blog', 'as' => 'blog.'], function () {
		Route::get('/create', [App\Http\Controllers\BlogController::class, 'index'])->name('create');
		Route::post('/store', [App\Http\Controllers\BlogController::class, 'store'])->name('store');
		Route::get('/list', [App\Http\Controllers\BlogController::class, 'list'])->name('list');
		Route::get('/edit/{id}', [App\Http\Controllers\BlogController::class, 'edit'])->name('edit');
		Route::post('/destroy/{id}', [App\Http\Controllers\BlogController::class, 'destroy'])->name('destroy');
		Route::post('/update/{id}', [App\Http\Controllers\BlogController::class, 'update'])->name('update');
	});
});


Route::get('/s.xml', function () {
    // Create sitemap
    $sitemap = Sitemap::create();

    // Add homepage
	$sitemap->add(Url::create(url('/'))->setPriority(1.0));

    // Add products
    $products = Product::all();
    foreach ($products as $product) {
        $sitemap->add(Url::create(route('product.show', $product->slug)));
    }

    // Add categories and subcategories (uncomment if needed)
	$categories = Category::with('menu_child')->get();
	foreach ($categories as $category) {
		// Add the parent category URL
		$sitemap->add(Url::create(route('products.category', $category->slug)));
	
		// Add subcategory URLs if they exist
		foreach ($category->menu_child as $subCategory) {
			$sitemap->add(
				Url::create(
					route('products.sub.category', [
						'main_cat' => $category->slug,
						'sub_cat' => $subCategory->slug,
					])
				)
			);
		}
	}

    // Return XML response
    return response($sitemap->render(), 200)
        ->header('Content-Type', 'text/xml');
});

//sub category
Route::get('/{main_cat}/{sub_cat}', [App\Http\Controllers\PageController::class, 'getProductBySubCategory'])->name('products.sub.category');

//child category category
Route::get('/{main_cat}/{sub_cat}/{child_slug}', [App\Http\Controllers\PageController::class, 'getProductByChildCategory'])->name('products.child.category');


//main category, product, brand
Route::get('/{slug}', [App\Http\Controllers\PageController::class, 'showProduct'])->name('product.show');

