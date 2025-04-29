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

                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('dashboard') }}">Schedule</a>
                    </nav>
                     <!--Employees -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseEmployees" aria-expanded="false" aria-controls="collapseEmployees">
                                     Employees
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseEmployees" aria-labelledby="headingEmployees"  data-bs-parent="#side2navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('employees.index') }}">Employees</a>
                                 
                            </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('employees.bulk-upload') }}">Employee Bulk-upload</a>
                            </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Employee Resignation</a>
                            </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Account Activation</a>
                            </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Subordinates</a>
                            </nav>
                        </div>
                    </nav>
                     <!--End Employees -->
                     <!--leaves -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLeaves" aria-expanded="false" aria-controls="collapseLeaves">
                                     Leaves
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLeaves" aria-labelledby="headingLeaves"  data-bs-parent="#side2navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Employee Leaves</a>
                            </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                 <a class="nav-link" href="{{ route('leave_types.index') }}">Leave Types</a>
                            </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('assign_leaves.index') }}">Assign Leaves</a>
                            </nav>
                            <nav class="sb-sidenav-menu-nested nav">
                                 <a class="nav-link" href="{{ route('dashboard') }}">Employee Resignation</a>
                            </nav>
                        </div>
                    </nav>
                     <!--End leaves -->
                     <!--Groups -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseGroups" aria-expanded="false" aria-controls="collapseGroups">
                                     Groups
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseGroups" aria-labelledby="headingGroups"  data-bs-parent="#side2navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('positions.index') }}">Positions</a>
                            </nav>
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('agencies.index') }}">Agencies</a>
                            </nav>
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Employee Type</a>
                            </nav>
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('departments.index') }}">Departments</a>
                            </nav>
                        </div>
                    </nav>
                     <!--End Groups -->
                    
                     <!--Contribution -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseContribution" aria-expanded="false" aria-controls="collapseContribution">
                                     Contribution
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseContribution" aria-labelledby="headingContribution"  data-bs-parent="#side2navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">SSS</a>
                            </nav>
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">PHIC</a>
                            </nav>
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">HDMF</a>
                            </nav>
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">TAX</a>
                            </nav>
                        </div>
                    </nav>
                     <!--End Groups -->
                     <!--Cutoff -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCutoff" aria-expanded="false" aria-controls="collapseCutoff">
                                     Cutoff setup
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseCutoff" aria-labelledby="headingCutoff"  data-bs-parent="#side2navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Payroll Cutoff</a>
                            </nav>
                        </div>
                    </nav>
                     <!--End Cutoff -->
                     <!--ERP -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseERP" aria-expanded="false" aria-controls="collapseERP">
                                     ERP setup
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseERP" aria-labelledby="headingERP"  data-bs-parent="#side2navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Add Evaluation Template</a>
                            </nav>
                        </div>
                    </nav>
                     <!--End ERP -->
                     <!--memo -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseMemo" aria-expanded="false" aria-controls="collapseMemo">
                                     Memo setup
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseMemo" aria-labelledby="headingMemo"  data-bs-parent="#side2navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Add Memo Template</a>
                            </nav>
                        </div>
                    </nav>
                     <!--End ERP -->

                </nav><!--Main nav -->
            </div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTransactions" aria-expanded="false" aria-controls="collapseTransactions">
                Transactions <!--Transactions -->
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseTransactions" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                 <!--Payroll -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePayroll" aria-expanded="false" aria-controls="collapsePayroll">
                                    Payroll
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePayroll" aria-labelledby="headingPayroll"  data-bs-parent="#side3navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Generate Payroll</a>
                            </nav>
                        </div>
                    </nav>
                 <!--End Payroll -->
                 <!--Loan -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLoan" aria-expanded="false" aria-controls="collapseLoan">
                                    Loan
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLoan" aria-labelledby="headingLoan"  data-bs-parent="#side3navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Add Loan Entry</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End Loan -->
                 <!--ATS -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseATS" aria-expanded="false" aria-controls="collapseATS">
                                    ATS
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseATS" aria-labelledby="headingATS"  data-bs-parent="#side3navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Add Manpower Request</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End ATS -->
                 <!--Retro -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRetro" aria-expanded="false" aria-controls="collapseRetro">
                                    Retro
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseRetro" aria-labelledby="headingRetro"  data-bs-parent="#side3navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Payroll Dispute Filing</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End Retro -->
                 <!--201 -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapse201" aria-expanded="false" aria-controls="collapse201">
                                    201 File
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapse201" aria-labelledby="heading201"  data-bs-parent="#side3navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">201 File Management</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">201 Filing</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End 201 -->
                 <!--DTR -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDTR" aria-expanded="false" aria-controls="collapseDTR">
                                    DTR
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseDTR" aria-labelledby="headingDTR"  data-bs-parent="#side3navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">DTR Correction and Approval</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">DTR Management</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End DTR -->
                 <!--Overtime -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseOvertime" aria-expanded="false" aria-controls="collapseOvertime">
                                    Overtime
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseOvertime" aria-labelledby="headingOvertime"  data-bs-parent="#side3navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">OT Filing and Approval</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">OT Request Management</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End Overtime -->
                 <!--Offset -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseOffset" aria-expanded="false" aria-controls="collapseOffset">
                                    Offset
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseOffset" aria-labelledby="headingOffset"  data-bs-parent="#side3navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Offset Filing and Approval</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Offset Request Management</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End Offset -->
                 <!--MEPR -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseMEPR" aria-expanded="false" aria-controls="collapseMEPR">
                                    EPR
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseMEPR" aria-labelledby="headingMEPR"  data-bs-parent="#side3navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">View My EPR</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">EPR Creation</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End MEPR -->
                 <!--MLeave -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseMLeave" aria-expanded="false" aria-controls="collapseMLeave">
                                    Leave
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseMLeave" aria-labelledby="headingMLeave"  data-bs-parent="#side3navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Leave Filing and Approval</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Leave Request Management</a>
                             </nav>
                        </div>
                    </nav>
                  <!--End MLeave -->
                 <!--Memorandum -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseMemorandum" aria-expanded="false" aria-controls="collapseMemorandum">
                                    Memorandum
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseMemorandum" aria-labelledby="headingMemorandum"  data-bs-parent="#side3navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">View My Memo (Tardiness & AWOL)</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Memo Approval</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End Memorandum -->
                  </nav><!--main nav -->
            </div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseReports" aria-expanded="false" aria-controls="collapseReports">
                Reports  <!--Reports -->
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseReports" aria-labelledby="headingThree" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                 <!--Masterlist -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseMasterlist" aria-expanded="false" aria-controls="collapseMasterlist">
                                    Masterlist
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseMasterlist" aria-labelledby="headingMasterlist"  data-bs-parent="#side4navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">View my EPR</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">EPR creation</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End Masterlist -->
                 <!--Plantilla -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePlantilla" aria-expanded="false" aria-controls="collapsePlantilla">
                                    Plantilla
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePlantilla" aria-labelledby="headingPlantilla"  data-bs-parent="#side4navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">View new hire employees</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">View resigned employees</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">View active employees</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End Masterlist -->
                 <!--Final pay -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseFinalpay" aria-expanded="false" aria-controls="collapseFinalpay">
                                    Final pay
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseFinalpay" aria-labelledby="headingFinalpay"  data-bs-parent="#side4navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">View new hire employees</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">View resigned employees</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">View active employees</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End Final pay -->
                 <!--Final pay -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseFinalpay" aria-expanded="false" aria-controls="collapseFinalpay">
                                    Final pay
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseFinalpay" aria-labelledby="headingFinalpay"  data-bs-parent="#side4navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">View new hire employees</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">View resigned employees</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">View active employees</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End Final pay -->
                 <!--DTR Report -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDTRReport" aria-expanded="false" aria-controls="collapseDTRReport">
                                    DTR Report
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseDTRReport" aria-labelledby="headingDTRReport"  data-bs-parent="#side4navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">DTR summary report</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">My DTR  report</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End DTR Report-->
                <!--RPayslip -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRPayslip" aria-expanded="false" aria-controls="collapseRPayslip">
                                    Payslip
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseRPayslip" aria-labelledby="headingRPayslip"  data-bs-parent="#side4navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Pay slip administrator</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">View my pay slip</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End RPayslip-->
                <!--Payroll Report -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePayrollReport" aria-expanded="false" aria-controls="collapsePayrollReport">
                                    Payroll Report
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePayrollReport" aria-labelledby="headingPayrollReport"  data-bs-parent="#side4navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">View final approved payroll</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End Payroll Report-->
                <!--Contribution -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRContribution" aria-expanded="false" aria-controls="collapseRContribution">
                                    Contribution
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseRContribution" aria-labelledby="headingRContribution"  data-bs-parent="#side4navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">SSS</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">PHIC</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">HDMF</a>
                             </nav>
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">Monthlt Report</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End Contribution-->
                <!--13th Month -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapse13thMonth" aria-expanded="false" aria-controls="collapse13thMonth">
                                    13th Month
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapse13thMonth" aria-labelledby="heading13thMonth"  data-bs-parent="#side4navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">View year to date 13th month computation</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End 13th Month-->
                <!--14th Month -->
                    <nav class="sb-sidenav-menu-nested nav">
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapse14thMonth" aria-expanded="false" aria-controls="collapse14thMonth">
                                    14th Month
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapse14thMonth" aria-labelledby="heading14thMonth"  data-bs-parent="#side4navAccordion">
                             <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('dashboard') }}">View year to date 14th month computation</a>
                             </nav>
                        </div>
                    </nav>
                 <!--End 14th Month-->

                    
                </nav>  <!--End nav main -->
            </div>
        
        </div>
    </div>
        
            
</nav>

