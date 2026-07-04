@extends('user.inc.master')
@php($business_info = business_info())

@section('title')
    Home
@endsection
@section('description')
    {{ optional($business_info)->meta_description }}
@endsection
@section('keywords')
    {{ optional($business_info)->meta_keywords }}
@endsection

@section('content')
    <div style="background-color: {{ optional($business_info)->body_bg_color }} !important">

        @include('user.partials.slider')

        <!-- Recommended Products Section -->
        @include('user.partials.recomanded_product')

        <!-- Global Brands Section -->
        @include('user.partials.brand')

        @include('user.partials.feature_deals')

        <!-- Recent Products Section -->
        @include('user.partials.recent_product')

        @include('user.partials.subscribe')

    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Recommended Products Swiper
            new Swiper('.recommended-swiper', {
                slidesPerView: 4,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.recommended-next',
                    prevEl: '.recommended-prev',
                },
                breakpoints: {
                    320: {
                        slidesPerView: 2
                    },
                    576: {
                        slidesPerView: 2
                    },
                    768: {
                        slidesPerView: 3
                    },
                    992: {
                        slidesPerView: 4
                    }
                }
            });



            // Initialize Reccnt Products Swiper
            new Swiper('.recent-swiper', {
                slidesPerView: 4,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.recent-next',
                    prevEl: '.recent-prev',
                },
                breakpoints: {
                    320: {
                        slidesPerView: 1
                    },
                    576: {
                        slidesPerView: 1
                    },
                    768: {
                        slidesPerView: 3
                    },
                    992: {
                        slidesPerView: 4
                    }
                }
            });

            // Initialize Related Products Swiper
            new Swiper('.related-slider', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.slider-next',
                    prevEl: '.slider-prev',
                },
                breakpoints: {
                    576: {
                        slidesPerView: 2
                    },
                    992: {
                        slidesPerView: 3
                    }
                }
            });

            //init brand swiper
            new Swiper('.brand-swiper', {
                slidesPerView: 6,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.brand-next',
                    prevEl: '.brand-prev',
                },
                breakpoints: {
                    320: {
                        slidesPerView: 2
                    },
                    576: {
                        slidesPerView: 3
                    },
                    768: {
                        slidesPerView: 4
                    },
                    992: {
                        slidesPerView: 5
                    },
                    1200: {
                        slidesPerView: 6
                    }
                }
            });

            // Countdown Timer (example implementation)
            function updateCountdown() {
                // This is just a demo - implement your actual countdown logic here
                const countdownBoxes = document.querySelectorAll('.countdown-box');
                if (countdownBoxes.length > 0) {
                    // In a real implementation, you would calculate actual time remaining
                    countdownBoxes.forEach(box => {
                        const currentValue = parseInt(box.textContent);
                        const newValue = currentValue > 0 ? currentValue - 1 : 59;
                        box.textContent = newValue.toString().padStart(2, '0');
                    });
                }
            }
            setInterval(updateCountdown, 1000);
        });
    </script>
@endsection
