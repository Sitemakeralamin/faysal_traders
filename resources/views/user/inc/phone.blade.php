<style>
     #phone__top{position:fixed;
        bottom:150px;
        right:25px;z-index:99;outline:0;background-color: var(--secondary-color);-webkit-box-shadow:0 2px 22px rgba(0,0,0,.16);box-shadow:0 2px 22px rgba(0,0,0,.16);cursor:pointer;-webkit-transform:translateY(50px);transform:translateY(50px);opacity:0;visibility:hidden;-webkit-transition:.3s;transition:.3s;line-height:1;width:60px;height:60px;line-height:1;border-radius:50%;border:0}
    #phone__top:hover{background: var(--secondary-color)}
    #phone__top.active{visibility:visible;opacity:1;-webkit-transform:translateY(0);transform:translateY(0)}
    #phone__top svg{width:60px; margin-top: 13px; line-height:1}

    a.phone {
        color: var(--white-color);
    }
    a.phone:hover {
        background-color: var(--secondary-color) ;
    }

    .phone-pulse {
        width: 60px;
        height: 60px;
        right: 10px;
        bottom: 100px;
        background: var(--secondary-color);
        position: fixed;
        text-align: center;
        color: #ffffff;
        cursor: pointer;
        border-radius: 50%;
        z-index: 99;
        display: inline-block;
        line-height: 65px;
    }

    .phone-pulse:before {
        position: absolute;
        content: " ";
        z-index: -1;
        bottom: -15px;
        right: -15px;
        background-color: var(--secondary-color);
        width: 90px;
        height: 90px;
        border-radius: 100%;
        animation-fill-mode: both;
        -webkit-animation-fill-mode: both;
        opacity: 0.6;
        -webkit-animation: pulse 1s ease-out;
        animation: pulse 1.8s ease-out;
        -webkit-animation-iteration-count: infinite;
        animation-iteration-count: infinite;
    }
    
</style>

    {{-- <a id="phone__top" class="active phone phone-pulse" href="tel:{{optional($business)->phone}}">
        <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
    </a> --}}