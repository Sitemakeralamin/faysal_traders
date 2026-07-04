
<!-- Start slider section -->
<div class="container-fluid">
    <div class="row slider-p pb-0">
        <div class="col-md-12 slider-pb">
            <section class="hero__slider--section">
                <div class="hero__slider--inner hero__slider--activation swiper">
                    <div class="hero__slider--wrapper swiper-wrapper">
                        @foreach($sliders as $slider) 
                        <div class="swiper-slide">
                            <div class="hero__slider--items home1__slider--bg" style="
                                background:url('{{ asset('images/slider/'.$slider->image ) }}'); 
                                ">
                                <a href="{{ $slider->link }}" class="" style="width:100%; height:100%">
                                <div class="container-fluid">
                                    <div class="hero__slider--items__inner">
                                        <div class="row row-cols-1">
                                            <div class="col">
                                                {{--
                                                <div class="slider__content">
                                                    <h2 class="slider__content--maintitle h1"><b>{{ $slider->title }}</b></h2>
                                                    {!! $slider->description !!}
                                                    <a class="slider__btn primary__btn" href="{{ $slider->link }}">Show Collection</a>
                                                </div>
                                                --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </a>

                            </div>
                        </div>
                        @endforeach
                        
                    </div>
                    <div class="swiper__nav--btn swiper-button-next">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    <div class="swiper__nav--btn swiper-button-prev">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div> 
<!-- End slider section -->