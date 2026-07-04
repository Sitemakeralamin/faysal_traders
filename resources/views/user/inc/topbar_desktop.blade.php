<style>
    :root{
        --pick-primary: #232323 !important;
    }
    .header__topbar {padding: 5px !important;}
    /* .header__topbar {
    background-color:#f2f3f7;
    } */

    .header_topbar_text_color{
        color:#f7f8fc !important;
    }

    .header_topbar_text_color:hover{
        color:#f7f8fc !important;
    }
    /* .header__shipping--text__link:hover{color:var(--logo-color)} */

    .header__shipping--text__link:hover{color: inherit !important}

.header__search--input {
    width: 100%;
    border: 1px solid #757679;
}

.header__search--button {
    background-color: var(--pick-purpule);
    color:#f7f8fc;
}


.header__search--button:hover{
    background-color: var(--pick-purpule) !important;
}

.header__account--btn{
     color: var(--pick-primary);
}

</style>
<div class="header__topbar d-sm-2-none" style="background-color: var(--pick-headerfooter)">
    <div class="container-fluid">
        <div class="header__topbar--inner d-flex align-items-center justify-content-between">
            <div class="header__shipping">
                <ul class="header__shipping--wrapper d-flex">
                    {{-- Call --}}
                    <li class="header__shipping--text header_topbar_text_color"><a href="tel:{{optional($business)->phone}}" class="header__shipping--text__link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        {{optional($business)->phone}}</a></li>
                        {{-- Track Order --}}
                    <li class="header__shipping--text header_topbar_text_color">
                         <a class="header__shipping--text__link" href="mailto:{{optional($business)->email}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            {{optional($business)->email}}</a></li>
                    <li class="header__shipping--text header_topbar_text_color"><a href="{{ route('order.track') }}" class="header__shipping--text__link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                        Track Your Order</a></li>
                </ul>
            </div>
            <div class="language__currency">
                <ul class="d-flex align-items-center">
                    {{-- Account --}}
                    @if(Auth::check())
                    <li class="header__shipping--text header_topbar_text_color d-lg-block">
                        <a href="{{ route('customer.account') }}" class="header__shipping--text__link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            {{ user()->name }} </a></li>
                    @else
                    <li class="header__shipping--text header_topbar_text_color d-lg-block">
                    <a href="{{ route('login') }}" class="header__shipping--text__link"> 
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                        Login </a></li>
                        <li class="header__shipping--text header_topbar_text_color d-lg-block">
                        <a href="{{ route('register') }}" class="header__shipping--text__link"> 
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                        Register </a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
