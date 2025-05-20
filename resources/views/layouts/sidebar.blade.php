<!-- resources/views/layouts/sidebar.blade.php -->
<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">HRIS SYSTEM</div>
                 <a class="nav-link" href="{{ route('dashboard') }}">
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
                        <a class="nav-link" href="{{ route('employees.index') }}">Employees</a>
                    </nav>

                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('leaves.index') }}">Leave Application</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('leave_types.index') }}">Leave Types</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('assign_leaves.index') }}">Assign Leaves</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('positions.index') }}">Positions</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                          <a class="nav-link" href="{{ route('agencies.index') }}">Agencies</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link" href="{{ route('departments.index') }}">Departments</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link" href="{{ route('holidays.index') }}">Holiday Management</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link" href="{{ route('roles.index') }}">Roles Management</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link" href="{{ route('role_permissions.index') }}">Roles & Access Management</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link" href="{{ route('cdmlevels.index') }}">CDM Management</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link" href="{{ route('employment_types.index') }}">Employment Type Management</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link" href="{{ route('file201.index') }}">201 File Management</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link" href="{{ route('overtimes.index') }}">Overtime Management</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link" href="{{ route('period_types.index') }}">Period Type Management</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link" href="{{ route('cut_off_types.index') }}">Cut-off Type Management</a>
                    </nav>
  

                     <!--End Employees -->

                </nav>  <!--End nav main -->
            </div>

        </div>
    </div>


</nav>

