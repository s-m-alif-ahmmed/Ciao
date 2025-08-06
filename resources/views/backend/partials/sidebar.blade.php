@php
    $systemSetting = App\Models\SystemSetting::first();
@endphp

<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="{{ route('dashboard') }}">
                <img src="{{ asset($systemSetting->logo ?? 'frontend/eVento_logo.png') }}"
                    class="header-brand-img desktop-logo" alt="logo">
                <img src="{{ asset($systemSetting->favicon ?? 'frontend/eVento_logo.png') }}"
                    class="header-brand-img toggle-logo" alt="logo">
                <img src="{{ asset($systemSetting->favicon ?? 'frontend/eVento_logo.png') }}"
                    class="header-brand-img light-logo" alt="logo">
                <img src="{{ asset($systemSetting->logo ?? 'frontend/eVento_logo.png') }}"
                    class="header-brand-img light-logo1" alt="logo">
            </a>
        </div>

        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg>
            </div>

            <ul class="side-menu">
                <li class="slide">
                    <a class="side-menu__item has-link" data-bs-toggle="slide" href="{{ route('dashboard') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon"
                            enable-background="new 0 0 24 24" viewBox="0 0 24 24">
                            <path
                                d="M19.9794922,7.9521484l-6-5.2666016c-1.1339111-0.9902344-2.8250732-0.9902344-3.9589844,0l-6,5.2666016C3.3717041,8.5219116,2.9998169,9.3435669,3,10.2069702V19c0.0018311,1.6561279,1.3438721,2.9981689,3,3h2.5h7c0.0001831,0,0.0003662,0,0.0006104,0H18c1.6561279-0.0018311,2.9981689-1.3438721,3-3v-8.7930298C21.0001831,9.3435669,20.6282959,8.5219116,19.9794922,7.9521484z M15,21H9v-6c0.0014038-1.1040039,0.8959961-1.9985962,2-2h2c1.1040039,0.0014038,1.9985962,0.8959961,2,2V21z M20,19c-0.0014038,1.1040039-0.8959961,1.9985962-2,2h-2v-6c-0.0018311-1.6561279-1.3438721-2.9981689-3-3h-2c-1.6561279,0.0018311-2.9981689,1.3438721-3,3v6H6c-1.1040039-0.0014038-1.9985962-0.8959961-2-2v-8.7930298C3.9997559,9.6313477,4.2478027,9.0836182,4.6806641,8.7041016l6-5.2666016C11.0455933,3.1174927,11.5146484,2.9414673,12,2.9423828c0.4853516-0.0009155,0.9544067,0.1751099,1.3193359,0.4951172l6,5.2665405C19.7521973,9.0835571,20.0002441,9.6313477,20,10.2069702V19z" />
                        </svg>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>

                <li class="slide {{ request()->is('admin/user*') ? 'active is-expanded' : '' }}">
                    <a class="side-menu__item {{ request()->is('admin/user*') ? 'active is-expanded' : '' }}"
                       data-bs-toggle="slide" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span class="side-menu__label">Users</span><i class="angle fa fa-angle-right"></i>
                    </a>

                    <ul class="slide-menu">
                        <li><a href="{{ route('user.index') }}" class="slide-item">Users</a></li>
                        <li><a href="{{ route('user.valet.index') }}" class="slide-item">Valets</a></li>
                        <li><a href="{{ route('user.valet-approved.index') }}" class="slide-item">Pending Valets</a></li>
                    </ul>
                </li>

               <li class="slide {{ request()->is('admin/banner*') ? 'active is-expanded' : '' }}">
                    <a class="side-menu__item {{ request()->is('admin/banner*') ? 'active is-expanded' : '' }}"
                        data-bs-toggle="slide" href="{{ route('banner.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <path d="M3 15h18v-2H3v2z"></path>
                        </svg>
                        <span class="side-menu__label">Banner Image</span>
                    </a>
                </li>
                <li class="slide {{ request()->is('admin/shop*') ? 'active is-expanded' : '' }}">
                    <a class="side-menu__item {{ request()->is('admin/shop**') ? 'active is-expanded' : '' }}"
                        data-bs-toggle="slide" href="{{ route('shop.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path d="M3 9h18" stroke="currentColor" stroke-width="2"></path>
                            <path d="M5 9V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4" stroke="currentColor" stroke-width="2">
                            </path>
                            <path d="M9 22V12h6v10" stroke="currentColor" stroke-width="2"></path>
                            <path d="M3 9l2 11a2 2 0 0 0 2 1h10a2 2 0 0 0 2-1l2-11" fill="currentColor"></path>
                        </svg>
                        <span class="side-menu__label">Shops List</span>
                    </a>
                </li>
                <li class="slide {{ request()->is('admin/category*') ? 'active is-expanded' : '' }}">
                    <a class="side-menu__item {{ request()->is('admin/category*') ? 'active is-expanded' : '' }}"
                        data-bs-toggle="slide" href="{{ route('category.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9l2-3h5a2 2 0 0 1 2 2z">
                            </path>
                        </svg>
                        <span class="side-menu__label">Category List</span>
                    </a>
                </li>
                <li class="slide {{ request()->is('admin/sub-category*') ? 'active is-expanded' : '' }}">
                    <a class="side-menu__item {{ request()->is('admin/sub-category*') ? 'active is-expanded' : '' }}"
                        data-bs-toggle="slide" href="{{ route('subcategory.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M3 12l7-7v4h4V5l7 7-7 7v-4h-4v4z"></path>
                        </svg>
                        <span class="side-menu__label">Subcategories</span>
                    </a>
                </li>

                <li class="slide {{ request()->is('admin/product*') ? 'active is-expanded' : '' }}">
                    <a class="side-menu__item {{ request()->is('admin/product*') ? 'active is-expanded' : '' }}"
                       data-bs-toggle="slide" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"
                             fill="currentColor">
                            <path
                                d="M17 3H7c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm1 16H8V5h10v14zM10 8h4V6h-4v2zm0 4h4v-2h-4v2zm0 4h4v-2h-4v2z">
                            </path>
                        </svg>
                        <span class="side-menu__label">Products</span><i class="angle fa fa-angle-right"></i>
                    </a>

                    <ul class="slide-menu">
                        @php
                        $categories = \App\Models\Category::all();
                        @endphp
                        @foreach($categories as $category)
                            <li><a href="{{ route('product.index', ['id' => $category->id]) }}" class="slide-item">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </li>

                <li class="slide {{ request()->is('admin/orders*') ? 'active is-expanded' : '' }}">
                    <a class="side-menu__item {{ request()->is('admin/orders*') ? 'active is-expanded' : '' }}"
                       data-bs-toggle="slide" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"
                             fill="currentColor">
                            <path
                                d="M17 3H7c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm1 16H8V5h10v14zM10 8h4V6h-4v2zm0 4h4v-2h-4v2zm0 4h4v-2h-4v2z">
                            </path>
                        </svg>
                        <span class="side-menu__label">Orders</span><i class="angle fa fa-angle-right"></i>
                    </a>

                    <ul class="slide-menu">
                        <li><a href="{{ route('orders.index') }}" class="slide-item">Completed Orders</a></li>
                        <li><a href="{{ route('orders.pending.index') }}" class="slide-item">Pending Orders</a></li>
                        <li><a href="{{ route('orders.valet.waiting.index') }}" class="slide-item">Waiting Valet Accept Orders</a></li>
                    </ul>
                </li>

                <li class="slide {{ request()->is('admin/taxes*') ? 'active is-expanded' : '' }}">
                    <a class="side-menu__item {{ request()->is('admin/taxes*') ? 'active is-expanded' : '' }}"
                        data-bs-toggle="slide" href="{{ route('taxes.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M14 2H6c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 16H6V4h12v14zm-3-6h2v2h-2v-2zm3 0h2v2h-2v-2z">
                            </path>
                        </svg>
                        <span class="side-menu__label">Tax page</span>
                    </a>
                </li>
                <li class="slide {{ request()->is('admin/dynamic*') ? 'active is-expanded' : '' }}">
                    <a class="side-menu__item {{ request()->is('admin/dynamic*') ? 'active is-expanded' : '' }}"
                        data-bs-toggle="slide" href="{{ route('dynamic.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <path d="M3 15h18v-2H3v2z"></path>
                        </svg>
                        <span class="side-menu__label">Dynamic Page</span>
                    </a>
                </li>

                <hr>

                <li class="slide {{ request()->is('admin/settings*') ? 'active is-expanded' : '' }}">
                    <a class="side-menu__item {{ request()->is('admin/settings*') ? 'active is-expanded' : '' }}"
                        data-bs-toggle="slide" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 512 512">
                            <path
                                d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z" />
                        </svg>
                        <span class="side-menu__label">Settings</span><i class="angle fa fa-angle-right"></i>
                    </a>

                    <ul class="slide-menu">
                        <li><a href="{{ route('profile.setting') }}" class="slide-item">Profile Settings</a></li>
                        <li><a href="{{ route('system.index') }}" class="slide-item">System Settings</a></li>
                        <li><a href="{{ route('mail.setting') }}" class="slide-item">Mail Settings</a></li>
                        <li><a href="{{ route('stripe.index') }}" class="slide-item">Paypal Settings</a></li>
                    </ul>
                </li>
            </ul>

            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg>
            </div>
        </div>
    </div>
</div>
