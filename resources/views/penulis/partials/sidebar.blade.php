<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar mt-0">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                @if (Auth::user()->author->avatar)
                    <img src="{{ asset(Auth::user()->author->avatar) }}" class="img-circle elevation-2"alt="Admin Foto">
                @else
                    <img src="{{ asset('assets/image/empty.jpg') }}" class="img-circle elevation-2"alt="Admin Foto">
                @endif
            </div>

            <div class="info">
                <a href="{{ route('penulis.profile') }}" class="d-block">{{ auth()->user()->fullname }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-header">Dashboard</li>
                <li class="nav-item">
                    <a href="{{ route('penulis.dashboard') }}"
                        class="nav-link @if (Request::is('penulis/dashboard')) active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-header">Notification</li>
                {{-- notification --}}
                <li class="nav-item">
                    <a href="{{ route('penulis.notification.index') }}"
                        class="nav-link @if (Request::is('penulis/notification') || Request::is('penulis/notification/*')) active @endif">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>
                            Notification
                            <span class="badge badge-warning right unread-notification-count"
                                style="display: none;"></span>
                        </p>
                    </a>
                </li>

                <li class="nav-header">Manage Ebook</li>
                {{-- upload ebook --}}
                <li class="nav-item">
                    <a href="{{ route('penulis.ebook.create') }}"
                        class="nav-link @if (Request::is('penulis/ebook/upload')) active @endif">
                        <i class="nav-icon fas fa-upload"></i>
                        <p>Upload Ebook</p>
                    </a>
                </li>

                {{-- manage ebook --}}
                <li class="nav-item">
                    <a href="{{ route('penulis.ebook.index') }}"
                        class="nav-link @if (
                            (Request::is('penulis/ebook') && !Request::is('penulis/ebook/upload')) ||
                                (Request::is('penulis/ebook/*') && !Request::is('penulis/ebook/upload'))) active @endif">
                        <i class="nav-icon fas fa-archive"></i>
                        <p>Manage Ebook</p>
                    </a>
                </li>

                {{-- kontribusi ebook --}}
                <li class="nav-item">
                    <a href="{{ route('penulis.ebook.kontribusi') }}"
                        class="nav-link @if (Request::is('penulis/kontribusi-ebook')) active @endif">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Kontribusi Ebook</p>
                    </a>
                </li>

                <li class="nav-header">Sales Report</li>
                {{-- laporan penjualan --}}
                <li class="nav-item">
                    <a href="{{ route('penulis.sales_report') }}"
                        class="nav-link @if (Request::is('penulis/sales-report')) active @endif">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Sales Report</p>
                    </a>
                </li>

                <li class="nav-header">Account Settings</li>
                {{-- Update Profile --}}
                <li class="nav-item">
                    <a href="{{ route('penulis.show_update') }}"
                        class="nav-link @if (Request::is('penulis/setting/update-profile')) active @endif">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>Update Profile</p>
                    </a>
                </li>

                {{-- Account Settings --}}
                <li class="nav-item">
                    <a href="{{ route('penulis.change_password') }}"
                        class="nav-link @if (Request::is('penulis/setting/change-password')) active @endif">
                        <i class="nav-icon fas fa-key"></i>
                        <p>Change Password</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
