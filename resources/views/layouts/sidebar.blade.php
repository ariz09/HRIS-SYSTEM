<!-- resources/views/layouts/sidebar.blade.php -->
<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">HRIS SYSTEM</div>
                 <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                 Dashboard
            </a>

            <div class="sb-sidenav-menu-heading">Admin Management</div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseSettings" aria-expanded="false" aria-controls="collapseSettings">
                         Settings <!--Settings -->
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseSettings" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">

                     <!--Employees -->
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.employees.index') }}">Employees</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.employees.bulk-upload') }}">Bulk-upload</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.leaves.index') }}">Leave Application</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.leave_types.index') }}">Leave Types</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.assign_leaves.index') }}">Assign Leaves</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.positions.index') }}">Positions</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                          <a class="nav-link" href="{{ route('admin.agencies.index') }}">Agencies</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link" href="{{ route('admin.departments.index') }}">Departments</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link" href="{{ route('admin.holidays.index') }}">Holiday Management</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link" href="{{ route('admin.roles.index') }}">Roles Management</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link" href="{{ route('admin.role_permissions.index') }}">Roles & Access Management</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link" href="{{ route('admin.cdmlevels.index') }}">CDM Management</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link" href="{{ route('admin.employment_types.index') }}">Employment Type Management</a>
                    </nav>

                     <!--End Employees -->

                </nav>  <!--End nav main -->
            </div>

        </div>
    </div>


</nav>

