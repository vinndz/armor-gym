<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="/" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo_armor.png') }}" alt="" height="26">
            </span>
            <div style="text-align: center;">
                <span class="logo-lg">
                    <img src="{{ URL::asset('assets/images/logo_armor.png') }}" alt="" height="60" width="120">
                </span>
            </div>

        </a>

    </div>

    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
        <i class="bx bx-menu align-middle"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">
      
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            @if(auth()->user()->role == 'ADMIN')
                <ul class="metismenu list-unstyled" id="side-menu" style="margin-top: 20px;">
                    <li>
                        <a href="{{route('admin.home')}}">
                            <i class="bx bx-home-alt icon nav-icon"></i>
                            <span class="menu-item" data-key="t-dashboard">Dashboard</span>
                        </a>
                    </li>
                    <hr class="divider" style="border-top: 1px solid #ccc; margin: 20px 0;">
                    <li>
                        <a href="{{route('instructor-data.index')}}">
                            <i class="fa fa-users icon nav-icon"></i>
                            <span class="menu-item" data-key="t-activation-member">Data Instructor</span>
                        </a>
                    </li>
                    <hr class="divider" style="border-top: 1px solid #ccc; margin: 20px 0;">
                    <li>
                        <a href="{{route('membership-data.index')}}">
                            <i class='fa fa-solid fa-dumbbell icon nav-icon'></i>
                            <span class="menu-item" data-key="t-activation-member">Data Membership</span>
                        </a>
                    </li>
                    <hr class="divider" style="border-top: 1px solid #ccc; margin: 20px 0;">
                    <li>
                        <a href="{{route('membership-transaction.index')}}">
                            <i class='fa fa-solid fa-user-check icon nav-icon'></i>
                            <span class="menu-item" data-key="t-activation-member">Activation Member</span>
                        </a>
                    </li>
                    <hr class="divider" style="border-top: 1px solid #ccc; margin: 20px 0;">
                    <li>
                        <a href="{{route('daily-gym-transaction.index')}}">
                            <i class="fa fa-shopping-cart icon nav-icon"></i>
                            <span class="menu-item" data-key="t-transaction-daily">Transaction Daily</span>
                        </a>
                    </li>

                    <hr class="divider" style="border-top: 1px solid #ccc; margin: 20px 0;">
                    <li>
                        <a href="{{route('suplement-data.index')}}">
                            <i class='fa fa-solid fa-box icon nav-icon'></i>
                            <span class="menu-item" data-key="t-suplement">Data Suplement</span>
                        </a>
                    </li>

                    
                    <hr class="divider" style="border-top: 1px solid #ccc; margin: 20px 0;">
                    <li>
                        <a href="{{route('suplement-transaction.index')}}">
                            <i class="fa fa-shopping-cart icon nav-icon"></i>
                            <span class="menu-item" data-key="t-suplement">Transaction Suplement</span>
                        </a>
                    </li>
                </ul>
            @elseif(auth()->user()->role == 'INSTRUCTOR')
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li>
                        <a href="{{route('instructor.home')}}">
                            <i class="bx bx-home-alt icon nav-icon"></i>
                            <span class="menu-item" data-key="t-dashboard">Dashboard</span>
                        </a>
                    </li>

                    <hr class="divider" style="border-top: 1px solid #ccc; margin: 20px 0;">
                    <li>
                        <a href="{{route('program-data.index')}}">
                            <i class='bx bxs-data' style="font-size: 24px"></i>
                            <span class="menu-item" data-key="t-data-program">Data Program</span>
                        </a>
                    </li>
                    @php
                        $instructorId = auth()->user()->id; 
                    @endphp
                    <hr class="divider" style="border-top: 1px solid #ccc; margin: 20px 0;">
                    <li>
                        <a href="{{route('program-member.index', ['instructor_id' => $instructorId]) }}">
                            <i class='bx bxs-group' style="font-size: 24px"></i>
                            <span class="menu-item" data-key="t-program-member">Program Member</span>
                        </a>
                    </li>

                    <hr class="divider" style="border-top: 1px solid #ccc; margin: 20px 0;">
                    <li>
                        <a href="{{route('gym-schedule.index',['instructor_id' => $instructorId]) }}">
                            <i class='bx bxs-calendar' style="font-size: 24px"></i>
                            <span class="menu-item" data-key="t-gym-schedule">Schedule Gym</span>
                        </a>
                    </li>
                </ul>

            @elseif(auth()->user()->role == 'MEMBER')
                 @php
                    $memberId = auth()->user()->id; 
                @endphp
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li>
                        <a href="{{route('member.home')}}">
                            <i class="bx bx-home-alt icon nav-icon"></i>
                            <span class="menu-item" data-key="t-dashboard">Dashboard</span>
                        </a>
                    </li>

                    <hr class="divider" style="border-top: 1px solid #ccc; margin: 20px 0;">
                    <li>
                        <a href="{{route('gym-schedule.index-member',['member_id' => $memberId]) }}">
                            <i class='bx bxs-calendar' style="font-size: 24px"></i>
                            <span class="menu-item" data-key="t-gym-schedule">Schedule Gym</span>
                        </a>
                    </li>
                 
                </ul>

            @elseif(auth()->user()->role == 'OWNER')
                <ul class="metismenu list-unstyled" id="side-menu">
                    {{-- <li>
                        <a href="{{route('owner.home')}}">
                            <i class="bx bx-home-alt icon nav-icon"></i>
                            <span class="menu-item" data-key="t-dashboard">Dashboard</span>
                        </a>
                    </li> --}}
                    <hr class="divider" style="border-top: 1px solid #ccc; margin: 20px 0;">
                    <li>
                        <a href="{{route('report.index-monthly')}}">
                            <i class="bx bx-receipt icon nav-icon"></i>
                            <span class="menu-item" data-key="t-monthly-report">Monthly Report</span>
                        </a>
                    </li>
                    <hr class="divider" style="border-top: 1px solid #ccc; margin: 20px 0;">
                    <li>
                        <a href="{{route('report.index')}}">
                            <i class="bx bx-receipt icon nav-icon"></i>
                            <span class="menu-item" data-key="t-daily-report">Daily Report</span>
                        </a>
                    </li>
                </ul>
            @elseif(auth()->user()->role == 'GUEST')
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li class="menu-title" data-key="t-menu">Dashboard for Guest</li>
                </ul>
            @endif
        </div>
        
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->