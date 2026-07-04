
@extends('user.inc.master')
@section('title')Order Info @endsection
@section('description')Order Info  @endsection
@section('keywords')Order Info @endsection
@section('content')
<style>
    @media only screen and (min-width: 768px) { 
        #text_end_on_desktop {
            text-align: right !important;
        }
    }
</style>

<section class="my__account--section py-5" id="printArea">
    <div class="container">
        <div class="my__account--section__inner p-3">
            <div class="row">
                <div class="col-lg-12">
                    <div class="account__wrapper account__wrapper--style4 border-radius-10">
                        <div class="account__content">
                            <div class="row">
                                <div class="col-lg-8 mb-3">
                                    <div class="cart__summary border-radius-10 mt-0 mb-3">
                                        @include('user.inc.order_confirmation_message')
                                    </div>
                                    <div class="cart__summary border-radius-10 mt-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="">
                                                    <p>Bill To,<br>
                                                        <b>Name:</b> {{optional($order)->name}} <br>
                                                        <b>Phone:</b> {{optional($order)->phone}} <br>
                                                        <b>Email:</b> {{optional($order)->email}}<br>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="text_end_on_desktop">
                                                <p>
                                                    <b>Order Code:</b> {{optional($order)->code}} <br>
                                                    <b>Date:</b> {{date("d M, Y", strtotime(optional($order)->created_at))}} <br>
                                                    <span class="text-info"><b>Status:</b> {{optional($order)->order_status}}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="table-responsive rounded p-4 ">
                                            <table class="cart__table--inner">
                                                <thead class="cart__table--header">
                                                    <tr class="cart__table--header__items">
                                                        <th width="45%" class="cart__table--header__list">Product</th>
                                                        <th class="cart__table--header__list">Price</th>
                                                        <th class="cart__table--header__list">Quantity</th>
                                                        <th class="cart__table--header__list">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="cart__table--body">
                                                    @if(count($order->order_product) > 0)
                                                    @foreach($order->order_product as $product)
                                                    @php
                                                        $variation_info = '';
                                                        if($product->variations <> 0) {
                                                            $stock_info = variation_stock_info($product->variations);
                                                            if(!is_null($stock_info)) {
                                                                if($stock_info->color <> null){
                                                                    $color_attribute_info = color_info($stock_info->color);
                                                                    $color_info = '<b>Color: </b>'.optional($color_attribute_info)->name.', ';
                                                                }
                                                                else {
                                                                    $color_info = '';
                                                                }
            
                                                                if($stock_info->variant <> null){
                                                                    $variant_attribute_info = variation_info($stock_info->variant);
                                                                    $attribute_info = '<b>'.optional($variant_attribute_info)->title.': </b>'.optional($stock_info)->variant_output.'';
                                                                }
                                                                else {
                                                                    $attribute_info = '';
                                                                }
                                                                $variation_info = $color_info.$attribute_info;
                                                            }
                                                        }
                                                    @endphp
                                                    
                                                    <tr class="cart__table--body__items mb-2">
                                                        <td class="cart__table--body__list">
                                                            <div class="cart__product d-flex align-items-center">
                                                                <button class="cart__remove--btn" aria-label="search button" type="submit">{{($loop->index) + 1}}</button>
                                                                <div class="cart__thumbnail">
                                                                    <a href="#"><img class="border-radius-5 shadow rounded p-1" src="{{ asset('images/product/'.optional($product->product)->thumbnail_image) }}" alt="cart-product"></a>
                                                                </div>
                                                                <div class="cart__content">
                                                                    <h4 class="cart__content--title"><a href="#">{{optional($product->product)->title}}</a></h4>
                                                                    <span class="cart__content--variant">{!!$variation_info!!}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="cart__table--body__list">
                                                            <span class="cart__price"><span class="current__price">৳{{number_format(optional($product)->price, 2)}}</span></span>
                                                        </td>
                                                        <td class="cart__table--body__list">
                                                            {{optional($product)->qty}} {{optional($product->product)->unit_type}}
                                                        </td>
                                                        <td class="cart__table--body__list">
                                                            <span class="cart__price end">৳{{number_format(optional($product)->total, 2)}}</span>
                                                        </td>
                                                    </tr>
                                                    
                                                    @endforeach
                                                    @endif                                                                                                             
                                                 </tbody>
                                            </table>
                                        </div>
                                        {{-- Print --}}
                                        <div class="row text-right">
                                            <span>
                                                <a href="{{ route('order.invoice.generate', $order->id) }}" 
                                                    class="btn btn-outline-primary btn-lg float-right" style="margin-right: 5px;" target="blank">
                                                    
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                                    Print Invoice</a>
                                                </span>
                                        </div>
                                    </div>
                                    
                                    
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <div class="cart__summary border-radius-10 mt-0" >
                                        <div class="cart__summary--total mb-20">
                                            <table class="cart__summary--total__table">
                                                <tbody>
                                                    <tr class="cart__summary--total__list">
                                                        <td class="cart__summary--total__title text-left">Subtotal</td>
                                                        <td class="cart__summary--amount text-right">৳{{number_format(optional($order)->price, 2)}}</td>
                                                    </tr>
                                                    @if($order->coupon_discount_amount > 0)
                                                    <tr class="cart__summary--total__list">
                                                        <td class="cart__summary--total__title text-left">Discount</td>
                                                        <td class="cart__summary--amount text-right">৳{{number_format(optional($order)->coupon_discount_amount, 2)}}</td>
                                                    </tr>
                                                    @endif
                                                    <tr class="cart__summary--total__list">
                                                        <td class="cart__summary--total__title text-left">Shipping Charge 
                                                            @if (env('DELIVERY_CHARGE_ADVANCED') == true) 
                                                            (<span class="text-success">Advanced</span>) 
                                                                @endif
                                                                 </td>
                                                        <td class="cart__summary--amount text-right">৳{{number_format(optional($order)->delivery_charge, 2)}}</td>
                                                    </tr>
                                                    <tr class="cart__summary--total__list">
                                                        <td class="cart__summary--total__title text-left">Total</td>
                                                        <td class="cart__summary--amount text-right">৳{{number_format(optional($order)->total_payable, 2)}}</td>
                                                    </tr>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                    </div> 
                                </div>
                                <div class="col-lg-8 mb-3">
                                    <div class="cart__summary border-radius-10 mt-0">
                                        <p>
                                            <b>Shipping Area:</b> {{optional($order->district_info)->name}}<br>
                                            <b>Shipping Address:</b> {{optional($order)->shipping_address}}<br>
                                            <b>Note:</b> {{optional($order)->note}}
                                        </p>
                                    </div>
                                    
                                </div>
                            </div>
    
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</section>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function printSpecialArea() {
        $('#printButton').hide();
        var printContent = document.getElementById("printArea").innerHTML;
        var originalContent = document.body.innerHTML;
        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
        $('#printButton').show();
    }
</script>
@endsection