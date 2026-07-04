<style>
    .footer__section {
        background-color: #f8f9fd !important;
        padding: 40px 0 0;
    }
    .footer__widget--title {
        color: #000 !important;
        font-size: 18px !important;
        font-weight: 600 !important;
        margin-bottom: 15px !important;
    }
    .footer__widget--menu__text {
        color: #333 !important;
        font-size: 16px !important;
        transition: color 0.3s !important;
    }
    .footer__widget--menu__text:hover {
        color: #e92026 !important;
    }
    .main__footer {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 40px !important;
    }
    .footer__widget--width {
        flex: 1;
        min-width: 200px;
    }
    .footer__widget--menu__wrapper {
        flex-direction: column !important;
    }
    .footer__widget--inner {
        display: flex !important;
        flex-direction: column !important;
        gap: 12px !important;
    }

    .footer-bottom {
        border-top: 1px solid #e0e0e0;
        padding: 15px 0px;
        text-align: center;
        background: var(--pick-purpule);
        color: #f7f9fc;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .main__footer {
            flex-direction: column;
            gap: 30px;
        }

        .footer-bottom {
            margin-bottom: 60px;
        }
    }

    @media (max-width: 991px) {
       .footer-bottom {
            margin-bottom: 60px;
        } 
    }
</style>

<!-- Start footer section -->
<footer class="footer__section">
    <div class="container-fluid">
        <div class="main__footer">
            <!-- About Column -->
            <div class="footer__widget footer__widget--width">
                <h2 class="footer__widget--title h3">About</h2>
                <ul class="footer__widget--menu footer__widget--inner">
                    <li class="footer__widget--menu__list"><a class="footer__widget--menu__text" href="/about-us">About us</a></li>
                    <li class="footer__widget--menu__list"><a class="footer__widget--menu__text" href="/shopping-carts">Shopping Cart</a></li>
                    <li class="footer__widget--menu__list"><a class="footer__widget--menu__text" href="/register">Register</a></li>
                    {{-- <li class="footer__widget--menu__list"><a class="footer__widget--menu__text" href="https://maps.app.goo.gl/SDBSs9dPERETPQsu6">Find Our Store</a></li> --}}

<li class="footer__widget--menu__list">
    <a class="footer__widget--menu__text" href="https://maps.app.goo.gl/SDBSs9dPERETPQsu6">Find Our Store (Mirpur 11)</a>
</li>
<li class="footer__widget--menu__list">
    <a class="footer__widget--menu__text" href="https://maps.app.goo.gl/xbvhfadqqrsNmUCK6">Find Our Store (Rupnagar)</a>
</li>

                </ul>
            </div>

            <!-- Help Column -->
            <div class="footer__widget footer__widget--width">
                <h2 class="footer__widget--title h3">Help</h2>
                <ul class="footer__widget--menu footer__widget--inner">
                    <li class="footer__widget--menu__list"><a class="footer__widget--menu__text" href="/contact-us">Contact us</a></li>
                    <li class="footer__widget--menu__list"><a class="footer__widget--menu__text" href="/shop">Shopping</a></li>
                </ul>
            </div>



            <!-- Resources Column -->
            <div class="footer__widget footer__widget--width">
                <h2 class="footer__widget--title h3">Resources</h2>
                <ul class="footer__widget--menu footer__widget--inner">
                    <li class="footer__widget--menu__list"><a class="footer__widget--menu__text" href="/latest-news">Latest News</a></li>
                    <li class="footer__widget--menu__list"><a class="footer__widget--menu__text" href="/track-order">Track order</a></li>
                </ul>
            </div>

            <!-- Legal Column -->
            <div class="footer__widget footer__widget--width">
                <h2 class="footer__widget--title h3">Law and order</h2>
                <ul class="footer__widget--menu footer__widget--inner">
                    <li class="footer__widget--menu__list"><a class="footer__widget--menu__text" href="/privacy_policy">Privacy Policy</a></li>
                    <li class="footer__widget--menu__list"><a class="footer__widget--menu__text" href="/terms_and_condition">Trade-in Terms and Conditions</a></li>
                    <li class="footer__widget--menu__list"><a class="footer__widget--menu__text" href="/support_policy">Support Policy</a></li>
                    <li class="footer__widget--menu__list"><a class="footer__widget--menu__text" href="/return_and_refund_policy">Return Refund Policy</a></li>
                </ul>
            </div>
        </div>

    </div>
    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div>&copy; Copyright {{ date('Y') }}. Developed By {{ env('APP_NAME') }}</div>
    </div>
</footer>
<!-- End footer section -->