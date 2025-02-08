<!---################################--->
<!-- ////// Header Navbar  ////// -->
<!---################################--->
<div class="shop-nav">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.business-setting.index') ? 'active' : '' }}"
                href="{{ route('admin.business-setting.index') }}">
                {{ __('Basic Info') }}
            </a>
        </li>

        @if ($businessModel == 'multi')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.business-setting.shop') ? 'active' : '' }}"
                    href="{{ route('admin.business-setting.shop') }}">
                    {{ __('Shops') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.business-setting.withdraw') ? 'active' : '' }}"
                    href="{{ route('admin.business-setting.withdraw') }}">
                    {{ __('Withdraw') }}
                </a>
            </li>
        @endif
    </ul>
</div>
