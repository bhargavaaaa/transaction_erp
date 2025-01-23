<!-- Sidebar -->
<nav id="sidebar">
    <!-- Sidebar Content -->
    <div class="sidebar-content">
        <!-- Side Header -->
        <div class="content-header justify-content-lg-center">
            <!-- Logo -->
            <div>
                <a class="fw-bold tracking-wide mx-auto" href="javascript:void(0);">
                    <span class="smini-hidden">
                        <span style="color: #0059a1;">Akshar Engineers</span>
                    </span>
                </a>
            </div>
            <!-- END Logo -->

            <!-- Options -->
            <div>
                <button type="button" class="btn btn-sm btn-alt-danger d-lg-none" data-toggle="layout"
                        data-action="sidebar_close">
                    <i class="fa fa-fw fa-times"></i>
                </button>
            </div>
            <!-- END Options -->
        </div>
        <!-- END Side Header -->

        <!-- Sidebar Scrolling -->
        <div class="js-sidebar-scroll">
            <!-- Side User -->
            <div class="content-side content-side-user px-0 py-0">
                <!-- Visible only in mini mode -->
                <div class="smini-visible-block animated fadeIn px-3">
                    <img class="img-avatar img-avatar32 object-fit-cover" src="{{ getUserAvatar() }}" alt="">
                </div>
                <!-- END Visible only in mini mode -->

                <!-- Visible only in normal mode -->
                <div class="smini-hidden text-center mx-auto">
                    <a class="img-link" href="javascript:void(0)">
                        <img class="img-avatar object-fit-cover" src="{{ getUserAvatar() }}" alt="">
                    </a>
                    <ul class="list-inline mt-3 mb-0">
                        <li class="list-inline-item">
                            <a class="link-fx text-dual fs-sm fw-semibold text-uppercase"
                               href="javascript:void(0)">{{ auth()->user()->name }}</a>
                        </li>
                        <li class="list-inline-item">
                            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                            <a class="link-fx text-dual" data-toggle="layout" data-action="dark_mode_toggle"
                               href="javascript:void(0)">
                                <i class="fa fa-moon"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a class="link-fx text-dual" href="javascript:void(0)"
                               onclick="document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out-alt"></i>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf</form>
                        </li>
                    </ul>
                </div>
                <!-- END Visible only in normal mode -->
            </div>
            <!-- END Side User -->

            <!-- Side Navigation -->
            <div class="content-side content-side-full">
                <ul class="nav-main">
                    @adminorcan('gantt-view')
                    <li class="nav-main-item">
                        <a class="nav-main-link{{ request()->segment(1) === 'dashboard' ? ' active' : '' }}"
                           href="{{ route('dashboard') }}">
                            <i class="nav-main-link-icon fa fa-file-alt"></i>
                            <span class="nav-main-link-name">Gantt</span>
                        </a>
                    </li>
                    @endadminorcan
                    @adminorcan('order-process-card-view')
                    <li class="nav-main-item">
                        <a class="nav-main-link{{ request()->segment(1) === 'order-process-card' ? ' active' : '' }}"
                           href="{{ route('order-process-card.index') }}">
                            <i class="nav-main-link-icon fa fa-id-card-alt"></i>
                            <span class="nav-main-link-name">Order Process Card</span>
                        </a>
                    </li>
                    @endadminorcan
                    @adminorcan('order-view')
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->segment(1) === 'order' ? 'active' : '' }}"
                           href="{{ route('order.index') }}">
                            <i class="nav-main-link-icon fa fa-exchange"></i>
                            <span class="nav-main-link-name">Order</span>
                        </a>
                    </li>
                    @endadminorcan
                    @adminorcan('cutting-view')
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->segment(1) === 'cutting' ? 'active' : '' }}"
                           href="{{ route('cutting.index') }}">
                            <i class="nav-main-link-icon fa fa-cut"></i>
                            <span class="nav-main-link-name">Cutting</span>
                        </a>
                    </li>
                    @endadminorcan
                    @adminorcan('turning-view')
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->segment(1) === 'turning' ? 'active' : '' }}"
                           href="{{ route('turning.index') }}">
                            <i class="nav-main-link-icon fa fa-refresh"></i>
                            <span class="nav-main-link-name">Turning</span>
                        </a>
                    </li>
                    @endadminorcan
                    @adminorcan('milling-view')
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->segment(1) === 'milling' ? 'active' : '' }}"
                           href="{{ route('milling.index') }}">
                            <i class="nav-main-link-icon fa fa-mill-sign"></i>
                            <span class="nav-main-link-name">Milling</span>
                        </a>
                    </li>
                    @endadminorcan
                    @adminorcan('other-view')
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->segment(1) === 'other' ? 'active' : '' }}"
                           href="{{ route('other.index') }}">
                            <i class="nav-main-link-icon fa fa-toolbox"></i>
                            <span class="nav-main-link-name">Other</span>
                        </a>
                    </li>
                    @endadminorcan
                    @adminorcan('dispatch-view')
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->segment(1) === 'dispatch' ? 'active' : '' }}"
                           href="{{ route('dispatch.index') }}">
                            <i class="nav-main-link-icon fa fa-truck"></i>
                            <span class="nav-main-link-name">Dispatch</span>
                        </a>
                    </li>
                    @endadminorcan
                    @adminorcan('role-view')
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->segment(1) === 'role' ? 'active' : '' }}"
                           href="{{ route('role.index') }}">
                            <i class="nav-main-link-icon fa fa-user-group"></i>
                            <span class="nav-main-link-name">Role</span>
                        </a>
                    </li>
                    @endadminorcan
                    @adminorcan('user-view')
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->segment(1) === 'user' ? 'active' : '' }}"
                           href="{{ route('user.index') }}">
                            <i class="nav-main-link-icon fa fa-user"></i>
                            <span class="nav-main-link-name">User</span>
                        </a>
                    </li>
                    @endadminorcan
                </ul>
            </div>
            <!-- END Side Navigation -->
        </div>
        <!-- END Sidebar Scrolling -->
    </div>
    <!-- Sidebar Content -->
</nav>
<!-- END Sidebar -->
