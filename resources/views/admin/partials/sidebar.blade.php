<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  {{-- <a href="" class="brand-link" target="_blank" style="background-color: #fff">
    <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image">
    <span class="brand-text font-weight-bold" style="color: #000;">{{ env('APP_NAME') }}</span>
  </a>  --}}

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->

    <div class="user-panel px-3 pb-2 my-1 d-flex">

      <div class="info">
        <a href="" class="d-block" style="font-size: 20px">{{ env('APP_NAME') }}</a>
      </div>
    </div>


    <!-- Sidebar Menu -->
    <nav class="mt-2">


@php
  $orderRoutes = ['order.index', 'order.status.filter', 'allOrder'];
@endphp

      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="{{ route('home') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>

        @if(user()->type == 1)

        <li class="nav-item {{ in_array(Route::currentRouteName(), $orderRoutes) ? 'menu-open' : '' }}">
          @php          
              $pending_orders = DB::table('orders')->where('order_status','pending')->count();              
          @endphp
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-plus-square"></i>
          <p>
            Orders 
            <i class="fas fa-angle-right right"></i>
          </p>
        </a>

        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="{{ route('order.index') }}" class="nav-link {{ request()->routeIs('order.index') ? 'active' : '' }}">
              <i class="fas fa-angle-right"></i>
              
              <p>All Orders</p>
            </a>
          </li>
          @php
               $wholesale_count = DB::table('wholesales')->count('id');
          @endphp
          <li class="nav-item">
              <a href="{{ route('allOrder') }}" class="nav-link {{ request()->routeIs('allOrder') ? 'active' : '' }}">
                <i class="fas fa-angle-right"></i>
                <p>All Wholesale Orders <span class="badge badge-light ms-1">{{$wholesale_count}}</span> </p> 
              </a>
            </li>
          @php
              $order_status = DB::table('orders')->select('order_status', DB::raw('count(*) as total'))->groupBy('order_status')->get();
          @endphp
          
          @foreach($order_status as $status)
              @php
                  $count = DB::table('orders')->where('order_status',  $status->order_status )->count();
              @endphp
          <li class="nav-item">
            <a href="{{ route('order.status.filter', $status->order_status) }}" class="nav-link {{ request()->routeIs('order.status.filter') && request()->route('order_status') === $status->order_status ? 'active' : '' }}">
              <i class="fas fa-angle-right"></i>
              <p>{{ $status->order_status }} <span class="badge badge-light ms-1">{{$count}}</span>  </p>
            </a>
          </li>
          @endforeach 
        </ul>
      </li>

      

      @php
        $productRoutes = ['product.index', 'product.create', 'setting.reward.point', 'brand.index', 'color.index', 'variation.index', 'flash.sale.index', 'product.generate.filter.head', 'category.index', 'category.create'];
      @endphp
      <li class="nav-item {{ in_array(Route::currentRouteName(), $productRoutes) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-th"></i>
          <p>
            Product
            <i class="fas fa-angle-right right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="{{ route('product.index') }}" class="nav-link {{ request()->routeIs('product.index') ? 'active' : ''  }}">
              <i class="fas fa-angle-right"></i>
              <p>Products List</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('product.create') }}" class="nav-link {{ request()->routeIs('product.create') ? 'active' : ''  }}">
              <i class="fas fa-angle-right"></i>
              <p>Add Product</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('product.stock') }}" class="nav-link {{ request()->routeIs('product.stock') ? 'active' : ''  }}">
              <i class="fas fa-angle-right"></i>
              <p>Product Stock List</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('category.index') }}" class="nav-link {{ request()->routeIs('category.index') ? 'active' : ''  }}">
              <i class="fas fa-angle-right"></i>
              <p>Category List</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('category.create') }}" class="nav-link {{ request()->routeIs('category.create') ? 'active' : ''  }}">
              <i class="fas fa-angle-right"></i>
              <p>Add Category</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('brand.index') }}" class="nav-link {{ request()->routeIs('brand.index') ? 'active' : ''  }}">
              <i class="fas fa-angle-right"></i>
              <p>Brand</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('color.index') }}" class="nav-link {{ request()->routeIs('color.index') ? 'active' : ''  }}">
              <i class="fas fa-angle-right"></i>
              <p>Colors</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('variation.index') }}" class="nav-link {{ request()->routeIs('variation.index') ? 'active' : ''  }}">
              <i class="fas fa-angle-right"></i>
              <p>Variation</p>
            </a>
          </li>
          <li class="nav-item d-none">
            <a href="{{ route('flash.sale.index') }}" class="nav-link {{ request()->routeIs('flash.sale.index') ? 'active' : ''  }}">
              <i class="fas fa-angle-right"></i>
              <p>Flash Sale</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('product.generate.filter.head') }}" class="nav-link {{ request()->routeIs('product.generate.filter.head') ? 'active' : ''  }}">
              <i class="fas fa-angle-right"></i>
              <p>Filter Head</p>
            </a>
          </li>

        </ul>
      </li> 


      @php
        $settingsRoutes = ['setting.index', 'about_us.index', 'setting.reward.point', 'slider.index', 'slider_side_banner.index', 'f.banner.show', 'page.index'];
      @endphp
        <li class="nav-item {{ in_array(Route::currentRouteName(), $settingsRoutes) ? 'menu-open' : ''}}">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cog"></i>
            <p>
              Settings
              <i class="fas fa-angle-right right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="{{ route('setting.index') }}" class="nav-link {{ request()->routeIs('setting.index') ? 'active' : ''}}">
                <i class="fas fa-angle-right"></i>
                <p>Shop Information</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{ route('about_us.index') }}" class="nav-link {{ request()->routeIs('about_us.index') ? 'active' : ''}}">
                <i class="fas fa-angle-right"></i>
                <p>About us</p>
              </a>
            </li>

            <li class="nav-item d-none">
              <a href="{{ route('setting.reward.point') }}" class="nav-link {{ request()->routeIs('setting.reward.point') ? 'active' : ''}}">
                <i class="fas fa-angle-right"></i>
                <p>Reward Point</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('slider.index') }}" class="nav-link {{ request()->routeIs('slider.index') ? 'active' : ''}}">
                <i class="fas fa-angle-right"></i>
                <p>Slider</p>
              </a>
            </li>
            {{-- <li class="nav-item">
              <a href="{{ route('slider_side_banner.index') }}" class="nav-link {{ request()->routeIs('slider_side_banner.index') ? 'active' : ''}}">
                <i class="fas fa-angle-right"></i>
                <p>Slider Side Banner</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('f.banner.show') }}" class="nav-link {{ request()->routeIs('f.banner.show') ? 'active' : ''}}">
                <i class="fas fa-angle-right"></i>
                <p>Home Middle Banner</p>
              </a>
            </li> --}}
            <li class="nav-item">
              <a href="{{ route('page.index') }}" class="nav-link {{ request()->routeIs('page.index') ? 'active' : ''}}">
                <i class="fas fa-angle-right"></i>
                <p>Pages</p>
              </a>
            </li>
            {{--
            <li class="nav-item">
              <a href="{{ route('gallery.index') }}" class="nav-link">
                <i class="fas fa-angle-right"></i>
                <p>Gallery</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{ route('referral.link.index') }}" class="nav-link">
                <i class="fas fa-angle-right"></i>
                <p>Referral Link</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link">
                <i class="fas fa-angle-right"></i>
                <p>Sponsors</p>
              </a>
            </li>
            --}}
          </ul>
        </li>


      @php
        $userManageRoutes = ['admin.index', 'customer.index'];
      @endphp

        <li class="nav-item {{ in_array(Route::currentRouteName(), $userManageRoutes) ? 'menu-open' : ''}}">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
              User Management
              <i class="fas fa-angle-right right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.index') }}" class="nav-link {{ request()->routeIs('admin.index') ? 'active' : ''}}">
                <i class="fas fa-angle-right"></i>
                <p>Adminstrators</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('customer.index') }}" class="nav-link {{ request()->routeIs('customer.index') ? 'active' : ''}}">
                <i class="fas fa-angle-right"></i>
                <p>Customers</p>
              </a>
            </li>
          </ul>
        </li>

        
        
        @php
          $reviewRoutes = ['product.review.index'];
        @endphp

        @php( $review_count = DB::table('products_reviews')->where(['is_active'=>0])->count('id') )
        <li class="nav-item {{ Route::currentRouteName() == 'product.review.index' ? 'menu-open' : '' }}">
          <a href="{{ route('product.review.index') }}" class="nav-link">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>
              Product Reviews
            </p> @if($review_count > 0)<span class="bg-primary px-2 p-1 rounded-pill text-light">{{$review_count}}</span>@endif
          </a>
        </li>


        <li class="nav-item {{ in_array(Route::currentRouteName(), ['coupon.index', 'registration.point.index']) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-percent"></i>
            <p>
              Campaign
              <i class="fas fa-angle-right right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="{{ route('coupon.index') }}" class="nav-link {{ request()->routeIs('coupon.index') ? 'active' : ''}}">
                <i class="fas fa-angle-right"></i>
                <p>Coupone</p>
              </a>
            </li>

            <li class="nav-item d-none">
              <a href="{{ route('registration.point.index') }}" class="nav-link {{ request()->routeIs('registration.point.index') ? 'active' : ''}}">
                <i class="fas fa-angle-right"></i>
                <p>Registration Point</p>
              </a>
            </li>

          </ul>
        </li>
        {{--
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-certificate"></i>
            <p>
              Affiliate
              <i class="fas fa-angle-right right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="{{ route('affiliate.request') }}" class="nav-link">
                <i class="fas fa-angle-right"></i>
                <p>Seller Requests</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('affiliate.payment.request') }}" class="nav-link">
                <i class="fas fa-angle-right"></i>
                <p>Payment Requests</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('affiliate.config') }}" class="nav-link">
                <i class="fas fa-angle-right"></i>
                <p>Configuration</p>
              </a>
            </li>

          </ul>
        </li>
        --}}

        <li class="nav-item {{ Route::currentRouteName() == 'admin.subscribers' ? 'menu-open' : '' }}">
          <a href="{{ route('admin.subscribers') }}" class="nav-link">
            <i class="nav-icon fas fa-bell-slash"></i>
            <p>
              Subscribers
            </p>
          </a>
        </li>

        <li class="nav-item {{ in_array(Route::currentRouteName(), ['district.index', 'area.index'] ) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-map-marker-alt"></i>
            <p>
              Location
              <i class="fas fa-angle-right right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="{{ route('district.index') }}" class="nav-link {{ request()->routeIs('district.index') ? 'active' : ''}}">
                <i class="fas fa-angle-right"></i>
                <p>District List</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('area.index') }}" class="nav-link {{ request()->routeIs('area.index') ? 'active' : ''}}">
                <i class="fas fa-angle-right"></i>
                <p>Area List</p>
              </a>
            </li>


          </ul>
        </li>


        <li class="nav-item {{ in_array(Route::currentRouteName(), ['blog.create', 'blog.list'] ) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tablets"></i>
            <p>
              Blog
              <i class="fas fa-angle-right right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="{{ route('blog.create') }}" class="nav-link {{ request()->routeIs('blog.create') ? 'active' : ''}}">
                <i class="fas fa-angle-right"></i>
                <p>Create Blog </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('blog.list') }}" class="nav-link {{ request()->routeIs('blog.list') ? 'active' : ''}}">
                <i class="fas fa-angle-right"></i>
                <p>Blog List</p>
              </a>
            </li>

          </ul>
        </li>


        <li class="nav-item {{ Route::currentRouteName() == 'user.profile' ? 'menu-open' : '' }}">
          <a href="{{ route('user.profile') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Profile
            </p>
          </a>
        </li>
        @endif

        @if(user()->type == 2)

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-plus-square"></i>
            <p>
              My Orders
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-heart"></i>
            <p>
              My Wishlist
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('customer.dashboard.wallet') }}" class="nav-link">
            <i class="nav-icon fas fa-money-bill-alt"></i>
            <p>
              My Wallet
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('user.profile') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Profile
            </p>
          </a>
        </li>
        @endif
        <div class="p-2"></div>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
