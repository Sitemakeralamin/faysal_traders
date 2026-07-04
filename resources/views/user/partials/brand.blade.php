<section class="section-spacing" style="background-color: #000435">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mH20">
            <h2 class="h3 mb-0 text-white">Discover Our Global Brands</h2>
            <div class="d-flex gap-2">
                <button class="swiper-nav-btn brand-prev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="swiper-nav-btn brand-next">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Swiper container -->
        <div class="swiper brand-swiper mt-4">
            <div class="swiper-wrapper">
                @foreach($brands as $brand)
                    <div class="swiper-slide">
                        <a href="{{ route('product.show', $brand->slug) }}">
                            <div class="brand-card text-center p-3">
                                <div class="brand-image mb-3">
                                    <img src="{{ asset('images/brand/' . $brand->image) }}"
                                         alt="{{ $brand->title ?? '' }}"
                                         class="img-fluid">
                                </div>
                                <h3 class="h6 mb-0 text-white">{{ $brand->title }}</h3>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
