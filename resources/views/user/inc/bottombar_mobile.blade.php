<style>
        .offcanvas__stikcy--toolbar__label {
        margin-top: 15px !important;
        margin-left: 5px !important;
    }
</style>
@php
    $svg_width="32";
    $svg_height="32";
@endphp
<div class="offcanvas__stikcy--toolbar">
    <ul class="d-flex justify-content-between">
        {{-- Home --}}
        <li class="offcanvas__stikcy--toolbar__list">
            <a class="offcanvas__stikcy--toolbar__btn" href="{{ route('index') }}">
            <span class="offcanvas__stikcy--toolbar__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="{{ $svg_width }}" height="{{ $svg_height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                </span>
            <span class="offcanvas__stikcy--toolbar__label">Home</span>
            </a>
        </li>
        {{-- Wishlist --}}
        <li class="offcanvas__stikcy--toolbar__list">
            <a class="offcanvas__stikcy--toolbar__btn" href="{{route('customer.wishlist')}}">
                <span class="offcanvas__stikcy--toolbar__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="{{ $svg_width }}" height="{{ $svg_height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </span>
                <span class="offcanvas__stikcy--toolbar__label">Wishlist</span>
                {{-- <span class="items__count">{{$wishlist_count}}</span>  --}}
            </a>
        </li>
        {{-- Cart --}}
        <li class="offcanvas__stikcy--toolbar__list">
            <a class="offcanvas__stikcy--toolbar__btn minicart__open--btn" href="javascript:void(0)" data-offcanvas>
                <span class="offcanvas__stikcy--toolbar__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="{{ $svg_width }}" height="{{ $svg_height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                </span>
                <span class="offcanvas__stikcy--toolbar__label">Cart</span>
                <span class="items__count" id="cart_count_3">0</span>
            </a>
        </li>
        {{-- Shop --}}
        <li class="offcanvas__stikcy--toolbar__list">
            <a class="offcanvas__stikcy--toolbar__btn" href="{{route('products')}}">
            <span class="offcanvas__stikcy--toolbar__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="{{ $svg_width }}" height="{{ $svg_height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                </span>
            <span class="offcanvas__stikcy--toolbar__label">Shop</span>
            </a>
        </li>
        {{-- Search --}}
        <li class="offcanvas__stikcy--toolbar__list d-none">
            <a class="offcanvas__stikcy--toolbar__btn search__open--btn" href="javascript:void(0)" data-offcanvas>
                <span class="offcanvas__stikcy--toolbar__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="{{ $svg_width }}" height="{{ $svg_height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </span>
            <span class="offcanvas__stikcy--toolbar__label">Search</span>
            </a>
        </li>
        <li class="offcanvas__stikcy--toolbar__list ">
            <a class="offcanvas__stikcy--toolbar__btn" @if(Auth::check()) href="{{ route('customer.account') }}" @else href="{{ route('login') }}" @endif>
                <span class="offcanvas__stikcy--toolbar__icon">
                    <svg xmlns="http://www.w3.org/2000/svg"  
                    width="{{ $svg_width }}" height="{{ $svg_height }}"
                    viewBox="0 0 512 512"><path d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/></svg>
                </span>    
                <span class="offcanvas__stikcy--toolbar__label">
                    @auth
                        {{ substr(user()->name,0,7) }}
                    @else
                        Account
                    @endauth
                </span>
            </a>
        </li>
    </ul>
</div>