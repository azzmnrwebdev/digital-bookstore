<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar mt-0">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                @if (Auth::user()->profile->avatar)
                    <img src="{{ asset(Auth::user()->profile->avatar) }}" class="img-circle elevation-2"alt="Admin Foto">
                @else
                    <img src="{{ asset('assets/image/empty.jpg') }}" class="img-circle" alt="Admin Foto">
                @endif
            </div>

            <div class="info">
                <a href="{{ route('admin.profile') }}" class="d-block">{{ auth()->user()->fullname }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-header">Dashboard</li>
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link @if (Request::is('admin/dashboard')) active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-header">Notification</li>
                <li class="nav-item">
                    <a href="{{ route('admin.notification.index') }}"
                        class="nav-link @if (Request::is('admin/notification') || Request::is('admin/notification/*')) active @endif">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>
                            Notification
                            <span class="badge badge-warning right unread-notification-count"
                                style="display: none;"></span>
                        </p>
                    </a>
                </li>

                <li class="nav-header">Manage Data</li>
                {{-- manage pengguna --}}
                <li class="nav-item">
                    <a href="{{ route('manageuser.index') }}"
                        class="nav-link @if (Request::is('admin/manageuser') || Request::is('admin/manageuser/*')) active @endif">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Manage User</p>
                    </a>
                </li>

                {{-- manage order --}}
                <li class="nav-item">
                    <a href="{{ route('admin.manageorder.index') }}"
                        class="nav-link @if (Request::is('admin/manageorder') || Request::is('admin/manageorder/*')) active @endif">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Manage Order</p>
                    </a>
                </li>

                {{-- manage rating --}}
                <li class="nav-item">
                    <a href="{{ route('managerating.index') }}"
                        class="nav-link @if (Request::is('admin/managerating') || Request::is('admin/managerating/*')) active @endif">
                        <i class="nav-icon fas fa-star"></i>
                        <p>Manage Rating</p>
                    </a>
                </li>

                {{-- manage kategori --}}
                <li class="nav-item">
                    <a href="{{ route('managecategory.index') }}"
                        class="nav-link @if (Request::is('admin/managecategory') || Request::is('admin/managecategory/*')) active @endif">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>Manage Category</p>
                    </a>
                </li>

                {{-- manage testimoni --}}
                <li class="nav-item">
                    <a href="{{ route('managetestimonial.index') }}"
                        class="nav-link @if (Request::is('admin/managetestimonial') || Request::is('admin/managetestimonial/*')) active @endif">
                        <i class="nav-icon fas fa-comment"></i>
                        <p>Manage Testimonial</p>
                    </a>
                </li>

                {{-- manage sampah ebook --}}
                <li class="nav-item">
                    <a href="{{ route('manageebooktrash.index') }}"
                        class="nav-link @if (Request::is('admin/manageebooktrash') || Request::is('admin/manageebooktrash/*')) active @endif">
                        <i class="nav-icon fas fa-trash"></i>
                        <p>Manage Ebook Trash</p>
                    </a>
                </li>

                <li class="nav-header">Account Settings</li>
                {{-- Update Profile --}}
                <li class="nav-item">
                    <a href="{{ route('admin.show_update') }}"
                        class="nav-link @if (Request::is('admin/setting/update-profile')) active @endif">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>Update Profile</p>
                    </a>
                </li>

                {{-- Account Settings --}}
                <li class="nav-item">
                    <a href="{{ route('admin.change_password') }}"
                        class="nav-link @if (Request::is('admin/setting/change-password')) active @endif">
                        <i class="nav-icon fas fa-key"></i>
                        <p>Change Password</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
