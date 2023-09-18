{{-- double navbar versi laptop --}}
<nav class="navbar navbar-web-satu navbar-expand-lg d-none d-lg-block shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">RuangLiterasi</a>

        <ul class="navbar-nav me-auto">
            <li class="nav-item  @if (Request::is('/')) active @endif">
                <a class="nav-link" href="{{ route('home') }}">Home</a>
            </li>

            <li class="nav-item ms-2 @if (Request::is('ebook') || Request::is('ebook/*')) active @endif">
                <a class="nav-link" href="{{ route('ebooks') }}">Ebook</a>
            </li>

            <li class="nav-item ms-2  @if (Request::is('category') || Request::is('category/*')) active @endif">
                <a class="nav-link" href="{{ route('categories') }}">Category</a>
            </li>

            <li class="nav-item ms-2 @if (Request::is('catalog') || Request::is('catalog/*')) active @endif">
                <a class="nav-link" href="{{ route('catalog') }}">Catalog</a>
            </li>
        </ul>

        <ul class="navbar-nav d-flex align-items-center">
            @guest
                @if (Route::has('login'))
                    <li class="nav-item me-2">
                        <a href="{{ route('login') }}" class="nav-link p-0">
                            <button type="button" class="btn btn-sm btn-outline-primary">Login</button>
                        </a>
                    </li>
                @endif

                @if (Route::has('register'))
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link p-0">
                            <button type="button" class="btn btn-sm btn-primary">Register</button>
                        </a>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        @if (Auth::user()->role === 'admin' || Auth::user()->role === 'pembaca')
                            @if (Auth::user()->profile->avatar)
                                <img src="{{ asset(Auth::user()->profile->avatar) }}" style="width: 50px; height: 50px;"
                                    class="rounded-circle" alt="Reader Profile Photo">
                            @else
                                <img src="{{ asset('assets/image/empty.jpg') }}" style="width: 50px; height: 50px;"
                                    class="rounded-circle" alt="Profile Image">
                            @endif
                        @endif

                        @if (Auth::user()->role === 'penulis')
                            @if (Auth::user()->author->avatar)
                                <img src="{{ asset(Auth::user()->author->avatar) }}" class="rounded-circle"
                                    style="width: 50px; height: 50px;" alt="Writer Profile Photo">
                            @else
                                <img src="{{ asset('assets/image/empty.jpg') }}" class="rounded-circle"
                                    style="width: 50px; height: 50px;" alt="Profile Image">
                            @endif
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right pb-0">
                        @if (Auth::user()->role === 'admin')
                            <li>
                                <a class="dropdown-item d-flex justify-content-arround align-items-center"
                                    href="{{ route('admin.profile') }}">
                                    @if (Auth::user()->profile->avatar)
                                        <img src="{{ asset(Auth::user()->profile->avatar) }}" class="rounded-circle"
                                            style="width: 50px; height: 50px;" alt="Reader Profile Photo">
                                    @else
                                        <img src="{{ asset('assets/image/empty.jpg') }}" class="rounded-circle"
                                            style="width: 50px; height: 50px;" alt="Profile Image">
                                    @endif

                                    <span style="line-height: 24px; margin-left: 0.75rem">
                                        <h6 class="m-0" style="font-size: 15px;">{{ Auth::user()->fullname }}</h6>
                                        <p class="text-muted m-0" style="font-size: 14px;">{{ Auth::user()->username }} |
                                            admin
                                        </p>
                                    </span>
                                </a>
                            </li>
                        @endif

                        @if (Auth::user()->role === 'penulis')
                            <li>
                                <a class="dropdown-item d-flex justify-content-arround align-items-center"
                                    href="{{ route('penulis.profile') }}">
                                    @if (Auth::user()->author->avatar)
                                        <img src="{{ asset(Auth::user()->author->avatar) }}" class="rounded-circle"
                                            style="width: 50px; height: 50px;" alt="Writer Profile Photo">
                                    @else
                                        <img src="{{ asset('assets/image/empty.jpg') }}" class="rounded-circle"
                                            style="width: 50px; height: 50px;" alt="Profile Image">
                                    @endif

                                    <span style="line-height: 24px; margin-left: 0.75rem">
                                        <h6 class="m-0" style="font-size: 15px;">{{ Auth::user()->fullname }}</h6>
                                        <p class="text-muted m-0" style="font-size: 14px;">{{ Auth::user()->username }} |
                                            writer
                                        </p>
                                    </span>
                                </a>
                            </li>
                        @endif

                        @if (Auth::user()->role === 'pembaca')
                            <li>
                                <a class="dropdown-item d-flex justify-content-arround align-items-center @if (Request::is('reader/profile') || Request::is('reader/profile/*')) active @endif"
                                    href="{{ route('pembaca.profile') }}">
                                    @if (Auth::user()->profile->avatar)
                                        <img src="{{ asset(Auth::user()->profile->avatar) }}" class="rounded-circle"
                                            style="width: 50px; height: 50px;" alt="Reader Profile Photo">
                                    @else
                                        <img src="{{ asset('assets/image/empty.jpg') }}" class="rounded-circle"
                                            style="width: 50px; height: 50px;" alt="Profile Image">
                                    @endif

                                    <span style="line-height: 24px; margin-left: 0.75rem">
                                        <h6 class="m-0" style="font-size: 15px;">{{ Auth::user()->fullname }}</h6>
                                        <p class="text-muted m-0" style="font-size: 14px;">{{ Auth::user()->username }} |
                                            reader
                                        </p>
                                    </span>
                                </a>
                            </li>
                        @endif

                        <li>
                            <hr class="dropdown-divider mb-0">
                        </li>

                        @if (Auth::user()->role === 'admin')
                            <li><a class="dropdown-item d-flex align-items-center" style="font-size: 14px;"
                                    href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"
                                        style="font-size: 20px;"></i>Dashboard</a></li>
                        @endif

                        @if (Auth::user()->role === 'penulis')
                            <li><a class="dropdown-item d-flex align-items-center" style="font-size: 14px;"
                                    href="{{ route('penulis.dashboard') }}"><i class="bi bi-speedometer2 me-2"
                                        style="font-size: 20px;"></i>Dashboard</a></li>
                        @endif

                        <li>
                            <hr class="dropdown-divider m-0">
                        </li>

                        <li><a class="dropdown-item d-flex align-items-center @if (Request::is('notification') || Request::is('notification/*')) active @endif"
                                style="font-size: 14px;" href="{{ route('notification.index') }}"><i
                                    class="bi bi-bell me-2" style="font-size: 20px;"></i>Notification

                                <span class="badge bg-warning position-absolute unread-notification-count"
                                    style="right: 20px; display: none"></span>
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider m-0">
                        </li>

                        <li><a class="dropdown-item d-flex align-items-center @if (Request::is('my-cart')) active @endif"
                                style="font-size: 14px;" href="{{ route('cart.index') }}"><i class="bi bi-cart me-2"
                                    style="font-size: 20px;"></i>My Cart

                                <span class="badge bg-warning position-absolute cart-count"
                                    style="right: 20px; display: none"></span>
                            </a></li>
                        <li>
                            <hr class="dropdown-divider m-0">
                        </li>

                        <li><a class="dropdown-item d-flex align-items-center @if (Request::is('my-order') || Request::is('my-order/*')) active @endif"
                                style="font-size: 14px;" href="{{ route('order.index') }}"><i class="bi bi-receipt me-2"
                                    style="font-size: 20px;"></i>My Order</a></li>

                        <li>
                            <hr class="dropdown-divider m-0">
                        </li>

                        <li><a class="dropdown-item d-flex align-items-center @if (Request::is('testimonial') || Request::is('testimonial/*')) active @endif"
                                style="font-size: 14px;" href="{{ route('testimonial.index') }}"><i
                                    class="bi bi-star me-2" style="font-size: 20px;"></i>My Testimonial</a></li>

                        <li>
                            <hr class="dropdown-divider m-0">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center logout" style="font-size: 14px;"
                                href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form-web').submit();">
                                <i class="bi bi-box-arrow-right me-2" style="font-size: 20px;"></i> {{ __('Logout') }}
                            </a>

                            <form id="logout-form-web" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            @endguest
        </ul>
    </div>
</nav>

{{-- navbar versi mobile --}}
<nav class="navbar bg-body-tertiary navbar-light shadow-sm d-lg-none">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">{{ __('RuangLiterasi') }}</a>

        <ul class="navbar-nav ms-auto me-3">
            <li class="nav-item fs-4">
                <a href="#" class="nav-link"><i class="bi bi-cart-fill"></i></a>
            </li>
        </ul>

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">{{ __('RuangLiterasi') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link @if (Request::is('/')) active @endif"
                            href="{{ route('home') }}">{{ __('Home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if (Request::is('ebooks')) active @endif"
                            href="{{ route('ebooks') }}">{{ __('Ebooks') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if (Request::is('categories')) active @endif"
                            href="{{ route('categories') }}">{{ __('Categories') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if (Request::is('catalog') || Request::is('catalog/*')) active @endif"
                            href="{{ route('catalog') }}">{{ __('Catalog') }}</a>
                    </li>

                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ Auth::user()->fullname }}
                            </a>

                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Dashboard</a></li>
                                <li><a class="dropdown-item" href="#">My Profile</a></li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
</nav>
