@php
    $featured_categories = featured_categories();
@endphp
<header class="header__section"> 
    {{-- Topbar 16 26 35 --}}
    @include('user.inc.topbar_desktop')
    <style>
        /* .header_text_color{
            color: <?php echo optional($business)->header_text_color; ?> !important;
        } */
    </style>
    {{--Middle Menu--}}
    <div class="main__header header__sticky sticky">
        <div class="container-fluid">
            <div class="main__header--inner position__relative d-flex justify-content-between align-items-center">
                {{--Mobile: Leftside category menu--}}
                <div class="offcanvas__header--menu__open ">
                    <a class="offcanvas__header--menu__open--btn" href="javascript:void(0)" data-offcanvas>
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon offcanvas__header--menu__open--svg" viewBox="0 0 512 512"><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M80 160h352M80 256h352M80 352h352"/></svg>
                        <span class="visually-hidden">Menu Open</span>
                    </a>
                </div>
                {{-- Logo --}}
                <div class="main__logo"> {{--  d-none header__sticky--none d-lg-block --}}
                    <h1 class="main__logo--title"><a class="main__logo--link" href="{{ route('index') }}"><img class="main__logo--img" src="{{ asset('images/website/'.optional($business)->header_logo) }}" alt="{{optional($business)->name}}"></a></h1>
                </div>
                {{--Desktop:Search menu--}}
                <div class="header__search--widget d-none d-lg-block header__sticky--none">
                    <form class="d-flex header__search--form" action="{{ route('products') }}">
                        {{-- <div class="header__select--categories select">
                            <select name="category_id" class="header__select--inner" id="d1_product_search_category">
                                <option selected value="">All Categories</option>
                                @foreach($featured_categories->take(8) as $categories)
                                    <option value="{{$categories->id}}">{{$categories->title}}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="header__search--box">
                            <label>
                                <input class="header__search--input" id="d1_product_search" oninput="search_product('d1')" placeholder="Search here..." type="text" name="search" value="{{ request()->search }}">
                            </label>
                            <button class="header__search--button header_text_color" type="submit" aria-label="search button">
                                <svg class="header__search--button__svg" xmlns="http://www.w3.org/2000/svg" width="27.51" height="26.443" viewBox="0 0 512 512"><path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"></path><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448"></path></svg>
                            </button>
                            <div class="search_product_output mt-2" id="search_product_output_d1_main" style="display:none">
                                <div class="row" id="search_product_output_d1">

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="header__menu d-none header__sticky--block d-lg-block">
                    <nav class="header__menu--navigation">
                        <ul class="d-flex">
                            @foreach($featured_categories as $category)
                                @if(count($category->menu_child) > 0)
                                    <li class="header__menu--items style2">
                                        <a class="header__menu--link header_text_color" href="{{route('product.show', $category->slug) }}">
                                            {{$category->title}} 
                                            <svg class="menu__arrowdown--icon" xmlns="http://www.w3.org/2000/svg" width="12" height="7.41" viewBox="0 0 12 7.41">
                                                <path d="M16.59,8.59,12,13.17,7.41,8.59,6,10l6,6,6-6Z" transform="translate(-6 -8.59)" fill="currentColor" opacity="0.7"></path>
                                            </svg>
                                        </a>
                                        <ul class="header__sub--menu">
                                            @foreach($category->menu_child as $p_category)
                                                <li class="header__sub--menu__items">
                                                    <a href="{{route('products.sub.category', ['main_cat' => $category->slug, 'sub_cat' => $p_category->slug])}}" class="header__sub--menu__link">{{$p_category->title}}
                                                    </a>
                                                    @if($p_category->children->count() > 0)
                                                        <ul class="header__sub--menu">
                                                            @foreach($p_category->children as $child)
                                                                <li class="header__sub--menu__items">
                                                                    <a href="{{ route('products.child.category', [
                                                                        'main_cat' => $category->slug,
                                                                        'sub_cat' => $p_category->slug,
                                                                        'child_slug' => $child->slug
                                                                    ]) }}" class="header__sub--menu__link">
                                                                        {{ $child->title }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                <li class="header__menu--items style2">
                                        <a class="header__menu--link header_text_color" href="{{ route('product.show', $category->slug) }}">      
                                            {{$category->title}}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </nav>
                </div>
                @php
                $svg_width="32";
                $svg_height="32";
                @endphp
                <div class="header__account"> {{--  header__sticky--none --}}

                    <ul class="d-flex">
                        {{--Mobile: Search Button--}}
                        <li class="header__account--items d-none header__sticky--block d-lg-block">{{-- big-screen-none --}}
                            <a class="header__account--btn search__open--btn" href="javascript:void(0)" data-offcanvas>
                            
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                width="{{ $svg_width }}" height="{{ $svg_height }}" 
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            
                            <span class="header__account--btn__text header_text_color">Search</span>
                            </a>
                        </li>
                        {{--Desktop: My Account--}}
                        <li class="header__account--items d-none header__sticky--none">{{--big-screen-none--}}
                            <a class="header__account--btn" @if(Auth::check()) href="{{ route('customer.account') }}" @else href="{{ route('login') }}" @endif>
                                <svg xmlns="http://www.w3.org/2000/svg"  
                                width="{{ $svg_width }}" height="{{ $svg_height }}"
                                viewBox="0 0 512 512"><path d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/></svg>
                                <span class="header__account--btn__text header_text_color">My Account</span>
                            </a>
                        </li>
                        {{--Desktop: Wish list--}}
                        <li class="header__account--items d-none header__sticky--none d-lg-block ">
                            <a class="header__account--btn" href="{{route('customer.wishlist')}}">
                                <svg  xmlns="http://www.w3.org/2000/svg" 
                                width="{{ $svg_width }}" height="{{ $svg_height }}"
                                viewBox="0 0 512 512"><path d="M352.92 80C288 80 256 144 256 144s-32-64-96.92-64c-52.76 0-94.54 44.14-95.08 96.81-1.1 109.33 86.73 187.08 183 252.42a16 16 0 0018 0c96.26-65.34 184.09-143.09 183-252.42-.54-52.67-42.32-96.81-95.08-96.81z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"></path></svg>
                                <span class="header__account--btn__text header_text_color"> Wish List</span>
                                <span class="items__count wishlist header_text_color">{{$wishlist_count}}</span>
                            </a>
                        </li>
                        {{--Desktop: My Cart--}}
                        <li class="header__account--items d-none d-lg-block">
                            <a class="header__account--btn minicart__open--btn" href="javascript:void(0)" data-offcanvas>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                width="{{ $svg_width }}" height="{{ $svg_height }}"
                                viewBox="0 0 14.706 13.534">
                                    <g  transform="translate(0 0)">
                                      <g >
                                        <path  data-name="Path 16787" d="M4.738,472.271h7.814a.434.434,0,0,0,.414-.328l1.723-6.316a.466.466,0,0,0-.071-.4.424.424,0,0,0-.344-.179H3.745L3.437,463.6a.435.435,0,0,0-.421-.353H.431a.451.451,0,0,0,0,.9h2.24c.054.257,1.474,6.946,1.555,7.33a1.36,1.36,0,0,0-.779,1.242,1.326,1.326,0,0,0,1.293,1.354h7.812a.452.452,0,0,0,0-.9H4.74a.451.451,0,0,1,0-.9Zm8.966-6.317-1.477,5.414H5.085l-1.149-5.414Z" transform="translate(0 -463.248)" fill="currentColor"/>
                                        <path  data-name="Path 16788" d="M5.5,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,5.5,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,6.793,478.352Z" transform="translate(-1.191 -466.622)" fill="currentColor"/>
                                        <path  data-name="Path 16789" d="M13.273,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,13.273,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,14.566,478.352Z" transform="translate(-2.875 -466.622)" fill="currentColor"/>
                                      </g>
                                    </g>
                                </svg>
                                <span class="header__account--btn__text header_text_color"> My cart</span>
                                <span class="items__count header_text_color" id="cart_count_1">0</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>
       
        /* Base menu item */
        .header__menu--items {
            position: relative;
        }


        /* First-level dropdown */
        .header__sub--menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 200px;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            z-index: 1000;
            padding: 0;
            list-style: none;
            border-radius: 0px !important;
        }

        /* Show dropdown on hover */
        .header__menu--items:hover > .header__sub--menu,
        .header__sub--menu__items:hover > .header__sub--menu {
            display: block;
        }

        /* Second-level dropdown (submenu of submenu) */
        .header__sub--menu__items {
            position: relative;
        }

        .header__sub--menu__items > .header__sub--menu {
            top: 0;
            left: 100%;
            margin-left: 0;
            position: absolute;
            min-width: 200px;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: none;
            white-space: nowrap;
            z-index: 999;
        }

        /* Hover to show child dropdown */
        .header__sub--menu__items:hover > .header__sub--menu {
            display: block;
        }

        /* Menu links */
        .header__sub--menu__link {
            display: block;
            padding: 10px 15px;
            color: <?php echo optional($business)->header_bottom_text_color; ?> !important;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        /* Hover effect */
        .header__sub--menu__link:hover {
            background-color: var(--logo-color);
            color: #fff !important;
        }

        .submenu-arrow {
            margin-left: 6px;
            vertical-align: middle;
        }

    </style>


    {{-- Category Menu None Sticky --}}
    <div class="header__bottom shadow">
        <div class="container-fluid align-items-center" style="background-color: <?php echo  optional($business)->header_bottom_bg_color; ?> !important">{{-- #F36D21 --}}
            <div class="header__bottom--inner position__relative d-none d-lg-flex justify-content-between align-items-center">
                <div class="header__menu text-align">
                    <nav class="header__menu--navigation">
                        <ul class="d-flex">
                            {{-- 2 step category menu --}}
                            @foreach($featured_categories as $category)
                            @if(count($category->menu_child) > 0)
                                <li class="header__menu--items">
                                    <a class="header__menu--link" href="{{route('product.show', $category->slug) }}">
                                        {{$category->title}}
                                        <svg class="menu__arrowdown--icon d-none" xmlns="http://www.w3.org/2000/svg" width="12" height="7.41" viewBox="0 0 12 7.41">
                                            <path d="M16.59,8.59,12,13.17,7.41,8.59,6,10l6,6,6-6Z" transform="translate(-6 -8.59)" fill="currentColor" opacity="0.7"></path>
                                        </svg>
                                    </a>
                                    <ul class="header__sub--menu">
                                        @foreach($category->menu_child as $p_category)
                                        <li class="header__sub--menu__items">
                                            <a href="{{route('products.sub.category', ['main_cat' => $category->slug, 'sub_cat' => $p_category->slug])}}" class="header__sub--menu__link">{{$p_category->title}}
                                            </a>

                                            @if($p_category->children->count() > 0)
                                                <ul class="header__sub--menu">
                                                    @foreach($p_category->children as $child)
                                                        <li class="header__sub--menu__items">
                                                            <a href="{{ route('products.child.category', [
                                                                'main_cat' => $category->slug,
                                                                'sub_cat' => $p_category->slug,
                                                                'child_slug' => $child->slug
                                                            ]) }}" class="header__sub--menu__link">
                                                                {{ $child->title }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif

                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="header__menu--items">
                                    <a class="header__menu--link" href="{{ route('product.show', $category->slug) }}">{{$category->title}}</a>
                                </li>
                            @endif
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>



    <!-- Mobile Menu Start Offcanvas header menu -->
    <div class="offcanvas__header">
        <div class="offcanvas__inner">
            <div class="offcanvas__logo" style="background-color: <?php echo optional($business)->header_bg_color; ?> !important;">
                <a class="offcanvas__logo_link" href="{{ route('index') }}">
                    <img src="{{ asset('images/website/'.optional($business)->header_logo) }}" alt="Grocee Logo" width="62">
                </a>
                <button class="offcanvas__close--btn" data-offcanvas>close</button>
            </div>
            <nav class="offcanvas__menu">
                <ul class="offcanvas__menu_ul">
                    {{-- three step categories menu --}}
                    @foreach($featured_categories as $category)
                        @if(count($category->menu_child) > 0)
                            <li class="offcanvas__menu_li">

                                <a class="offcanvas__menu_item" href="{{ route('product.show', $category->slug) }}">{{$category->title}}</a>

                                <ul class="offcanvas__sub_menu">
                                    @foreach($category->menu_child as $p_category)
                                    <li class="offcanvas__sub_menu_li">
                                        <a href="{{route('products.sub.category', ['main_cat' => $category->slug, 'sub_cat' => $p_category->slug])}}" class="offcanvas__sub_menu_item">{{$p_category->title}}</a>

                                        @if(count($p_category->menu_child) > 0)
                                        <ul class="offcanvas__sub_menu">
                                            @foreach($p_category->menu_child as $inner_sub_category)
                                            <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="{{ route('products.child.category', [
                                                'main_cat' => $category->slug,
                                                'sub_cat' => $p_category->slug,
                                                'child_slug' => $inner_sub_category->slug
                                            ]) }}">{{$inner_sub_category->title}}</a></li>
                                            @endforeach
                                        </ul>
                                        @endif

                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="offcanvas__menu_li">
                                <a class="offcanvas__menu_item" href="{{ route('product.show', $category->slug) }}">{{$category->title}}</a>
                            </li>
                        @endif
                    @endforeach


                    {{-- three step categories menu --}}
                    <li class="offcanvas__menu_li">
                        <a class="offcanvas__menu_item" href="{{route('wholesale')}}">Wholesale</a>
                    </li>

                </ul>
                {{--
                <div class="offcanvas__account--items">
                    <a class="offcanvas__account--items__btn d-flex align-items-center" href="login.html">
                    <span class="offcanvas__account--items__icon">
                        <svg xmlns="http://www.w3.org/2000/svg"  width="20.51" height="19.443" viewBox="0 0 512 512"><path d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/></svg>
                    </span>
                    <span class="offcanvas__account--items__label">Login / Register</span>
                    </a>
                </div>
                --}}
            </nav>
        </div>
    </div>
    <!-- End Offcanvas header menu -->

    <!-- Start Offcanvas stikcy toolbar / Mobile bottom navigation -->
    @include('user.inc.bottombar_mobile')
    
    <!-- End Offcanvas stikcy toolbar / Mobile bottom navigation -->

    <!-- Start offCanvas minicart / side cart -->
    <div class="offCanvas__minicart">
        <div class="minicart__header ">
            <div class="minicart__header--top d-flex justify-content-between align-items-center">
                <h2 class="minicart__title h3"> Shopping Carts</h2>
                <button class="minicart__close--btn" aria-label="minicart close button" data-offcanvas>
                    <svg class="minicart__close--icon" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 512 512"><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"/></svg>
                </button>
            </div>
        </div>

        <div id="side_cart_info">


        </div>

        <div class="minicart__button d-flex justify-content-center mt-3">
            <a class="primary__btn minicart__button--link" href="{{ route('carts') }}">View cart</a>
            <a class="primary__btn minicart__button--link" href="{{ route('checkout') }}">Checkout</a>
        </div>
    </div>
    <!-- End offCanvas minicart -->

    <!-- Start serch box area -->
    <div class="predictive__search--box ">
        <div class="predictive__search--box__inner">
            <h2 class="predictive__search--title">Search Products</h2>
            <form class="predictive__search--form" action="{{ route('products') }}">
                <input type="hidden" name="category_id" value="">
                <label>
                    <input class="predictive__search--input" id="d2_product_search" oninput="search_product('d2')" placeholder="Search Here" type="text" name="search"  value="{{ request()->search }}">
                </label>
                <button class="predictive__search--button" aria-label="search button" type="submit"><svg class="header__search--button__svg" xmlns="http://www.w3.org/2000/svg" width="30.51" height="25.443" viewBox="0 0 512 512"><path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448"/></svg>  </button>
                <div class="search_product_output">
                    <div class="row" id="search_product_output_d2">

                    </div>
                </div>
            </form>

        </div>
        <button class="predictive__search--close__btn" aria-label="search close button" data-offcanvas>
            <svg class="predictive__search--close__icon" xmlns="http://www.w3.org/2000/svg" width="40.51" height="30.443"  viewBox="0 0 512 512"><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"/></svg>
        </button>
    </div>
    <!-- End serch box area -->

</header>
