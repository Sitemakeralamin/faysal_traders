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
<div class="p-2 mb-5">
    <div class="col border-radius-10" style="background-color: #FFFFFF !important; padding-bottom:20px;">
        <div class="product__items">
            <div class="product__items--thumbnail product_col">
                <a class="product__items--link" href="{{ route('product.show', $product->slug??'test') }}">
                    <img class="product__items--img product__primary--img product_img border-radius-10" src="{{ asset('images/product/'.$product->thumbnail_image) }}" alt="{{$product->title}}">
                </a>
                @if ($sale_text)
                    <div class="product__badge">
                        <span class="product__badge--items sale">{{$sale_text}}</span>
                    </div>
                @endif
            </div>

            <div class="product__items--content text-center">
                {{-- <span class="product__items--content__subtitle">{{optional($product->brand)->title}}</span> --}}
                <h4 class="product__items--content__title"><a href="{{ route('product.show', $product->slug??'test') }}">
                    {{ substr($product->title,0,45) }}
                </a></h4>
                <div class="product__items--price">
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
                        <span class="current__price"><b>{{number_format($new_price)}}৳</b></span>
                        <span class="price__divided"></span>
                        <span class="old__price"><b>{{number_format(optional($stock_price)->price)}}৳</b></span>
                        @else
                        <span class="current__price"><b>{{number_format(optional($stock_price)->price)}}৳</b></span>
                        
                        @endif
                    @else
                        @if($min_price < $max_price)
                            <span class="current__price"><b>{{number_format($min_price)}}৳</b></span>
                            <span class="price__divided"></span> 
                            <span class="current__price"><b>{{number_format($max_price)}}৳</b></span>
                        @else
                            <span class="current__price"><b>{{number_format($max_price)}}৳</b></span>
                        @endif
                    @endif
                </div>
                
                <ul class="product__items--action">
                    <li class="product__items--action__list text-center">
                        @if ($product->call_for_price==0)
                            @if($product->type == 'single')
                                @if(optional($stock_price)->qty > 0)
                                    {{-- Buy Now --}}
                                    <button class="product__items--action__btn buy__now--cart" id="buy_now_button{{optional($product)->id}}" onclick="addToCart({{optional($product)->id}}, 'only', 'checkout')" type="button">Buy Now</button>
                                    {{-- + Add to cart --}}
                                    <button class="product__items--action__btn add__to--cart" onclick="addToCart({{ $product->id }}, 'only', 'cart')" type="button" >
                                    <svg class="product__items--action__btn--svg d-none" xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 14.706 13.534">
                                        <g transform="translate(0 0)">
                                            <g>
                                            <path data-name="Path 16787" d="M4.738,472.271h7.814a.434.434,0,0,0,.414-.328l1.723-6.316a.466.466,0,0,0-.071-.4.424.424,0,0,0-.344-.179H3.745L3.437,463.6a.435.435,0,0,0-.421-.353H.431a.451.451,0,0,0,0,.9h2.24c.054.257,1.474,6.946,1.555,7.33a1.36,1.36,0,0,0-.779,1.242,1.326,1.326,0,0,0,1.293,1.354h7.812a.452.452,0,0,0,0-.9H4.74a.451.451,0,0,1,0-.9Zm8.966-6.317-1.477,5.414H5.085l-1.149-5.414Z" transform="translate(0 -463.248)" fill="currentColor"></path>
                                            <path data-name="Path 16788" d="M5.5,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,5.5,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,6.793,478.352Z" transform="translate(-1.191 -466.622)" fill="currentColor"></path>
                                            <path data-name="Path 16789" d="M13.273,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,13.273,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,14.566,478.352Z" transform="translate(-2.875 -466.622)" fill="currentColor"></path>
                                            </g>
                                        </g>
                                    </svg>
                                    <span class="add__to--cart__text">Add to cart</span>
                                    </button>
                                
                                @else
                                    {{-- Out of Stock --}}
                                    <button class="product__items--action__btn add__to--cart" type="button" disabled >
                                        <span class="add__to--cart__text">Out of Stock</span>
                                    </button>
                                @endif
                            @else
                            {{-- Select Product --}}
                                <a class="product__items--action__btn add__to--cart" 
                                    {{--onclick="quick_view({{ $product->id }})"--}} href="{{ route('product.show', $product->slug??'test') }}">
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
                        @else
                            <button class="product__items--action__btn add__to--cart" type="button">
                                    <span class="add__to--cart__text">Call For Price</span>
                            </button>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
