@extends('user.inc.master')

@section('title'){{ optional($product)->title }}@endsection
@section('description'){{ optional($product)->meta_description }}@endsection
@section('keywords'){{ optional($product)->meta_keywords }}@endsection

@section('og_image'){{ optional($product)->thumbnail_image }}@endsection
@section('canonical', route('product.show', $product->slug))


<!-- Inside your Blade layout or product page -->
@section('schema_markup')
    <script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "{{ $product->title }}",
        "image": [
        "{{ asset($product->thumbnail_image) }}"
        ],
        "description": "{{ strip_tags(Str::limit($product->description, 160)) }}",
        "sku": "{{ $product->code }}",
        "mpn": "{{ $product->code }}",
        "brand": {
        "@type": "Brand",
        "name": "{{ optional($product->brand)->title }}"
        },
        "offers": {
        "@type": "Offer",
        "url": "{{ Request::url() }}",
        "priceCurrency": "BDT",
        "price": "{{ $product->single_stock->price ?? 0 }}",
        "availability": "https://schema.org/{{ $product->single_stock->qty > 0 ? 'InStock' : 'OutOfStock' }}",
        "itemCondition": "https://schema.org/NewCondition"
        }
    }
    </script>
@endsection



@section('content')

    <style>
        /*For Product Image Zoom*/
        figure.zoom {
            & img:hover {
                opacity: 0;
            }

            .content-wrapper {
                overflow-x: auto;
                width: 100%;
            }

            img {
                transition: opacity .5s;
                display: block;
                width: 100%;
            }

            background-position: center center;
            position: relative;

            overflow: hidden;
            cursor: zoom-in !important;
        }

        .product__tab--content {
            margin-left: 0px;
        }


        /* Desktop styles */
        .product__tab--content table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
            border: none;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 10px;
        }

        /* Full-width heading row */
        .product__tab--content table tr:first-child td {
            background-color: #ee2932;
            color: #f7f9fc;
            font-weight: bold;
            padding: 6px 10px;
            text-align: left;
            vertical-align: middle;
            border: none;
        }

        /* Normal table data rows */
        .product__tab--content table td {
            padding: 12px 16px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
            text-align: left;
        }


        /* Remove bottom border from last row */
        .product__tab--content table tr:last-child td {
            border-bottom: none;
        }

        /* Modern scrollbar (for tables with overflow) */
        .product__tab--content ::-webkit-scrollbar {
            height: 6px;
        }

        .product__tab--content ::-webkit-scrollbar-thumb {
            background: #ced4da;
            border-radius: 4px;
        }

        .product__tab--content ul,
        .product__tab--content ol {
            padding-left: 20px;
            margin-bottom: 1rem;
        }

        .product__tab--content li {
            margin-bottom: 0.5rem;
            list-style-position: inside;
            word-wrap: break-word;
        }

        .product__tab--content p {
            margin-bottom: 1rem;
            word-wrap: break-word;
        }

        .product__tab--content img {
            max-width: 100%;
            height: auto;
            display: block;
            margin-bottom: 1rem;
        }

        /* Default button (normal flow) */
        /* Original Cart Button */

        #scrollingAddToCart.hide-original {
            opacity: 0;
            visibility: hidden;
        }

        .product_description_sticky {
            position: sticky;
            top: 75px;
            background: #fff;
            padding-top: 10px;
        }

        #stickyAddToCart {
            opacity: 0;
            visibility: hidden;
            position: sticky;
            top: 45px;
            margin: 15px 0;
            transition: all 0.3s ease;
        }

        .sticky-addtocart-container {
            margin-top: -35px;
        }

        #stickyAddToCart.active {
            opacity: 1;
            visibility: visible;
        }

        #stickyAddToCart.active::before {
            content: "";
            width: 100%;
            background: #fff;
            padding-top: 10px;
        }


        .variant__input--fieldset input[type=radio]:checked+label {
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
        }

        .single-product-bg-info {
            margin-bottom: 5px;
        }

        .product-details-tab-list {
            border: 1px solid var(--pick-purpule);
        }

        .product-details-tab-list:hover {
            background-color: var(--pick-purpule);
            color: var(--white-color);
        }

        .product-details-tab-list.active {
            background-color: var(--pick-purpule);
            color: var(--white-color);
        }


        /* Mobile Responsive Table Fix */
        @media (max-width: 767px) {
            .product-details-tab-list {
                cursor: pointer !important;
                margin-right: 0.2rem !important;
                border: 1px solid #aba8a8 !important;
                border-bottom: 1px solid #aba8a8 !important;
                padding: 4px 4px 5px 5px !important;
                border-radius: 4px 4px 0 0 !important;
                font-size: 1.5rem !important;
                font-weight: 500 !important;
            }

            .quickview__cart--btn {
                height: 2.7rem !important;
                line-height: 2.7rem !important;
                padding: 0 21px !important;
                margin-left: 5px !important;
                font-size: 1.4rem !important;
            }

            .sticky-addtocart-container {
                margin-top: -12px;
                margin-left: -5px;
            }

            #stickyAddToCart.sticky-active {
                opacity: 1;
                visibility: visible;
                margin-top: -20px;
                margin-left: 0px;
                /* width: 100%; */
            }

            .product__tab--content {
                margin: 0px -10px;
                overflow-x: scroll;
            }
        }
    </style>

    @php

        $stock_price = $product->single_stock;
        $sale_text = 'sale';
        $stock_qty = '';
        $stock_qty_text = 'In Stock';

        if ($product->discount_type != 'no') {
            if ($product->discount_type == 'flat') {
                $sale_text = 'Discount ' . optional($product)->discount_amount . ' TK';
            } elseif ($product->discount_type == 'percentage') {
                $sale_text = 'Discount ' . optional($product)->discount_amount . '%';
            }
        }

        if ($product->type == 'single') {
            if (optional($stock_price)->qty <= 0) {
                $sale_text = 'Out of Stock';
                $stock_qty_text = 'Out of Stock';
            }
            $stock_qty = optional($stock_price)->qty . ' ' . optional($product)->unit_type;
        } else {
            $variations = $product->variation_stock;
            $min_price = $variations->min('price');
            $max_price = $variations->max('price');

            if ($product->discount_type == 'flat') {
                $min_price = $variations->min('price') - optional($product)->discount_amount;
                $max_price = $variations->max('price') - optional($product)->discount_amount;
            } elseif ($product->discount_type == 'percentage') {
                $discount_amount_tk = (optional($product)->discount_amount * $variations->min('price')) / 100;
                $min_price = $variations->min('price') - $discount_amount_tk;
                $max_price = $variations->max('price') - $discount_amount_tk;
            }
        }

        $reviews = App\Models\ProductsReviews::where(['product_id' => optional($product)->id])
            ->where('is_active', 1)
            ->orderBy('id', 'DESC')
            ->get(['id', 'customer_id', 'review_star', 'review_text', 'is_active', 'created_at']);
        $review_count = count($reviews);

        $variationProductImages = App\Models\ProductStocks::where('product_id', optional($product)->id)
            ->where('image', '!=', null)
            ->get(['image', 'id']);
    @endphp

    <!-- Start product details section -->
    <section class="single-product-section-padding">
        <div class="container-fluid">
            {{-- breadcrump --}}
            <div class="row">
                <div class="
            col-xl-10 
            col-lg-10 
            col-md-10 
            col-8">

                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-home" style="margin-top: -7px ">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </span>


                    <span class="capitalize">
                        <a href="{{ url('/') }}">Home</a>

                        @if ($parentCategory)
                            / <a href="{{ route('product.show', ['slug' => $parentCategory->slug]) }}">
                                {{ $parentCategory->title }}
                            </a>
                        @endif

                        @if ($subCategory && $parentCategory)
                            / <a
                                href="{{ route('products.sub.category', [
                                    'main_cat' => $parentCategory->slug,
                                    'sub_cat' => $subCategory->slug,
                                ]) }}">{{ $subCategory->title }}</a>
                        @endif

                        @if ($childCategory && $parentCategory && $subCategory)
                            / <a
                                href="{{ route('products.child.category', [
                                    'main_cat' => $parentCategory->slug,
                                    'sub_cat' => $subCategory->slug,
                                    'child_slug' => $childCategory->slug,
                                ]) }}">{{ $childCategory->title }}</a>
                        @endif


                        @if (optional($product)->title)
                            / {{ optional($product)->title }}
                        @endif
                    </span>



                </div>
                {{-- Right Side: --}}
                <div class="
            col-xl-2 
            col-lg-2 
            col-md-2 
            col-4 flex items-center justify-end "
                    style="float:right;">
                    {{-- Share --}}
                    <div class="flex items-center gap-2">
                        <span class="text-sm md:text-tiny">Share: </span>

                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('product.show', $product->slug) }}"
                            target="_blank">
                            <span aria-label="Facebook" class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-facebook">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                </svg></span>
                        </a>

                    </div>
                    <div class="h-full w-1 bg-secondary ms-2 me-2"></div>
                    <span type="button" onclick="addToWishlist({{ $product->id }})" title="Add To Wishlist"
                        class="flex items-center gap-2 text-primary-hover duration-300">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 16 16" height="1.5em"
                            width="1.5em" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.389 12.99l-1.27-1.27.67-.7 2.13 2.13v.7l-2.13 2.13-.71-.71L7.349 14h-1.85a2.49 2.49 0 0 1-2.5-2.5V5.95a2.59 2.59 0 0 1-1.27-.68 2.52 2.52 0 0 1-.54-2.73A2.5 2.5 0 0 1 3.499 1a2.45 2.45 0 0 1 1 .19 2.48 2.48 0 0 1 1.35 1.35c.133.317.197.658.19 1a2.5 2.5 0 0 1-2 2.45v5.5a1.5 1.5 0 0 0 1.5 1.5h1.85zm-4.68-8.25a1.5 1.5 0 0 0 2.08-2.08 1.55 1.55 0 0 0-.68-.56 1.49 1.49 0 0 0-.86-.08 1.49 1.49 0 0 0-1.18 1.18 1.49 1.49 0 0 0 .08.86c.117.277.311.513.56.68zm10.33 6.3c.48.098.922.335 1.27.68a2.51 2.51 0 0 1 .31 3.159 2.5 2.5 0 1 1-3.47-3.468c.269-.182.571-.308.89-.37V5.49a1.5 1.5 0 0 0-1.5-1.5h-1.85l1.27 1.27-.71.71-2.13-2.13v-.7l2.13-2.13.71.71-1.27 1.27h1.85a2.49 2.49 0 0 1 2.5 2.5v5.55zm-.351 3.943a1.5 1.5 0 0 0 1.1-2.322 1.55 1.55 0 0 0-.68-.56 1.49 1.49 0 0 0-.859-.08 1.49 1.49 0 0 0-1.18 1.18 1.49 1.49 0 0 0 .08.86 1.5 1.5 0 0 0 1.539.922z">
                            </path>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="row row-cols-lg-2 row-cols-md-2">
                <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                    {{-- Product Media Preview --}}
                    <div class="product__details--media">
                        <div class="product__media--preview  swiper">
                            <div class="swiper-wrapper">
                                @foreach ($product->product_image as $image)
                                    <div class="swiper-slide">
                                        <div class="product__media--preview__items">
                                            <a class="product__media--preview__items--link glightboxStop"
                                                style="cursor: zoom-in !important;" data-gallery="product-media-preview"
                                                hrefStop="{{ asset('images/product/' . $image->image) }}">
                                                <figure class="zoom" onmousemove="zoom(event)"
                                                    style="background-image: url({{ asset('images/product/' . $image->image) }})">
                                                    <img class="bg-white product__media--preview__items--img"
                                                        src="{{ asset('images/product/' . $image->image) }}"
                                                        alt="{{ $product->title }}">
                                            </a>
                                            </figure>
                                            <div class="product__media--view__icon d-none">
                                                <a class="product__media--view__icon--link glightboxStop"
                                                    style="cursor: zoom-in !important;"
                                                    href="{{ asset('images/product/' . $image->image) }}"
                                                    data-gallery="product-media-preview">
                                                    <svg class="product__media--view__icon-svg"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path
                                                            d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if (count($variationProductImages) > 0)
                                    @foreach ($variationProductImages as $vImage)
                                        <div class="swiper-slide">
                                            <div class="product__media--preview__items">
                                                <a class="product__media--preview__items--link glightbox"
                                                    data-gallery="product-media-preview"
                                                    href="{{ asset('images/product/' . $vImage->image) }}">

                                                    <figure class="zoom" onmousemove="zoom(event)"
                                                        style="background-image: url({{ asset('images/product/' . $vImage->image) }})">

                                                        <img class="product__media--preview__items--img"
                                                            src="{{ asset('images/product/' . $vImage->image) }}"
                                                            alt="{{ $vImage->title ?? '' }}">
                                                    </figure>
                                                </a>
                                                <div class="product__media--view__icon">
                                                    <a class="product__media--view__icon--link glightbox"
                                                        href="{{ asset('images/product/' . $vImage->image) }}"
                                                        data-gallery="product-media-preview">
                                                        <svg class="product__media--view__icon-svg"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path
                                                                d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="product__media--nav swiper">
                            <div class="swiper-wrapper">
                                @foreach ($product->product_image as $image)
                                    <div class="swiper-slide">
                                        <div class="product__media--nav__items">
                                            <img class="product__media--nav__items--img"
                                                src="{{ asset('images/product/' . $image->image) }}"
                                                alt="{{ $product->title }}">
                                        </div>
                                    </div>
                                @endforeach
                                @if (count($variationProductImages) > 0)
                                    @foreach ($variationProductImages as $vImage)
                                        <div class="swiper-slide">
                                            <div class="product__media--nav__items">
                                                <img class="product__media--nav__items--img"
                                                    src="{{ asset('images/product/' . $vImage->image) }}"
                                                    alt="{{ $product->title }}">
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="swiper__nav--btn swiper-button-next"></div>
                            <div class="swiper__nav--btn swiper-button-prev"></div>
                        </div>
                    </div>
                </div>
                {{-- Product__details--info --}}
                <div class="col-xl-8 col-lg-8 col-md-8 col-12">
                    <div class="product__details--info">
                        {{-- Brand --}}
                        <div class="col-md-4 col-4">
                            @if (!is_null($product->brand))
                                <a class="w-24 h-auto block" href="#{{-- route('brand.products', [$product->brand->id, Str::slug($product->brand->title)]) --}}">
                                    <img class="h-full w-full"
                                        src="{{ asset('images/brand/' . $product->brand->image) }}"
                                        alt="{{ $product->brand->image }}" title="{{ $product->brand->title }}" /></a>
                            @endif
                        </div>
                        {{-- Name --}}
                        <h2 class="product__details--info__title mb-15" title="{{ $product->title }}">
                            {{ $product->title }}</h2>
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <div class="mb-3">
                                    {{-- Price --}}
                                    @if ($product->type == 'single')
                                        @if ($product->discount_type != 'no')
                                            <?php
                                            if ($product->discount_type == 'flat') {
                                                $new_price = $stock_price->price - optional($product)->discount_amount;
                                            } elseif ($product->discount_type == 'percentage') {
                                                $discount_amount_tk = (optional($product)->discount_amount * $stock_price->price) / 100;
                                                $new_price = $stock_price->price - $discount_amount_tk;
                                            }
                                            ?>
                                            <span class="single-product-bg-info">
                                                Price: <b>{{ number_format($new_price) }}৳</b></span>

                                            <span class="single-product-bg-info">
                                                Regular Price: <b>{{ number_format($stock_price->price) }}৳</b></span>
                                        @else
                                            <span class="single-product-bg-info">
                                                Price: <b>{{ number_format($stock_price->price) }}৳</b></span>
                                        @endif
                                    @else
                                        <span class="single-product-bg-info"
                                            id="product_price_info{{ optional($product)->id }}">
                                            Price Range: <b>{{ number_format($min_price) }}৳</b> <span
                                                class="price__divided"></span> <b>{{ number_format($max_price) }}৳</b>
                                        </span>
                                    @endif
                                    {{-- Status --}}
                                    <span class="single-product-bg-info"
                                        id="stock_qty_show{{ optional($product)->id }}">Status:
                                        <b>{{ $stock_qty_text }}</b></span>
                                    {{-- Producr Code --}}
                                    <span class="single-product-bg-info">Product Code: <b>{{ $product->code }}</b></span>

                                    {{-- Brand --}}
                                    @if (!is_null($product->brand))
                                        <span class="single-product-bg-info">Brand:
                                            <b>{{ $product->brand->title }}</b></span>
                                    @endif

                                </div>

                                {{-- <div class="mb-3">
                                    @if ($product->options && $product->options->count())
                                        <div class="mb-2">
                                            @foreach ($product->options->groupBy('head.name') as $headName => $options)
                                                <div class="mb-1">
                                                    <strong>{{ $headName }}:</strong>
                                                    <div class="d-inline flex-wrap">
                                                        @foreach ($options as $opt)
                                                            <span class="single-product-bg-info mr-2 mb-2">
                                                                {{ $opt->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div> --}}


                            </div>
                        </div>

                        @if ($review_count > 0)
                            @php
                                $total_review_star = $reviews
                                    ->filter(function ($item) {
                                        return $item->review_star > 0;
                                    })
                                    ->sum('review_star');
                                $average_review = $total_review_star / $review_count;

                            @endphp
                            {{-- Review --}}
                            <div class="product__details--info__rating d-flex align-items-center mb-15">
                                <ul class="rating d-flex justify-content-center">
                                    @for ($j = 1; $j <= $average_review; $j++)
                                        <li class="rating__list">
                                            <span class="rating__list--icon">
                                                <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg"
                                                    width="14.105" height="14.732" viewBox="0 0 10.105 9.732">
                                                    <path data-name="star - Copy"
                                                        d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z"
                                                        transform="translate(0 -0.018)" fill="currentColor"></path>
                                                </svg>
                                            </span>
                                        </li>
                                    @endfor
                                </ul>
                                <span class="product__items--rating__count--number">({{ $review_count }})</span>
                            </div>
                        @endif

                        {{-- Key Features --}}
                        @if (!empty(optional($product)->feature))
                            <div class="row mb-20">
                                <div class="col-12">
                                    <h3 style="margin: 0">Key Features</h3>
                                    {!! optional($product)->feature !!}
                                </div>
                            </div>
                        @endif


                        <div id="variationImage">

                        </div>

                        @if (count(optional($product)->variation_stock) > 0)
                            <form action="javascript:void(0)" method="POST"
                                id="variation_form{{ optional($product)->id }}">
                                <div class="product__variant">
                                    @if ($product->type == 'variation')
                                        <input type="hidden" name="product_type" id="product_type" value="variation">
                                        @if (optional($product)->colors != '[]')
                                            <input type="hidden" name="color_info" id="color_info" value="1">
                                            <div class="product__variant--list mb-10">
                                                <fieldset class="variant__input--fieldset">
                                                    <legend class="product__variant--title mb-8">Color : <span
                                                            id="SetColorName_{{ optional($product)->id }}"></span>
                                                    </legend>
                                                    @foreach (json_decode(optional($product)->colors, true) as $color)
                                                        <?php
                                                        $color_info = color_info($color);
                                                        ?>
                                                        @if ($loop->first)
                                                            <input id="color_{{ $color }}"
                                                                onchange="select_variation({{ optional($product)->id }})"
                                                                value="{{ $color }}" name="color"
                                                                type="radio">
                                                            <label class="variant__color--value"
                                                                style="background-color: {{ $color_info->code }} !important;"
                                                                for="color_{{ $color }}" data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="{{ $color_info->name }}"></label>
                                                        @else
                                                            <input id="color_{{ $color }}"
                                                                onchange="select_variation({{ optional($product)->id }})"
                                                                value="{{ $color }}" name="color"
                                                                type="radio">
                                                            <label class="variant__color--value"
                                                                style="background-color: {{ $color_info->code }} !important;"
                                                                for="color_{{ $color }}"
                                                                title="{{ $color_info->name }}"></label>
                                                        @endif
                                                    @endforeach
                                                </fieldset>
                                            </div>
                                        @else
                                            <input type="hidden" name="color_info" id="color_info" value="0">
                                        @endif
                                        @if (optional($product)->attributes != null)
                                            @foreach (json_decode(optional($product)->attributes, true) as $attribute)
                                                <?php
                                                $attribute_info = variation_info($attribute);
                                                ?>
                                                @if (!is_null($attribute_info))
                                                    <?php
                                                    $single_variation_info = single_variation_info($attribute_info->id, optional($product)->id);
                                                    ?>
                                                    @if (count($single_variation_info) > 0)
                                                        <div class="product__variant--list mb-15">
                                                            <fieldset
                                                                class="variant__input--fieldset {{ $attribute_info->title }}">
                                                                <legend class="product__variant--title mb-8">
                                                                    {{ $attribute_info->title }} : <span
                                                                        id="SetVariantOutput_{{ optional($product)->id }}"></span>
                                                                </legend>
                                                                <div
                                                                    id="single_variation_info_div{{ optional($product)->id }}">
                                                                    @foreach ($single_variation_info as $variation)
                                                                        <input
                                                                            id="{{ $attribute_info->title . $variation->id }}"
                                                                            onchange="select_variation({{ optional($product)->id }})"
                                                                            value="{{ $variation->id }}"
                                                                            name="attribute_variation" type="radio">
                                                                        <label class="variant__size--value"
                                                                            for="{{ $attribute_info->title . $variation->id }}">{{ $variation->variant_output }}</label>
                                                                    @endforeach
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    @else
                                        <input type="hidden" name="product_type" id="product_type" value="single">
                                    @endif
                                    <input type="hidden" name="product_id" id="product_id"
                                        value="{{ optional($product)->id }}">
                            </form>
                        @endif


                        <form action="javascript:void(0)" id="add_to_server{{ optional($product)->id }}" method="post">
                            <input type="hidden" name="product_id" id="product_id"
                                value="{{ optional($product)->id }}">
                            <div class="product__variant--list quantity d-flex align-items-center mb-20">
                                <div class="quantity__box">
                                    <button type="button" class="quantity__value quickview__value--quantity decrease"
                                        onclick="quantity_change('de', {{ optional($product)->id }})"
                                        aria-label="quantity value" value="Decrease Value">-</button>
                                    <label>
                                        <input type="number"
                                            class="quantity__number quickview__value--number quantity__number_{{ optional($product)->id }}"
                                            name="cart_qty_input" id="cart_qty_input" value="1" />

                                    </label>
                                    <button type="button" class="quantity__value quickview__value--quantity increase"
                                        onclick="quantity_change('in', {{ optional($product)->id }})"
                                        aria-label="quantity value" value="Increase Value">+</button>
                                </div>

                                <div class="stock-qty d-none">
                                    <p class="ps-3" id="stock_qty_show{{ optional($product)->id }}">
                                        {{-- $stock_qty --}}</p>
                                </div>

                            </div>

                            <div>
                                <input type="hidden" name="selected_variation_id"
                                    id="selected_variation_id{{ optional($product)->id }}" value="">
                                @if ($product->call_for_price == 0)


                                    @if ($product->type == 'single')
                                        <input type="hidden" name="product_type" id="product_type" value="single">
                                        <input type="hidden" name="stock_qty"
                                            id="stock_qty_{{ optional($product)->id }}"
                                            value="{{ optional($stock_price)->qty }}">
                                        @if (optional($stock_price)->qty > 0)
                                            <button class="ms-0 quickview__cart--btn primary__btn"
                                                onclick="addToCart({{ optional($product)->id }}, 'details', 'cart')"
                                                id="scrollingAddToCart" type="button">Add To Cart</button>
                                            <button class="quickview__cart--btn primary__btn"
                                                id="buy_now_button{{ optional($product)->id }}"
                                                onclick="addToCart({{ optional($product)->id }}, 'details', 'checkout')"
                                                type="button">Buy Now</button>
                                        @endif
                                    @else
                                        <input type="hidden" name="product_type" id="product_type" value="variation">
                                        <input type="hidden" name="stock_qty"
                                            id="stock_qty_{{ optional($product)->id }}" value="0">

                                        {{-- <button class="ms-0 quickview__cart--btn primary__btn originalAddToCart" id="scrollingAddToCart"
                                            onclick="addToCart({{ optional($product)->id }}, 'details', 'cart')"
                                            type="button">Add To Cart</button> --}}

                                        <button class="ms-0 quickview__cart--btn primary__btn originalAddToCart"
                                            id="scrollingAddToCart"
                                            onclick="addToCart({{ optional($product)->id }}, 'details', 'cart')"
                                            type="button">Add To Cart</button>

                                        <button class="quickview__cart--btn primary__btn mx-3"
                                            id="buy_now_button{{ optional($product)->id }}"
                                            onclick="addToCart({{ optional($product)->id }}, 'details', 'checkout')"
                                            type="button">Buy Now</button>

                                        <h3 class="m-3 text-danger fw-bold"
                                            id="notification_show{{ optional($product)->id }}" style="display: none;">
                                            Please Select a Variation</h3>

                                    @endif

                                    <a class="quickview__cart--btn primary__btn"
                                        onclick="addToWishlist({{ $product->id }})" href="javascript:void(0)">
                                        <svg class="product__items--action__btn--svg" xmlns="http://www.w3.org/2000/svg"
                                            width="25.51" height="23.443" viewBox="0 0 512 512">
                                            <path
                                                d="M352.92 80C288 80 256 144 256 144s-32-64-96.92-64c-52.76 0-94.54 44.14-95.08 96.81-1.1 109.33 86.73 187.08 183 252.42a16 16 0 0018 0c96.26-65.34 184.09-143.09 183-252.42-.54-52.67-42.32-96.81-95.08-96.81z"
                                                fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="32"></path>
                                        </svg>
                                        <span class="visually-hidden">Wishlist</span>
                                    </a>
                                @else
                                    <button class="ms-0 quickview__cart--btn primary__btn" type="button">Call For
                                        Price</button>
                                @endif
                            </div>
                        </form>
                    </div>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
        </div>
    </section>
    <!-- End product details section -->
    @php($business_info = business_info())
    <div style="background-color: {{ optional($business_info)->body_bg_color }} !important">
        <!-- Start product details tab section -->
        <section class="product__details--tab__section pt-4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8 col-sm-12">

                        <div class="product_description_sticky">

                            <ul class="product__details--tab d-flex mb-30">

                                <li class="product-details-tab-list active col-md-4 col-sm-6 mb-3" data-toggle="tab"
                                    data-target="#Specification">Specification</li>

                                <li class="product-details-tab-list col-md-4 col-sm-6 mb-3" data-toggle="tab"
                                    data-target="#description">Description</li>

                                <li class="product-details-tab-list col-md-4 col-sm-6 mb-3" data-toggle="tab"
                                    data-target="#reviews">Product Reviews</li>
                                <!-- Add to Cart Button Container -->

                            </ul>

                            <div class="sticky-addtocart-container">
                                <button class="ms-0 quickview__cart--btn primary__btn" id="stickyAddToCart"
                                    onclick="addToCart({{ optional($product)->id }}, 'details', 'cart')"
                                    type="button">Add To Cart</button>
                            </div>



                        </div>



                        <div class="product__details--tab__inner border-radius-10">
                            <div class="tab_content">


                                <div id="Specification" class="tab_pane active show">
                                    <div class="product__tab--content">
                                        <div class="content-wrapper">
                                            {!! optional($product)->specification !!}
                                        </div>
                                    </div>
                                </div>
                                <div id="description" class="tab_pane">
                                    <div class="product__tab--content">
                                        <div class="content-wrapper">
                                            {!! optional($product)->description !!}
                                        </div>
                                    </div>
                                </div>

                                <div id="reviews" class="tab_pane">
                                    <div class="product__reviews">

                                        <div class="reviews__comment--area">

                                            @if (count($reviews) > 0)
                                                @foreach ($reviews as $review)
                                                    <div class="reviews__comment--list d-flex">
                                                        <div class="reviews__comment--thumb">
                                                            <img class="shadow rounded"
                                                                src="{{ asset('images/customer/' . optional($review->customer_info)->image) }}"
                                                                alt="{{ $review->review_text ?? '' }}">
                                                        </div>
                                                        <div class="reviews__comment--content">
                                                            <div
                                                                class="reviews__comment--top d-flex justify-content-between">
                                                                <div class="reviews__comment--top__left">
                                                                    <h3 class="reviews__comment--content__title h4">
                                                                        {{ optional($review->customer_info)->name }}</h3>
                                                                    <ul class="rating reviews__comment--rating d-flex">
                                                                        @for ($i = 1; $i <= optional($review)->review_star; $i++)
                                                                            <li class="rating__list">
                                                                                <span class="rating__list--icon">
                                                                                    <svg class="rating__list--icon__svg"
                                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                                        width="14.105" height="14.732"
                                                                                        viewBox="0 0 10.105 9.732">
                                                                                        <path data-name="star - Copy"
                                                                                            d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z"
                                                                                            transform="translate(0 -0.018)"
                                                                                            fill="currentColor"></path>
                                                                                    </svg>
                                                                                </span>
                                                                            </li>
                                                                        @endfor

                                                                    </ul>
                                                                </div>
                                                                <span
                                                                    class="reviews__comment--content__date">{{ date('M d, Y', strtotime($review->created_at)) }}</span>
                                                            </div>
                                                            <p class="reviews__comment--content__desc">
                                                                {!! optional($review)->review_text !!}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 text-center d-none">
                        <h6 class="product-details-tab-list">Realeted Product</h6>
                    </div>
                </div>
            </div>
        </section>
        <!-- End product details tab section -->

        <!-- Start product section -->
        <section class="product__section product__section--style3 section--padding">
            <div class="container-fluid product3__section--container">
                <div class="section__heading text-center mb-50">
                    <h2 class="section__heading--maintitle">You may also like</h2>
                </div>
                <div class="row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 mb--n30">
                    @foreach ($similar_products as $product)
                        @include('user.partials.product')
                    @endforeach
                </div>
            </div>
            <input type="hidden" name="" id="baseURL" value="{{ url('/') }}">
        </section>
        <!-- End product section -->
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script>
        let baseUrl = $('#baseURL').val();

        function select_variation(product_id) {
            $('#selected_variation_id' + product_id).val('');
            $('#stock_qty_' + product_id).html(0);
            //$('#stock_qty_show'+product_id).html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('single.product.variation.check') }}",
                method: 'post',
                data: $('#variation_form' + product_id).serialize(),
                beforeSend: function() {
                    // $('.se-pre-con').show();
                },
                success: function(response) {
                    if (response.variation_status == 1) {
                        if (response.image != null) {
                            $('#variationImage').html('<img class="shadow border rounded" src="' + baseUrl +
                                '/images/product/' + response.image + '" width="250px" >');
                        } else {
                            $('#variationImage').html('');
                        }

                        $('#product_price_info' + product_id).html(response.price_info);
                        //$('#stock_qty_show'+product_id).html(response.qty+" "+response.unit_type);
                        if (response.qty > 0) {
                            $('#stock_qty_show' + product_id).html('Status: <b>In Stock</b>');
                        }
                        $('#selected_variation_id' + product_id).val(response.id);
                        $('#stock_qty_' + product_id).val(response.qty);
                        if (response.qty > 0) {
                            $('#add_to_cart_button' + product_id).show();
                            $('#buy_now_button' + product_id).show();
                            $('#notification_show' + product_id).hide();
                        } else {
                            $('#add_to_cart_button' + product_id).hide();
                            $('#buy_now_button' + product_id).hide();
                            $('#notification_show' + product_id).text('Out of Stock');
                            $('#notification_show' + product_id).show();
                        }
                        $('#SetVariantOutput_' + product_id).html(response.variation_name);
                    } else {
                        if (response.color_dependent_variation_status == 1) {
                            $('#SetColorName_' + product_id).html(response.color_name);
                            $('#single_variation_info_div' + product_id).html(response
                                .color_dependent_variation);
                        }
                    }
                }
            });
        }


        /*For Product Image Zoom*/

        function zoom(e) {
            var zoomer = e.currentTarget;
            e.offsetX ? offsetX = e.offsetX : offsetX = e.touches[0].pageX
            e.offsetY ? offsetY = e.offsetY : offsetX = e.touches[0].pageX
            x = offsetX / zoomer.offsetWidth * 100
            y = offsetY / zoomer.offsetHeight * 100
            zoomer.style.backgroundPosition = x + '% ' + y + '%';
        }

        /*End For Product Image Zoom*/



        // document.addEventListener('DOMContentLoaded', function() {
        //     const originalBtn = document.getElementById('scrollingAddToCart');
        //     const stickyBtn = document.getElementById('stickyAddToCart');
        //     const tabContent = document.querySelector('.tab_content');

        //     if (!originalBtn || !stickyBtn || !tabContent) return;

        //     const observer = new IntersectionObserver((entries) => {
        //         entries.forEach(entry => {
        //             const isIntersecting = entry.isIntersecting;
        //             originalBtn.classList.toggle('hide-original', isIntersecting);
        //             stickyBtn.classList.toggle('sticky-active', isIntersecting);
        //         });
        //     }, {
        //         threshold: 0.1,
        //         rootMargin: '0px'
        //     });

        //     observer.observe(tabContent);
        // });

        document.addEventListener('DOMContentLoaded', function() {
            const stickyContainer = document.querySelector('.sticky-addtocart-container');
            const tabsContainer = document.querySelector('.product_description_sticky');
            const stickyBtn = document.querySelector('#stickyAddToCart');

            window.addEventListener('scroll', function() {
                const tabsRect = tabsContainer.getBoundingClientRect();
                const isSticky = tabsRect.top <= 75;
                stickyBtn.classList.toggle('active', isSticky);
            });
        });
    </script>

@endsection
