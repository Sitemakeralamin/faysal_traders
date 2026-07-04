
@extends('user.inc.master')
@section('title'){{$slug}} products @endsection
@section('description'){{$slug}} products @endsection
@section('keywords'){{$slug}} products @endsection
@section('content')
@php( $business_info = business_info() )
<div style="background-color: {{ optional($business_info)->body_bg_color }} !important">
<section class="product__section section--padding pt-0" style="padding-bottom: 6rem !important;">
    <div class="container-fluid">
        
        <div class="section__heading mb-3 mt-4 d-flex single-product-bg-info rounded">
            <h4> 
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                @if (isset($slug) && !empty($slug))
                        @if ($slug=='New-Collection') New Collection 
                    @elseif ($slug=='Trending-Now') Trending Now
                    @elseif ($slug=='Todays-Deal') Todays Deal
                    @elseif ($slug=='Best-Selling') Best Selling Products
                    @endif
                @endif 
            </h4>
        </div>
        <div class="product__section--inner mt-4">
            <div class="row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 mb--n30">
                @foreach($products as $product)
                    @include('user.partials.product')
                @endforeach
            </div>
            <div class="pagination__area bg__gray--color text-center mt-1">
                <nav class="pagination justify-content-center">
                    {{ $products->links('user.partials.pagination') }}
                </nav>
                <div style="margin-top: 10px !important;">
                    <span class="fw-bold">Showing {{$products->firstItem()}} to {{$products->lastItem()}} of {{$products->total()}} Products</span>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection