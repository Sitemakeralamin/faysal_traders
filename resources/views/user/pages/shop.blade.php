@extends('user.inc.master')
@section('title')
    Shop
@endsection
@section('description')
    Shop, all-products, discount-products, offer-products, offer, new-year-offer
@endsection
@section('keywords')
    Shop, all-products, discount-products, offer-products, offer, new-year-offer
@endsection
@section('content')
    <style>
        .category-box {
            transition: all 0.3s ease;
            border-radius: 4px;
            border: 1px solid #dee2e6 !important;
        }

        .category-box:hover {
            background-color: #ED3833 !important;
            border-color: #ED3833 !important;
        }

        .category-box:hover a {
            color: #fff !important;
        }
    </style>
    @php($business_info = business_info())
    <form action="javascript:void(0)" id="filter_form">
        @csrf
        <input type="hidden" id="brand_array" name="brand_array" value="{{ $brand_id ?? '' }}">
        <input type="hidden" id="brand_id" name="brand_id" value="{{ $brand_id ?? '' }}">
        <input type="hidden" id="category_id" name="category_id" value="{{ $category_id ?? '' }}">
        <input type="hidden" id="search" name="search" value="{{ $search ?? '' }}">
        <input type="hidden" id="min_price" name="min_price" value="{{ $min_price ?? '' }}">
        <input type="hidden" id="max_price" name="max_price" value="{{ $max_price ?? '' }}">
        <input type="hidden" id="filter_data_json" name="filter_data_json" value="">

    </form>
    {{-- For Mobile --}}
    <div class="offcanvas__filter--sidebar widget__area">
        <button type="button" class="offcanvas__filter--close m-2" data-offcanvas="">
            <svg class="minicart__close--icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="32" d="M368 368L144 144M368 144L144 368"></path>
            </svg> <span class="offcanvas__filter--close__text">Close</span>
        </button>
        {{-- mobile_filter --}}
        <div class="offcanvas__filter--sidebar__inner" id="mobile_filterStop">
            @include('user.inc.shop_filter')
        </div>
    </div>

    <div style="background-color: {{ optional($business_info)->body_bg_color }} !important">
        <!-- Start shop section -->
        <section class="shop__section py-3">
            <div class="container-fluid">


                @if (optional($category)->description)
                    <div class="shop__header bg__gray--color p-2 mb-10" style="border-radius:10px;">
                        <table>
                            <tr>
                                <div class="descriptions">
                                    <div class="row p-2">
                                        <div class="col-md-12 my-3" style="line-height: 150%">
                                            {!! optional($category)->description !!}
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        </table>
                    </div>
                @endif



                {{-- If current $category has children, decide what to show --}}
                @if ($category && $category->children->count())
                    <div class="shop__header bg__gray--color p-3 mb-4 rounded shadow-sm">
                        <h5 class="mb-3 text-capitalize font-weight-bold text-dark border-bottom pb-2">
                            Explore More Categories
                        </h5>

                        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start">
                            @foreach ($category->children as $child)
                                <div class="category-box py-2 m-2 px-3 border">
                                    @if ($category->parent_id == 0)
                                        <a href="{{ route('products.sub.category', [
                                            'main_cat' => $category->slug,
                                            'sub_cat' => $child->slug,
                                        ]) }}"
                                            class="text-dark font-weight-bold d-block text-center text-decoration-none">
                                            {{ $child->title }}
                                        </a>
                                    @elseif($category->parent && $category->parent->parent_id == 0)
                                        <a href="{{ route('products.child.category', [
                                            'main_cat' => $category->parent->slug,
                                            'sub_cat' => $category->slug,
                                            'child_slug' => $child->slug,
                                        ]) }}"
                                            class="text-dark font-weight-bold d-block text-center text-decoration-none">
                                            {{ $child->title }}
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- End nested menu --}}




                <div class="shop__header bg__gray--color p-2 mb-10" style="border-radius:10px;">
                    <table>
                        <tr>
                            <td class="text-right align-items-right">
                                <button class="widget__filter--btn d-flex d-lg-none align-items-center" data-offcanvas="">
                                    <svg class="widget__filter--btn__icon" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="28"
                                            d="M368 128h80M64 128h240M368 384h80M64 384h240M208 256h240M64 256h80"></path>
                                        <circle cx="336" cy="128" r="28" fill="none" stroke="currentColor"
                                            stroke-linecap="round" stroke-linejoin="round" stroke-width="28"></circle>
                                        <circle cx="176" cy="256" r="28" fill="none" stroke="currentColor"
                                            stroke-linecap="round" stroke-linejoin="round" stroke-width="28"></circle>
                                        <circle cx="336" cy="384" r="28" fill="none" stroke="currentColor"
                                            stroke-linecap="round" stroke-linejoin="round" stroke-width="28"></circle>
                                    </svg>
                                    <span class="widget__filter--btn__text">Filter</span>
                                </button>
                            </td>
                            <td>



                                &nbsp;
                                @if (request()->query())
                                    Result for:
                                    {{-- Category --}}
                                    @if (request()->category_id)
                                        <span class="capsul">
                                            {{ \App\Models\Category::where('id', request()->category_id)->first()->title }}
                                        </span>
                                    @endif
                                    {{-- Brand --}}
                                    @if (request()->brand_id)
                                        <span id="brand_name_show"></span>
                                    @endif

                                    @if (request()->search)
                                        <span class="capsul">
                                            {{ request()->search }}
                                        </span>
                                    @endif

                                    @if (request()->min_price && request()->max_price)
                                        <span class="capsul">
                                            {{ env('CURRENCY') }}{{ request()->min_price }}
                                            <span class="price__divided"></span>

                                            {{ env('CURRENCY') }}{{ request()->max_price }}</span>
                                    @endif
                                @else
                                    <span>
                                        <a href="{{ url('/') }}">Home</a>

                                        @if (!empty($brandName))
                                            / <a
                                                href="{{ route('product.show', ['slug' => \Illuminate\Support\Str::slug($brandName)]) }}">{{ $brandName }}</a>
                                        @endif

                                        @if (!empty($mainCategoryName))
                                            / <a
                                                href="{{ route('product.show', ['slug' => \Illuminate\Support\Str::slug($mainCategoryName)]) }}">{{ $mainCategoryName }}</a>
                                        @endif

                                        @if (!empty($subCategoryName))
                                            / <a
                                                href="{{ route('products.sub.category', [
                                                    'main_cat' => \Illuminate\Support\Str::slug($mainCategoryName),
                                                    'sub_cat' => \Illuminate\Support\Str::slug($subCategoryName),
                                                ]) }}">{{ $subCategoryName }}</a>
                                        @endif

                                        @if (!empty($childCategoryName))
                                            / <a
                                                href="{{ route('products.child.category', [
                                                    'main_cat' => \Illuminate\Support\Str::slug($mainCategoryName),
                                                    'sub_cat' => \Illuminate\Support\Str::slug($subCategoryName),
                                                    'child_slug' => \Illuminate\Support\Str::slug($childCategoryName),
                                                ]) }}">{{ $childCategoryName }}</a>
                                        @endif

                                    </span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="row">

                    <div class="col-xl-3 col-lg-4">
                        {{-- desktop_filter --}}
                        <div class="shop__sidebar--widget widget__area d-none d-lg-block" id="desktop_filterStop">
                            @include('user.inc.shop_filter')
                        </div>
                    </div>

                    <div class="col-xl-9 col-lg-8">
                        <div class="shop__product--wrapper">
                            <div class="tab_content">
                                <div id="product_grid" class="tab_pane active show">
                                    <div class="product__section--inner product__grid--inner">
                                        <div class="row row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-2 mb--n30"
                                            id="product_body">

                                        </div>
                                        <div class="row mt-3" id="loading_div"></div>
                                        {{-- Load More --}}
                                        <div class="row mb-5 text-center mt-3" id="load_more_div" style="display: none;">
                                            <div class="cart-action mb-6 pt-3 pb-3">
                                                <a href="javascript:void(0)" type="button" onclick="load_more()"
                                                    class="continue__shipping--btn primary__btn border-radius-5"><i
                                                        class="w-icon-long-arrow-left"></i>Load More</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>


                @if (optional($category)->bottom_description)
                    <div class="shop__header bg__gray--color p-2 mb-10 mt-5" style="border-radius:10px;">
                        <table>
                            <tr>
                                <div class="descriptions">
                                    <div class="row p-2">
                                        <div class="col-md-12 my-3" style="line-height: 150%">
                                            {!! optional($category)->bottom_description !!}
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        </table>
                    </div>
                @endif

            </div>
        </section>
        <!-- End shop section -->
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

    <script>
        function selected_filters() {
            let filterData = {};

            // Gather selected filter options
            $('.filter-option:checked').each(function() {
                const headId = $(this).data('head');
                const optionId = $(this).val();

                if (!filterData[headId]) {
                    filterData[headId] = [];
                }

                filterData[headId].push(optionId);
            });

            // Save the filter data in hidden input for form submission
            $('#filter_data_json').val(JSON.stringify(filterData));
        }

        $(document).on('change, click', '.filter-option', function() {
            selected_filters(); // Update the filter data when filter options are changed
            order_ready(); // Trigger the AJAX request to fetch filtered products
        });



        //filter head

        $(document).ready(function() {
            order_ready();
        });

        $(".brands").click(function() {
            order_ready();
        });

        // $(document).on("change", '.brands, .filterPrice1', function() {
        //     order_ready();
        // })

        function selected_brands() {
            var brands = new Array();
            var brand_name = new Array();
            $('.brands:checked').each(function() {
                brands.push($(this).val());
                brand_name.push(this.getAttribute('data-title'));
            });
            if (brands.length > 0) {
                $('#brand_name_show').html('');
                $('#brand_array').val(brands);
                for (var i = 1; i < brand_name.length; i++) {
                    $('#brand_name_show').append('<span class="capsul">' + brand_name[i] + '</span> ');
                }
                //console.log(brand_name);
            } else {
                $('#brand_array').val(0);
                $('#brand_name_show').html('');
            }
        }

        function order_ready() {
            selected_brands();
            order_confirm();
            selected_filters();
        }


        function order_confirm() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('shop.products.data') }}",
                method: 'post',
                data: $('#filter_form').serialize(),

                beforeSend: function() {
                    $('#loading_divStop').html(
                        '<div class="col-md-12" style="width: 100% !important;"><div class="text-center p-10"><h2><b>Loading....</b></h2></div></div>'
                    );
                },
                success: function(response) {
                    console.log(response.output);
                    $('#loading_div').show();
                    $('#loading_div').html('');
                    $('#product_body').html(response.output);
                }
            });

        }


        //collapse all filter
        $(document).ready(function() {

            $('.filterPrice').on('click', function() {
                let collapsePrice = $('.collapsePrice');
                let caret = $(this).find('.caret-icon');

                collapsePrice.slideToggle(200);
                caret.toggleClass('caret-rotated');

                if (!collapsePrice.hasClass('show')) {
                    collapsePrice.removeClass('hide').addClass('show');
                } else {
                    collapsePrice.removeClass('show').addClass('hide');
                }

            });


            $('.filterPrice1').on('click', function() {
                let collapsePrice = $('.collapsePrice1');
                let caret = $(this).find('.caret-icon');

                collapsePrice.slideToggle(200);
                caret.toggleClass('caret-rotated');

                if (!collapsePrice.hasClass('show')) {
                    collapsePrice.removeClass('hide').addClass('show');
                } else {
                    collapsePrice.removeClass('show').addClass('hide');
                }

            });


            $('.filterPrice2').on('click', function() {
                let collapsePrice = $('.collapsePrice2');
                let caret = $(this).find('.caret-icon');

                collapsePrice.slideToggle(200);
                caret.toggleClass('caret-rotated');

                if (!collapsePrice.hasClass('show')) {
                    collapsePrice.removeClass('hide').addClass('show');
                } else {
                    collapsePrice.removeClass('show').addClass('hide');
                }

            });



            $(document).on('click', '.filterToggle', function() {
                let targetSelector = $(this).data('target');
                let target = $(targetSelector);
                let caret = $(this).find('.caret-icon');

                target.slideToggle(200);
                caret.toggleClass('caret-rotated');
            });




        });
    </script>

@endsection
