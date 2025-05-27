<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand -->
    <a class="navbar-brand ps-3" href="{{ route('dashboard') }}">
        <img src="{{ asset('images/logo_sidebar.png') }}" alt="Tagline HRIS" width="120" style="align-content:center;">
    </a>

    <!-- Sidebar Toggle -->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Combined User and Notifications Menu -->
    <ul class="navbar-nav ms-auto">
        <!-- Notifications -->
        <li class="nav-item dropdown me-2">
            <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" 
               data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i>
                <span class="badge bg-danger" id="inactiveUsersCount">
                    {{ App\Models\User::where('is_active', false)->count() }}
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                <li><h6 class="dropdown-header">New User Approvals</h6></li>
                <li id="inactiveUsersList">
                    @if(App\Models\User::where('is_active', false)->count() > 0)
                        <a class="dropdown-item" href="{{ route('inactive-users.index') }}">
                            <i class="fas fa-users me-2"></i>
                            {{ App\Models\User::where('is_active', false)->count() }} users waiting approval
                        </a>
                    @else
                        <span class="dropdown-item text-muted">No pending approvals</span>
                    @endif
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-center" href="{{ route('inactive-users.index') }}">View All</a></li>
            </ul>
        </li>
        
        <!-- User Menu -->
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
                <li><a class="dropdown-item" href="{{ route('profile.index') }}">Profile</a></li>
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

@push('scripts')
<script>
    let previousCount = {{ App\Models\User::where('is_active', false)->count() }};
    // Poll for new inactive users every 30 seconds
    setInterval(function() {
        fetch("{{ route('inactive-users.count') }}")
            .then(response => response.json())
            .then(data => {
                document.getElementById('inactiveUsersCount').textContent = data.count;
                
                // Update dropdown content
                let dropdownContent = '';
                if (data.count > 0) {
                    dropdownContent = `
                        <a class="dropdown-item" href="{{ route('inactive-users.index') }}">
                            <i class="fas fa-users me-2"></i>
                            ${data.count} users waiting approval
                        </a>
                    `;
                } else {
                    dropdownContent = '<span class="dropdown-item text-muted">No pending approvals</span>';
                }
                document.getElementById('inactiveUsersList').innerHTML = dropdownContent;
            });
    }, 30000); // 30 seconds
</script>
@endpush