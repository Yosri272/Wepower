<!---################################--->
<!-- ////// Shop Header Navbar  ////// -->
<!---################################--->
<div class="shop-nav">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.shop.show') ? 'active' : '' }}"
                href="{{ route('admin.shop.show', $shop->id) }}">
                {{ __('Shop overview') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.shop.orders') ? 'active' : '' }}" href="{{ route('admin.shop.orders', $shop->id) }}">
                {{ __('Order') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.shop.products') ? 'active' : '' }}" href="{{ route('admin.shop.products', $shop->id) }}">
                {{ __('Product') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.shop.category') ? 'active' : '' }}" href="{{ route('admin.shop.category', $shop->id) }}">
                {{ __('Category') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.shop.reviews') ? 'active' : '' }}" href="{{ route('admin.shop.reviews', $shop->id) }}">
                {{ __('Review') }}
            </a>
        </li>
    </ul>
</div>
