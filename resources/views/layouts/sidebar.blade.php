@php
    $request = request();
@endphp
<div class="app-sidebar">
    <div class="scrollbar-sidebar">
        <div class="branding-logo">
            @php
                $url = request()->is('admin', 'admin/*') ? route('admin.dashboard') : route('shop.dashboard');
            @endphp
            <a href="{{ $url }}">
                <img src="{{ $generaleSetting?->logo ?? asset('assets/logo.png') }}" alt="logo" loading="lazy" />
            </a>
        </div>
        <div class="branding-logo-forMobile">
            <a href="{{ asset('assets/logo.png') }}"></a>
        </div>
        <div class="app-sidebar-inner">
            <ul class="vertical-nav-menu">
                @if (request()->is('admin', 'admin/*'))
                    @hasanyrole('admin|root')
                        @include('layouts.partials.admin-menu')
                    @endhasanyrole
                @else
                    @role('shop')
                        @include('layouts.partials.shop-menu')
                    @endrole
                @endif
            </ul>
        </div>
        <div class="sideBarfooter">
            <button type="button" class="fullbtn hite-icon" onclick="toggleFullScreen(document.body)">
                <i class="fa-solid fa-expand"></i>
            </button>
            @if (request()->is('admin', 'admin/*'))
                <a href="{{ route('admin.generale-setting.index') }}" class="fullbtn hite-icon">
                    <i class="fa-solid fa-cog"></i>
                </a>
            @endif
            <a href="#" class="fullbtn hite-icon">
                <i class="fa-solid fa-user"></i>
            </a>
            <a href="javascript:void(0)" class="fullbtn hite-icon logout">
                <i class="fa-solid fa-power-off"></i>
            </a>
        </div>
    </div>
</div>
