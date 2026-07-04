@if(!empty($product))
@php
    //$stock_price = $product->single_stock;
    $stock_price = DB::table('product_stocks')->where('product_id', $product->id)->where('variant', '=', null)->where('color', '=', null)->first(['price', 'qty']);
    $sale_text = '';

    if($product->discount_type <> 'no') {
        if($product->discount_type == 'flat') {
            $sale_text = '-'.optional($product)->discount_amount." TK";
        }
        else if($product->discount_type == 'percentage') {
            $sale_text = '-'.optional($product)->discount_amount."%";
        }
    }

    if($product->type == 'single' && optional($stock_price)->qty <= 0) {
        $sale_text = 'Out of Stock';
    }
    else {
        $stock_price = DB::table('product_stocks')->where('product_id', $product->id)->first(['price', 'qty']);

        $variations = $product->variation_stock;
        $min_price = $variations->min('price');
        $max_price = $variations->max('price');

        if($product->discount_type == 'flat') {
            $min_price = $variations->min('price') - optional($product)->discount_amount;
            $max_price = $variations->max('price') - optional($product)->discount_amount;

        }
        else if($product->discount_type == 'percentage') {
            $discount_amount_tk = (optional($product)->discount_amount * $variations->min('price'))/100;
            $min_price =  $variations->min('price') - $discount_amount_tk;
            $max_price =  $variations->max('price') - $discount_amount_tk;
        }
    }

@endphp
<div class="product-card">
    <div class="product-badge">
        @if ($sale_text)
            <span class="badge bg-danger">{{$sale_text}}</span>
        @endif
    </div>
    
    <div class="product-thumb">
        <a href="{{ route('product.show', $product->slug??'test') }}">
            <img class="img-fluid" src="{{ asset('images/product/'.$product->thumbnail_image) }}" alt="{{$product->title}}">
        </a>
        <div class="product-actions">
            <button class="btn btn-sm btn-outline-secondary wishlist-btn" data-product-id="{{ $product->id }}">
                <i class="far fa-heart"></i>
            </button>
            <button class="btn btn-sm btn-outline-secondary quick-view-btn" data-product-id="{{ $product->id }}">
                <i class="far fa-eye"></i>
            </button>
        </div>
    </div>
    
    <div class="product-details">
        <div class="product-category">{{optional($product->brand)->title}}</div>
        <h4 class="product-title">
            <a href="{{ route('product.show', $product->slug??'test') }}">{{ substr($product->title,0,45) }}</a>
        </h4>
        
        <div class="product-rating">
            <div class="stars text-warning">
                @php
                    $avgRating = $product->reviews->avg('review_star') ?? 0;
                    $fullStars = floor($avgRating);
                @endphp
                                    
                @for($i = 1; $i <= $fullStars; $i++)★@endfor
                @for($i = $fullStars + 1; $i <= 5; $i++)☆@endfor
            </div>
            <small class="text-muted">({{ $product->reviews->count() ?? 0 }} reviews)</small>
        </div>

        
        <div class="product-price">
            @if($product->type == 'single')
                @if($product->discount_type <> 'no')
                <?php
                    if($product->discount_type == 'flat') {
                        $new_price = optional($stock_price)->price - optional($product)->discount_amount;
                    }
                    else if($product->discount_type == 'percentage') {
                        $discount_amount_tk = (optional($product)->discount_amount * optional($stock_price)->price)/100;
                        $new_price =  optional($stock_price)->price - $discount_amount_tk;
                    }
                ?>
                <span class="current-price">{{number_format($new_price)}}৳</span>
                <span class="original-price">{{number_format(optional($stock_price)->price)}}৳</span>
                @else
                <span class="current-price">{{number_format(optional($stock_price)->price)}}৳</span>
                @endif
            @else
                @if($min_price < $max_price)
                    <span class="current-price">{{number_format($min_price)}}৳ - {{number_format($max_price)}}৳</span>
                @else
                    <span class="current-price">{{number_format($max_price)}}৳</span>
                @endif
            @endif
        </div>
        
        <div class="product-cta">
            @if ($product->call_for_price == 0)
                @if($product->type == 'single')
                    @if(optional($stock_price)->qty > 0)
                        <button class="btn btn-primary btn-sm me-2" onclick="addToCart({{optional($product)->id}}, 'only', 'checkout')">
                            <i class="fas fa-bolt me-1"></i> Buy Now
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="addToCart({{ $product->id }}, 'only', 'cart')">
                            <i class="fas fa-cart-plus me-1"></i> Add to Cart
                        </button>
                    @else
                        <button class="btn btn-secondary btn-sm w-100" disabled>
                            <i class="fas fa-times-circle me-1"></i> Out of Stock
                        </button>
                    @endif
                @else
                    <a class="btn btn-primary btn-sm w-100" href="{{ route('product.show', $product->slug??'test') }}">
                        <i class="fas fa-list-ul me-1"></i> Select Options
                    </a>
                @endif
            @else
                <button class="btn btn-info btn-sm w-100">
                    <i class="fas fa-phone-alt me-1"></i> Call For Price
                </button>
            @endif
        </div>
    </div>
</div>
@endif

<style>
    .product-card {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        position: relative;
        margin-bottom: 20px;
    }
    
    .product-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        transform: translateY(-5px);
    }
    
    .product-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
    }
    
    .product-thumb {
        position: relative;
        overflow: hidden;
        padding: 15px;
        text-align: center;
        background: #f9f9f9;
    }
    
    .product-thumb img {
        max-height: 200px;
        width: auto;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-thumb img {
        transform: scale(1.05);
    }
    
    .product-actions {
        position: absolute;
        top: 15px;
        right: 15px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .product-card:hover .product-actions {
        opacity: 1;
    }
    
    .product-actions button {
        display: block;
        margin-bottom: 5px;
        width: 30px;
        height: 30px;
        line-height: 30px;
        padding: 0;
        text-align: center;
    }
    
    .product-details {
        padding: 15px;
    }
    
    .product-category {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 5px;
    }
    
    .product-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
        height: 40px;
        overflow: hidden;
    }
    
    .product-title a {
        color: #333;
        text-decoration: none;
    }
    
    .product-title a:hover {
        color: #0d6efd;
    }
    
    .product-rating {
        margin-bottom: 10px;
    }
    
    .product-price {
        margin-bottom: 15px;
    }
    
    .current-price {
        font-size: 18px;
        font-weight: 700;
        color: #0d6efd;
    }
    
    .original-price {
        font-size: 14px;
        text-decoration: line-through;
        color: #6c757d;
        margin-left: 5px;
    }
    
    .product-cta {
        margin-top: 15px;
    }
    
    .stars {
        display: inline-block;
    }
</style>