<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('_backendDashboardIndex') }}" class="app-brand-link">
            <img src="{{ app('request')->input('app_store_logo') }}" class="m-1" style="max-width: 10rem;" />
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <li class="menu-item @if ('dashboard' == app('request')->input('menu_route')) active @endif">
            <a href="{{ route('_backendDashboardIndex') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home "></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>
        <li class="menu-item @if (request()->routeIs('_searchCustomersIndex')) active @endif">
            <a href="{{ route('_searchCustomersIndex') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-search"></i>
                <div>Search Customers</div>
            </a>
        </li>

        <li class="menu-item @if (request()->routeIs('_customers*') && request('type') == 'all') active @endif">
            <a href="{{ route('_customersIndex', ['type' => 'all']) }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div>All Customers</div>
            </a>
        </li>
        {{-- <li
            class="menu-item {{ request()->routeIs('_customers*') && request('type') == 'personal' ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-user"></i>
                <div>PL Customers</div>
            </a>

            <ul class="menu-sub">
                <li
                    class="menu-item {{ request()->routeIs('_customers*') && request('type') == 'personal' && request('login_type') == 'self' ? 'active' : '' }}">
                    <a href="{{ route('_customersIndex', ['type' => 'personal', 'login_type' => 'self']) }}"
                        class="menu-link">
                        <div>Self</div>
                    </a>
                </li>

                <li
                    class="menu-item {{ request()->routeIs('_customers*') && request('type') == 'personal' && request('login_type') == 'consultant' ? 'active' : '' }}">
                    <a href="{{ route('_customersIndex', ['type' => 'personal', 'login_type' => 'consultant']) }}"
                        class="menu-link">
                        <div>Hire Agent</div>
                    </a>
                </li>
            </ul>
        </li>
        <li
            class="menu-item {{ request()->routeIs('_customers*') && request('type') == 'business' ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-building-bank"></i>
                <div>BL Customers</div>
            </a>

            <ul class="menu-sub">
                <li
                    class="menu-item {{ request()->routeIs('_customers*') && request('type') == 'business' && request('login_type') == 'self' ? 'active' : '' }}">
                    <a href="{{ route('_customersIndex', ['type' => 'business', 'login_type' => 'self']) }}"
                        class="menu-link">
                        <div>Self</div>
                    </a>
                </li>

                <li
                    class="menu-item {{ request()->routeIs('_customers*') && request('type') == 'business' && request('login_type') == 'consultant' ? 'active' : '' }}">
                    <a href="{{ route('_customersIndex', ['type' => 'business', 'login_type' => 'consultant']) }}"
                        class="menu-link">
                        <div>Hire Agent</div>
                    </a>
                </li>
            </ul>
        </li> --}}
        <li class="menu-item {{ request()->routeIs('_personalLoan*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-user"></i>
                <div>PL Applications</div>
            </a>

            <ul class="menu-sub">
                <li
                    class="menu-item {{ request()->routeIs('_personalLoan*') && request('login_type', 'self') == 'self' ? 'active' : '' }}">
                    <a href="{{ route('_personalLoanIndex', ['type' => 'personal', 'login_type' => 'self']) }}"
                        class="menu-link">
                        <div>Self</div>
                    </a>
                </li>

                <li
                    class="menu-item {{ request()->routeIs('_personalLoan*') && request('login_type') == 'consultant' ? 'active' : '' }}">
                    <a href="{{ route('_personalLoanIndex', ['type' => 'personal', 'login_type' => 'consultant']) }}"
                        class="menu-link">
                        <div>Hire Agent</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item {{ request()->routeIs('_businessLoan*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-building-bank"></i>
                <div>BL Applications</div>
            </a>

            <ul class="menu-sub">
                <li
                    class="menu-item {{ request()->routeIs('_businessLoan*') && request('login_type', 'self') == 'self' ? 'active' : '' }}">
                    <a href="{{ route('_businessLoanIndex', ['type' => 'business', 'login_type' => 'self']) }}"
                        class="menu-link">
                        <div>Self</div>
                    </a>
                </li>

                <li
                    class="menu-item {{ request()->routeIs('_businessLoan*') && request('login_type') == 'consultant' ? 'active' : '' }}">
                    <a href="{{ route('_businessLoanIndex', ['type' => 'business', 'login_type' => 'consultant']) }}"
                        class="menu-link">
                        <div>Hire Agent</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item @if (request()->routeIs(['_creditCardIndex', '_creditCardView', '_creditCardAddStatus'])) active @endif">
            <a href="{{ route('_creditCardIndex') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-credit-card"></i>
                <div data-i18n="Page 2">Credit Card</div>
            </a>
        </li>

        {{-- <li class="menu-item @if ('subscriptions' == app('request')->input('menu_route')) active @endif">
            <a href="{{ route('_subscriptionsIndex') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-id-badge-2"></i>
                <div data-i18n="Page 2">Subscriptions</div>
            </a>
        </li> --}}
        <li class="menu-item @if ('transactions' == app('request')->input('menu_route')) active @endif">
            <a href="{{ route('_transactionsIndex') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-currency-taka"></i>
                <div data-i18n="Page 2">Transactions</div>
            </a>
        </li>
        <li class="menu-item @if ('invoices' == app('request')->input('menu_route')) active @endif">
            <a href="{{ route('_invoicesIndex') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-receipt"></i>
                <div data-i18n="Page 2">Invoice</div>
            </a>
        </li>
        {{--
        <li class="menu-item @if (in_array(app('request')->input('menu_route'), ['report-gst'])) active @endif">
            <a href="{{ route('_gstReportIndex') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-receipt-2"></i>
                <div data-i18n="Page 2"> GST Report </div>
            </a>
        </li>
        --}}
        <li class="menu-item @if (in_array(app('request')->input('menu_route'), ['remarketing-cycle'])) active @endif">
            <a href="{{ route('_remarketingCycleIndex') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-refresh"></i>
                <div data-i18n="Page 2"> Remarketing Cycle </div>
            </a>
        </li>
        <li class="menu-item @if (in_array(app('request')->input('menu_route'), ['remarketing-logs'])) active @endif">
            <a href="{{ route('_remarketingLogsIndex') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-refresh-dot"></i>
                <div data-i18n="Page 2"> Remarketing Log </div>
            </a>
        </li>
        <li class="menu-item @if (in_array(app('request')->input('menu_route'), ['dnd-customers', 'dnd-customer'])) active @endif">
            <a href="{{ route('_dndCustomersIndex') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-lock-access-off"></i>
                <div data-i18n="Page 2"> DND Customers </div>
            </a>
        </li>
        <li class="menu-item @if (in_array(app('request')->input('menu_route'), ['sms-message'])) active @endif">
            <a href="{{ route('_smsMessageIndex') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-message-circle-code"></i>
                <div data-i18n="Page 2"> SMS Messages </div>
            </a>
        </li>
        <li class="menu-item @if (in_array(app('request')->input('menu_route'), ['otp-logs'])) active @endif">
            <a href="{{ route('_otpLogsIndex') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-device-mobile-message"></i>
                <div data-i18n="Page 2"> OTP Logs </div>
            </a>
        </li>
        <li class="menu-item @if ('support' == app('request')->input('menu_route')) active @endif">
            <a href="{{ route('_supportPostIndex') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-user-question"></i>
                <div data-i18n="Page 2"> Support Request </div>
            </a>
        </li>
        <li class="menu-item @if ('settings' == app('request')->input('menu_route')) active @endif">
            <a href="{{ route('_webOptionPostIndex') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-settings-bolt"></i>
                <div data-i18n="Page 2"> Settings </div>
            </a>
        </li>
        @php
            /*
        <li class="menu-item @if (in_array(app('request')->input('menu_route'), ['marketing-manual'])) active @endif">
            <a href="{{ route('_manualMarketingIndex') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-badge-ad"></i>
                <div data-i18n="Page 2"> Manual Marketing </div>
            </a>
        </li>
        <li class="menu-item @if ('message-onfiguration' == app('request')->input('menu_route')) active @endif">
            <a href="{{ route('_messageConfigurationPostIndex') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-message-cog"></i>
                <div data-i18n="Page 2"> Message Config. </div>
            </a>
        </li>
        */
        @endphp
    </ul>
</aside>
