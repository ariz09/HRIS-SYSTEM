<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand -->
    <a class="navbar-brand ps-3" href="{{ route('dashboard') }}">
        <img src="{{ asset('images/logo_sidebar.png') }}" alt="Tagline HRIS" width="120" style="align-content:center;">
    </a>

    <!-- Sidebar Toggle -->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>



    <!-- Navbar User Menu - Now properly aligned to the right -->
    <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
               data-bs-toggle="dropdown" aria-expanded="false">
               <i class="fas fa-user fa-fw"></i>
               <span class="d-none d-lg-inline">
                    {{
                        Auth::user()->personalInfo && Auth::user()->personalInfo->first_name && Auth::user()->personalInfo->last_name
                            ? Auth::user()->personalInfo->last_name . ', ' . Auth::user()->personalInfo->first_name
                            : (Auth::user()->personalInfo && Auth::user()->personalInfo->preferred_name
                                ? Auth::user()->personalInfo->preferred_name
                                : Auth::user()->name)
                    }}
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                {{-- <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li> --}}
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
