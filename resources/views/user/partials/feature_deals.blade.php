    <!-- Featured Deal Section - NEW DESIGN -->
    <section class="featured-deals">
        <div class="container-fluid" style="margin-top:30px">
            <div class="mb-5">
                <h2 class="h2 mb-0">Today's Hot Deals</h2>
                <p class="section-subtitle">Don't miss out on these exclusive offers</p>
            </div>
             <?php
                $productData = get_product_display_data($todaysDeal);
                $isVariable = $todaysDeal->type == 'variable';
             ?>
            
            <div class="row g-4">               
                <!-- Featured Product -->
                <div class="col-lg-4 p-2 bg-light pb-5">
                    <div class="featured-product-card">                        
                        <div class="featured-product-image">
                            <img src="{{ asset('images/product/' . $todaysDeal->thumbnail_image) }}" 
                                class="img-fluid" 
                                alt="{{ $todaysDeal->title ?? '' }}">
                        </div>
                        <div class="featured-product-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                <h2 class="product__items--content__title h4"><a href="{{ route('product.show', $todaysDeal->slug) }}">
                                    {{ $todaysDeal->title }}
                                </a></h2>                                  
                                <p class="product-description">{!! Str::limit(strip_tags($todaysDeal->description), 120) !!}</p>
                                </div>         
                            </div>

                            <div class="todaysRate mt-2">
                            <div class="product-rating d-flex">
                                <div class="stars text-warning">
                                    @php
                                        $avgRating = $todaysDeal->reviews->avg('review_star') ?? 0;
                                        $fullStars = floor($avgRating);
                                    @endphp
                                    
                                    @for($i = 1; $i <= $fullStars; $i++)★@endfor
                                    @for($i = $fullStars + 1; $i <= 5; $i++)☆@endfor
                                </div>
                                <small class="text-muted">({{ $todaysDeal->reviews->count() ?? 0 }} reviews)</small>
                                </div>
                            </div>
   
                            
                            <div class="product-pricing">
                                @if(!$isVariable)
                                   <span class="current-price">{{ number_format($productData['current_price']) }} {{ env('CURRENCY') }}</span>
                                        @if($productData['original_price'])
                                            <span class="original-price">{{ number_format($productData['original_price']) }} {{ env('CURRENCY') }}</span>
                                        @endif
                                    @else
                                        <span class="current-price">
                                            @isset($productData['price_range']['min'])
                                                {{ number_format($productData['price_range']['min']) }} {{ env('CURRENCY') }}
                                                @isset($productData['price_range']['max'])
                                                    @if($productData['price_range']['min'] != $productData['price_range']['max'])
                                                        - {{ number_format($productData['price_range']['max']) }} {{ env('CURRENCY') }}
                                                    @endif
                                                @endisset
                                            @else
                                                {{ number_format(0) }} {{ env('CURRENCY') }}
                                            @endisset
                                    </span>
                                 @endif
                            </div>


                         @if($todaysDeal->type == 'single')
                            @if(!$productData['is_out_of_stock'])
                                <div class="product-actions mt-3">
                                    <button onclick="addToCart({{ $todaysDeal->id }}, 'only', 'cart')" class="btn btn-primary btn-lg me-2">
                                        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                    </button>
                                    <button onclick="addToWishlist({{ $todaysDeal->id }})" class="btn btn-outline-secondary btn-lg">
                                        <i class="far fa-heart new_icon"></i>
                                    </button>
                                </div>                                
                                @else
                                    {{-- Out of Stock --}}
                                    <button class="product__items--action__btn add__to--cart" type="button" disabled >
                                        <span class="add__to--cart__text">Out of Stock</span>
                                    </button>
                                @endif
                            @else
                            {{-- Select Product --}}
                                <a class="product__items--action__btn add__to--cart" 
                                    href="{{ route('product.show', $todaysDeal->slug) }}">
                                    <svg class="product__items--action__btn--svg d-none" xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 14.706 13.534">
                                        <g transform="translate(0 0)">
                                            <g>
                                            <path data-name="Path 16787" d="M4.738,472.271h7.814a.434.434,0,0,0,.414-.328l1.723-6.316a.466.466,0,0,0-.071-.4.424.424,0,0,0-.344-.179H3.745L3.437,463.6a.435.435,0,0,0-.421-.353H.431a.451.451,0,0,0,0,.9h2.24c.054.257,1.474,6.946,1.555,7.33a1.36,1.36,0,0,0-.779,1.242,1.326,1.326,0,0,0,1.293,1.354h7.812a.452.452,0,0,0,0-.9H4.74a.451.451,0,0,1,0-.9Zm8.966-6.317-1.477,5.414H5.085l-1.149-5.414Z" transform="translate(0 -463.248)" fill="currentColor"></path>
                                            <path data-name="Path 16788" d="M5.5,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,5.5,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,6.793,478.352Z" transform="translate(-1.191 -466.622)" fill="currentColor"></path>
                                            <path data-name="Path 16789" d="M13.273,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,13.273,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,14.566,478.352Z" transform="translate(-2.875 -466.622)" fill="currentColor"></path>
                                            </g>
                                        </g>
                                    </svg>
                                    <span class="add__to--cart__text">Select Product</span>
                                </a>
                            @endif
                            
                            
                            {{-- <div class="deal-countdown">
                                <div class="countdown-title">Hurry! Offer ends in:</div>
                                <div class="countdown-timer">
                                    <span class="countdown-box">12</span>h 
                                    <span class="countdown-box">45</span>m 
                                    <span class="countdown-box">30</span>s
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>                
                <!-- Related Products Slider -->
                <div class="col-lg-8 p-2 bg-light">

                    <div class="related-products-slider">
                        <div class="seo-content mt-4 d-none d-md-block">
                            <p class="text-muted" style="line-height: 150%; letter-spacing: 1px;">
                               Discover expertly curated, high-quality products that customers love. Each item is competitively priced, backed by verified ratings, and thoroughly quality-checked. Shop with confidence — everything’s ready to ship!
                            </p>
                        </div>
                        <div class="slider-header d-flex justify-content-between align-items-center">
                            <h3 class="slider-title mb-0">You May Also Like</h3>


                            <div class="slider-nav">
                                <button class="slider-prev">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="slider-next">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="swiper related-slider">
                            <div class="swiper-wrapper">
                                @foreach($trendingProducts as $product)

                                 <?php
                                    $productData = get_product_display_data($product);
                                    $isVariable = $product->type == 'variable';
                                 ?>
                                <div class="swiper-slide">
                                    <div class="related-product-card">                                       
                                        <div class="product-image">
                                            <img src="{{ asset('images/product/' . $product->thumbnail_image) }}"
                                                alt="{{ $product->title }}" 
                                                class="img-fluid">

                                            @if($productData['sale_text'])                                     
                                                <div class="product__badge">
                                                    <span class="product__badge--items sale">{{ $productData['sale_text'] }}</span>
                                                </div>
                                            @endif
              
                                        </div>
                                        <div class="product-info">
                                            <h4 class="product-name">{{ $product->title }}</h4>
                                            {{-- <div class="product-rating">
                                                <div class="stars text-warning small">
                                                    ★★★★★
                                                </div>
                                            </div> --}}
                                            <div class="product-price">

                                    @if(!$isVariable)
                                   <span class="current-price">{{ number_format($productData['current_price']) }} {{ env('CURRENCY') }}</span>
                                        @if($productData['original_price'])
                                            <span class="original-price">{{ number_format($productData['original_price']) }} {{ env('CURRENCY') }}</span>
                                        @endif
                                    @else
                                        <span class="current-price">
                                            @isset($productData['price_range']['min'])
                                                {{ number_format($productData['price_range']['min']) }} {{ env('CURRENCY') }}
                                                @isset($productData['price_range']['max'])
                                                    @if($productData['price_range']['min'] != $productData['price_range']['max'])
                                                        - {{ number_format($productData['price_range']['max']) }} {{ env('CURRENCY') }}
                                                    @endif
                                                @endisset
                                            @else
                                                {{ number_format(0) }} {{ env('CURRENCY') }}
                                            @endisset
                                    </span>
                                 @endif
                                
                                </div>


                          @if($product->type == 'single')
                            @if(!$productData['is_out_of_stock'])
                                <div class="product-actions-mini mt-3">
                                    <button onclick="addToCart({{ $product->id }}, 'only', 'cart')" class="btn btn-primary btn-lg me-2">
                                        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                    </button>
                                    <button onclick="addToWishlist({{ $product->id }})" class="btn btn-outline-secondary btn-lg">
                                        <i class="far fa-heart new_icon"></i>
                                    </button>
                                </div>                                
                                @else
                                    {{-- Out of Stock --}}
                                    <button class="product__items--action__btn_sold" type="button" disabled >
                                        <span class="add__to--cart__text">Out of Stock</span>
                                    </button>
                                @endif
                            @else
                            {{-- Select Product --}}
                                <a class="product__items--action__btn add__to--cart" 
                                    href="{{ route('product.show', $product->slug) }}">
                                    <svg class="product__items--action__btn--svg d-none" xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 14.706 13.534">
                                        <g transform="translate(0 0)">
                                            <g>
                                            <path data-name="Path 16787" d="M4.738,472.271h7.814a.434.434,0,0,0,.414-.328l1.723-6.316a.466.466,0,0,0-.071-.4.424.424,0,0,0-.344-.179H3.745L3.437,463.6a.435.435,0,0,0-.421-.353H.431a.451.451,0,0,0,0,.9h2.24c.054.257,1.474,6.946,1.555,7.33a1.36,1.36,0,0,0-.779,1.242,1.326,1.326,0,0,0,1.293,1.354h7.812a.452.452,0,0,0,0-.9H4.74a.451.451,0,0,1,0-.9Zm8.966-6.317-1.477,5.414H5.085l-1.149-5.414Z" transform="translate(0 -463.248)" fill="currentColor"></path>
                                            <path data-name="Path 16788" d="M5.5,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,5.5,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,6.793,478.352Z" transform="translate(-1.191 -466.622)" fill="currentColor"></path>
                                            <path data-name="Path 16789" d="M13.273,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,13.273,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,14.566,478.352Z" transform="translate(-2.875 -466.622)" fill="currentColor"></path>
                                            </g>
                                        </g>
                                    </svg>
                                    <span class="add__to--cart__text">Select Product</span>
                                </a>
                            @endif


{{--                                             
                                            <div class="product-actions-mini mt-3">
                                                <button class="btn btn-primary btn-lg me-2">
                                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                                </button>
                                                <button class="btn btn-outline-secondary btn-lg">
                                                    <i class="far fa-heart new_icon"></i>
                                                </button>
                                            </div> --}}

                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>