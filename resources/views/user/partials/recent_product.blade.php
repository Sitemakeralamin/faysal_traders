    <section class="section-spacing" style="background-color: #000435">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0 text-white">Recently Added Products</h2>
                <div class="d-flex gap-2">
                    <button class="swiper-nav-btn recent-prev">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="swiper-nav-btn recent-next">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="swiper recent-swiper">
                <div class="swiper-wrapper">
                    @foreach($recentProducts as $product)

                        <?php
                            $productData = get_product_display_data($product);
                            $isVariable = $product->type == 'variable';
                        ?>

                    <div class="swiper-slide">
                        <div class="product-card p-3">
                            <div class="product-image-container">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <img src="{{ asset('images/product/' . $product->thumbnail_image) }}"
                                        alt="{{ $product->title }}" 
                                        class="img-fluid">
                                </a>
                            </div>
                            <div class="product-info mt-3">

                                <h3 class="h6 mb-1"><a href="{{ route('product.show', $product->slug) }}">{{ substr($product->title,0,45) }}</a></h3>

                                <p class="text-muted small mb-2">{!! Str::limit(strip_tags($product->description), 70) !!}</p>
                                <div class="text-warning mb-2">
                                    {{-- @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $product->rating)
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    @endfor --}}
                                </div>

                                <div class="d-flex align-items-center">
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
                                    <button class="product__items--action__btn_sold mt-2" type="button" disabled >
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


                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>