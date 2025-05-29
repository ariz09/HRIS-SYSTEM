<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">

            <!-- Dashboard -->
            <div class="sb-sidenav-menu-heading">HRIS SYSTEM</div>
            <a class="nav-link" href="{{ route('dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>

            <!-- Employee Management -->
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseEmployeeMgmt" aria-expanded="false" aria-controls="collapseEmployeeMgmt">
                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                Employee Management
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseEmployeeMgmt" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ route('employees.index') }}">Employees</a>
                    <a class="nav-link" href="{{ route('positions.index') }}">Positions</a>
                    <a class="nav-link" href="{{ route('departments.index') }}">Departments</a>
                    <a class="nav-link" href="{{ route('employment_types.index') }}">Employment Types</a>
                    <a class="nav-link" href="{{ route('cdmlevels.index') }}">CDM Levels</a>
                    <a class="nav-link" href="{{ route('agencies.index') }}">Agencies</a>
                    <a class="nav-link" href="{{ route('inactive-users.index') }}">Activate Users</a>
                </nav>
            </div>

            <!-- Leave & Attendance -->
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLeave" aria-expanded="false" aria-controls="collapseLeave">
                <div class="sb-nav-link-icon"><i class="fas fa-calendar-check"></i></div>
                Leave & Attendance
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseLeave" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ route('leaves.index') }}">Leave Applications</a>
                    <a class="nav-link" href="{{ route('leave_types.index') }}">Leave Types</a>
                    <a class="nav-link" href="{{ route('assign_leaves.index') }}">Assign Leaves</a>
                    <a class="nav-link" href="{{ route('excess.index') }}">Excess Time Management</a>
                </nav>
            </div>

            <!-- Employee Records -->
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRecords" aria-expanded="false" aria-controls="collapseRecords">
                <div class="sb-nav-link-icon"><i class="fas fa-folder-open"></i></div>
                Employee Records
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseRecords" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ route('file201.index') }}">201 File Management</a>
                </nav>
            </div>

            <!-- Payroll Setup -->
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePayroll" aria-expanded="false" aria-controls="collapsePayroll">
                <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                Payroll Setup
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapsePayroll" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ route('period_types.index') }}">Period Types</a>
                    <a class="nav-link" href="{{ route('cut_off_types.index') }}">Cut-off Types</a>
                </nav>
            </div>

            <!-- Access Control -->
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAccess" aria-expanded="false" aria-controls="collapseAccess">
                <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div>
                Access Control
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseAccess" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ route('roles.index') }}">Roles Management</a>
                    <a class="nav-link" href="{{ route('roles.user-roles') }}">User Roles</a>
                    <a class="nav-link" href="{{ route('role_permissions.index') }}">Roles & Access</a>
                </nav>
            </div>

            <!-- Others -->
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseOthers" aria-expanded="false" aria-controls="collapseOthers">
                <div class="sb-nav-link-icon"><i class="fas fa-umbrella-beach"></i></div>
                Others
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseOthers" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ route('holidays.index') }}">Holiday Management</a>
                </nav>
            </div>

        </div>
    </div>
</nav>
