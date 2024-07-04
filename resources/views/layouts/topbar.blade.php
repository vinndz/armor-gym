<header id="page-topbar" class="isvertical-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->


            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
                <i class="bx bx-menu align-middle"></i>
            </button>

            <!-- start page title -->
            <div class="page-title-box align-self-center d-none d-md-block">
                <h4 class="page-title mb-0">@yield('page-title')</h4>
            </div>
            <!-- end page title -->

        </div>

        <div class="d-flex">
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item user text-start d-flex align-items-center"
                    id="page-header-user-dropdown-v" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    @if(auth()->user()->role == 'ADMIN')
                    <img class="rounded-circle header-profile-user"
                        src="{{ auth()->user()->image != - ? asset('storage/' . auth()->user()->image) : asset('images/profile-default.png') }}"
                        alt="Header Avatar">
                    @elseif(auth()->user()->role == 'INSTRUCTOR')
                    <img class="rounded-circle header-profile-user"
                        src="{{ auth()->user()->image != '-' ? asset('storage/' . auth()->user()->image) : asset('images/profile-default.png') }}"
                        alt="Header Avatar">
                    @elseif(auth()->user()->role == 'MEMBER')
                    <img class="rounded-circle header-profile-user"
                        src="{{ auth()->user()->image != '-' ? asset('storage/' . auth()->user()->image) : asset('images/profile-default.png') }}"
                        alt="Header Avatar">
                    @elseif(auth()->user()->role == 'OWNER')
                    <img class="rounded-circle header-profile-user"
                        src="{{ auth()->user()->image != '-' ? asset('storage/' . auth()->user()->image) : asset('images/profile-default.png') }}"
                        alt="Header Avatar">
                    @elseif(auth()->user()->role == 'GUEST')
                    <img class="rounded-circle header-profile-user"
                        src="{{ auth()->user()->image != '-' ? asset('storage/' . auth()->user()->image) : asset('images/profile-default.png') }}"
                        alt="Header Avatar">
                    @endif
                    @if(Auth::check())
                    <span class="d-none d-xl-inline-block ms-2 fw-medium font-size-15">{{ Auth::user()->name }}</span>
                    @endif
                </button>
                {{-- <div class="dropdown-menu dropdown-menu-end py-2 px-2 rounded-1 w-25"
                    style="width: 100px !important">
                    <a class="nav-link mx-auto text-center fw-bold d-flex align-items-center gap-2"
                        href="{{ route('logout') }}"><i class='bx bx-exit bx-sm'></i>Logout</a>
                    <a class="nav-link mx-auto text-center fw-bold d-flex align-items-center gap-2" href=""><i
                            class='bx bx-user bx-sm'></i>Profile</a>
                </div> --}}
                <div class="dropdown-menu dropdown-menu-end py-2 px-3 rounded-3 shadow" style="min-width: 150px;">
                    <!-- Profile -->
                    @if(auth()->user()->role == 'ADMIN')
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.profile') }}">
                        <i class="bx bx-user mr-2"></i> Profile
                    </a>
                    @elseif(auth()->user()->role == 'INSTRUCTOR')
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('instructor.profile') }}">
                        <i class="bx bx-user mr-2"></i> Profile
                    </a>
                    @elseif(auth()->user()->role == 'MEMBER')
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('member.profile') }}">
                        <i class="bx bx-user mr-2"></i> Profile
                    </a>
                    @elseif(auth()->user()->role == 'OWNER')
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('owner.profile') }}">
                        <i class="bx bx-user mr-2"></i> Profile
                    </a>
                    @elseif(auth()->user()->role == 'GUEST')
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('guest.profile') }}">
                        <i class="bx bx-user mr-2"></i> Profile
                    </a>
                    @endif

                    <!-- Divider -->
                    <div class="dropdown-divider"></div>

                    <!-- Logout -->
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}">
                        <i class="bx bx-exit mr-2"></i> Logout
                    </a>
                </div>

            </div>
        </div>
    </div>
</header>