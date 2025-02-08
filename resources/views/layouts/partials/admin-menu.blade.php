<!--- Dashboard --->
<li>
    <a class="menu {{ $request->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
        <span>
            <i class="fa-solid fa-house menu-icon"></i>
            {{ __('Dashboard') }}
        </span>
    </a>
</li>

<li class="menu-divider">
    <span class="menu-title">{{ __('PROMOTION MANAGEMENT') }}</span>
</li>
<!--- banner--->
<li>
    <a class="menu {{ $request->routeIs('admin.banner.*') ? 'active' : '' }}" href="{{ route('admin.banner.index') }}">
        <span>
            <i class="fa-solid fa-image menu-icon"></i>
            {{ __('Banner') }}
        </span>
    </a>
</li>

<!--- banner--->
<li>
    <a class="menu {{ $request->routeIs('admin.ad.*') ? 'active' : '' }}" href="{{ route('admin.ad.index') }}">
        <span>
            <i class="fa-solid fa-photo-film menu-icon"></i>
            {{ __('Ads') }}
        </span>
    </a>
</li>

<!--- Coupon discount--->
<li>
    <a class="menu {{ $request->routeIs('admin.coupon.*') ? 'active' : '' }}"
        href="{{ route('admin.coupon.index') }}">
        <span>
            <i class="fa-solid fa-ticket menu-icon"></i>
            {{ __('Coupon') }}
        </span>
    </a>
</li>

<!--- notification--->
<li>
    <a class="menu {{ $request->routeIs('admin.customerNotification.*') ? 'active' : '' }}"
        href="{{ route('admin.customerNotification.index') }}">
        <span>
            <i class="fa-solid fa-bell menu-icon"></i>
            {{ __('Notifications') }}
        </span>
    </a>
</li>

<li class="menu-divider">
    <span class="menu-title">{{ __('ORDER MANAGEMENT') }}</span>
</li>

<!--- Orders --->
<li>
    <a class="menu {{ request()->routeIs('admin.order.*') ? 'active' : '' }}" data-bs-toggle="collapse"
        href="#ordersMenu">
        <span>
            <i class="fa-solid fa-cart-shopping menu-icon"></i>
            {{ __('All Orders') }}
        </span>
        <img src="{{ asset('assets/icons/arrowDown.svg') }}" alt="" class="downIcon">
    </a>
    <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.order.*') ? 'show' : '' }}" id="ordersMenu">
        <div class="listBar">
            @php
                use App\Enums\OrderStatus;
                $orderStatuses = OrderStatus::cases();
            @endphp
            <a href="{{ route('admin.order.index') }}"
                class="subMenu hasCount {{ request()->url() === route('admin.order.index') ? 'active' : '' }}">
                {{ __('All') }} <span class="count statusAll">{{ $allOrders > 99 ? '99+' : $allOrders }}</span>
            </a>
            @foreach ($orderStatuses as $status)
                <a href="{{ route('admin.order.index', str_replace(' ', '_', $status->value)) }}"
                    class="subMenu hasCount {{ request()->url() === route('admin.order.index', str_replace(' ', '_', $status->value)) ? 'active' : '' }}">
                    <span>{{ __($status->value) }}</span>
                    <span
                        class="count status{{ Str::camel($status->value) }}">{{ ${Str::camel($status->value)} > 99 ? '99+' : ${Str::camel($status->value)} }}</span>
                </a>
            @endforeach
        </div>
    </div>
</li>

@if ($businessModel == 'multi')
    <li class="menu-divider">
        <span class="menu-title">{{ __('SHOP MANAGEMENT') }}</span>
    </li>
    <!--- shops --->
    <li>
        <a href="{{ route('admin.shop.index') }}"
            class="menu {{ request()->routeIs('admin.shop.*') ? 'active' : '' }}">
            <span>
                <i class="fa-solid fa-shop menu-icon"></i>
                {{ __('All Shops') }}
            </span>
        </a>
    </li>

    <!--- admin Shop products --->
    <li>
        <a class="menu {{ request()->routeIs('admin.product.index') ? 'active' : '' }}" data-bs-toggle="collapse"
            href="#shopProducts">
            <span>
                <i class="fa-solid fa-basket-shopping menu-icon"></i>
                {{ __('Shop Products') }}
            </span>
            <img src="{{ asset('assets/icons/arrowDown.svg') }}" alt="" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.product.index') ? 'show' : '' }}"
            id="shopProducts">
            <div class="listBar">
                @if ($generaleSetting?->new_product_approval)
                    <a href="{{ route('admin.product.index', 'status=0') }}"
                        class="subMenu {{ request()->filled('status') && request()->status == 0 ? 'active' : '' }}"
                        title="{{ __('New Products Requests') }}">
                        {{ __('New Products Requests') }}
                    </a>
                @endif

                @if ($generaleSetting?->update_product_approval)
                    <a href="{{ route('admin.product.index', 'status=1') }}"
                        class="subMenu {{ request()->filled('status') && request()->status == 1 ? 'active' : '' }}"
                        title="{{ __('Products Update Requests') }}">
                        {{ __('Products Update Requests') }}
                    </a>
                @endif

                <a href="{{ route('admin.product.index', 'approve=true') }}"
                    class="subMenu {{ request()->filled('approve') && request()->approve == 'true' ? 'active' : '' }}"
                    title="{{ __('Approved Products') }}">
                    {{ __('Approved Products') }}
                </a>
            </div>
        </div>
    </li>
@endif

<li class="menu-divider">
    <span class="menu-title">{{ __('USER MANAGEMENT') }}</span>
</li>
<!--- rider --->
<li>
    <a class="menu {{ $request->routeIs('admin.rider.*') ? 'active' : '' }}" href="{{ route('admin.rider.index') }}">
        <span>
            <i class="bi bi-bicycle menu-icon"></i>
            {{ __('Riders') }}
        </span>
    </a>
</li>

<!--- customers --->
<li>
    <a class="menu {{ $request->routeIs('admin.customer.*') ? 'active' : '' }}"
        href="{{ route('admin.customer.index') }}">
        <span>
            <i class="fa-solid fa-user menu-icon"></i>
            {{ __('Customers') }}
        </span>
    </a>
</li>

<li class="menu-divider">
    <span class="menu-title">{{ __('Business management') }}</span>
</li>
<!--- Settings --->
<li>
    <a class="menu {{ request()->routeIs('admin.generale-setting.*', 'admin.business-setting.*', 'admin.socialLink.*', 'admin.themeColor.*', 'admin.deliveryCharge.*', 'admin.ticketissuetype.*') ? 'active' : '' }}"
        data-bs-toggle="collapse" href="#setings">
        <span>
            <i class="bi bi-gear-fill menu-icon"></i>
            {{ __('Buisness Settings') }}
        </span>
        <img src="{{ asset('assets/icons/arrowDown.svg') }}" alt="" class="downIcon">
    </a>
    <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.generale-setting.*', 'admin.business-setting.*', 'admin.socialLink.*', 'admin.themeColor.*', 'admin.deliveryCharge.*', 'admin.ticketissuetype.*') ? 'show' : '' }}"
        id="setings">
        <div class="listBar">
            <a href="{{ route('admin.generale-setting.index') }}"
                class="subMenu {{ request()->routeIs('admin.generale-setting.index') ? 'active' : '' }}">
                {{ __('General Settings') }}
            </a>

            <a href="{{ route('admin.business-setting.index') }}"
                class="subMenu {{ request()->routeIs('admin.business-setting.*') ? 'active' : '' }}">
                {{ __('Business Setup') }}
            </a>

            <a href="{{ route('admin.deliveryCharge.index') }}"
                class="subMenu {{ request()->routeIs('admin.deliveryCharge.*') ? 'active' : '' }}">
                {{ __('Delivery Charge') }}
            </a>

            <a href="{{ route('admin.themeColor.index') }}"
                class="subMenu {{ request()->routeIs('admin.themeColor.*') ? 'active' : '' }}">
                {{ __('Theme Colors') }}
            </a>

            <a href="{{ route('admin.socialLink.index') }}"
                class="subMenu {{ request()->routeIs('admin.socialLink.index') ? 'active' : '' }}">
                {{ __('Social Links') }}
            </a>

            <a href="{{ route('admin.ticketissuetype.index') }}"
                class="subMenu {{ request()->routeIs('admin.ticketissuetype.index') ? 'active' : '' }}">
                {{ __('Ticket Issue Types') }}
            </a>
        </div>
    </div>
</li>

<!--- legal pages --->
<li>
    <a class="menu {{ request()->routeIs('admin.legalpage.*', 'admin.contactUs.*') ? 'active' : '' }}"
        data-bs-toggle="collapse" href="#legalPages">
        <span>
            <i class="fa-solid fa-bookmark menu-icon"></i>
            {{ __('Legal Pages') }}
        </span>
        <img src="{{ asset('assets/icons/arrowDown.svg') }}" alt="" class="downIcon">
    </a>
    <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.legalpage.*', 'admin.contactUs.*') ? 'show' : '' }}"
        id="legalPages">
        <div class="listBar">
            @use(App\Models\LegalPage)
            @foreach (LegalPage::all() as $legalPage)
                <a href="{{ route('admin.legalpage.index', $legalPage->slug) }}"
                    class="subMenu {{ request()->fullUrl() === route('admin.legalpage.edit', $legalPage->slug) || request()->fullUrl() === route('admin.legalpage.index', $legalPage->slug) ? 'active' : '' }}"
                    title="{{ $legalPage->title }}">
                    {{ __($legalPage->title) }}
                </a>
            @endforeach

            <a href="{{ route('admin.contactUs.index') }}"
                class="subMenu {{ request()->routeIs('admin.contactUs.*') ? 'active' : '' }}">
                {{ __('Contact Us') }}
            </a>

        </div>
    </div>
</li>

<li>
    <a class="menu {{ request()->routeIs('admin.pusher.*', 'admin.mailConfig.*', 'admin.paymentGateway.*', 'admin.sms-gateway.*', 'admin.firebase.*') ? 'active' : '' }}"
        data-bs-toggle="collapse" href="#thirdpartConfig" title="Third Party configuration">
        <span>
            <i class="bi bi-boxes menu-icon"></i>
            {{ __('3rd Party Config') }}
        </span>
        <img src="{{ asset('assets/icons/arrowDown.svg') }}" alt="" class="downIcon">
    </a>
    <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.pusher.*', 'admin.mailConfig.*', 'admin.paymentGateway.*', 'admin.sms-gateway.*', 'admin.firebase.*') ? 'show' : '' }}"
        id="thirdpartConfig">
        <div class="listBar">
            <a href="{{ route('admin.paymentGateway.index') }}"
                class="subMenu {{ request()->routeIs('admin.paymentGateway.*') ? 'active' : '' }}">
                {{ __('Payment Gateway') }}
            </a>

            <a href="{{ route('admin.sms-gateway.index') }}"
                class="subMenu {{ request()->routeIs('admin.sms-gateway.*') ? 'active' : '' }}">
                {{ __('SMS Gateway') }}
            </a>

            <a href="{{ route('admin.pusher.index') }}"
                class="subMenu {{ request()->routeIs('admin.pusher.*') ? 'active' : '' }}">
                {{ __('Pusher Setup') }}
            </a>

            <a href="{{ route('admin.mailConfig.index') }}"
                class="subMenu {{ request()->routeIs('admin.mailConfig.*') ? 'active' : '' }}">
                {{ __('Mail Config') }}
            </a>

            <a href="{{ route('admin.firebase.index') }}"
                class="subMenu {{ request()->routeIs('admin.firebase.*') ? 'active' : '' }}">
                {{ __('Firebase Notification') }}
            </a>

        </div>
    </div>
</li>

@if ($businessModel == 'multi')
    <li class="menu-divider">
        <span class="menu-title">{{ __('ACCOUNTS') }}</span>
    </li>
    <!--- withdraw --->
    <li>
        <a class="menu {{ $request->routeIs('admin.withdraw.*') ? 'active' : '' }}"
            href="{{ route('admin.withdraw.index') }}">
            <span>
                <i class="bi bi-wallet2 menu-icon"></i>
                {{ __('Withdraws') }}
            </span>
        </a>
    </li>
@endif

<li class="menu-divider">
    <span class="menu-title">{{ __('LANGUAGE MANAGEMENT') }}</span>
</li>
<!--- Languages --->
<li>
    <a href="{{ route('admin.language.index') }}"
        class="menu {{ request()->routeIs('admin.language.*') ? 'active' : '' }}">
        <span>
            <i class="fa-solid fa-language menu-icon"></i>
            {{ __('Languages') }}
        </span>
    </a>
</li>


<li class="menu-divider">
    <span class="menu-title">{{ __('Supports') }}</span>
</li>
<!--- Support Tickets --->
<li>
    <a href="{{ route('admin.supportTicket.index') }}"
        class="menu {{ request()->routeIs('admin.supportTicket.*') ? 'active' : '' }}">
        <span>
            <i class="bi bi-ticket-perforated menu-icon"></i>
            {{ __('Support Tickets') }}
        </span>
    </a>
</li>

<!--- Support Messages --->
<li>
    <a href="{{ route('admin.support.index') }}"
        class="menu {{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
        <span>
            <i class="bi bi-chat-right-text menu-icon"></i>
            {{ __('Support Messages') }}
        </span>
    </a>
</li>
