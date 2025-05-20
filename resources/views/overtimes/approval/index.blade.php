@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Overtime Approvals</h3>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('overtimes.index') }}">Overtime Management</a></li>
        <li class="breadcrumb-item active">Pending Approvals</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-danger text-white">
            Pending Overtime Approvals
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" id="overtimeApprovalTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Employee Type</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Total OT Hours</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Department</th>
                            <th>Reason</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overtimes as $i => $ot)
                            @php
                                $employee = optional($ot->employee->personalInfo)->full_name ?? $ot->employee_number;
                                $modalId = 'overtimeModal' . $ot->id;

                                // Use start and end datetime if available, else fallback
                                $start = $ot->start ?? ($ot->date && $ot->time_start ? $ot->date . ' ' . $ot->time_start : null);
                                $end = $ot->end ?? ($ot->date && $ot->time_end ? $ot->date . ' ' . $ot->time_end : null);

                                $startFormatted = $start ? \Carbon\Carbon::parse($start)->format('M d, Y h:i A') : '–';
                                $endFormatted = $end ? \Carbon\Carbon::parse($end)->format('M d, Y h:i A') : '–';

                                $totalHours = $ot->hours ?? ($start && $end ? \Carbon\Carbon::parse($start)->diffInHours(\Carbon\Carbon::parse($end)) : '–');

                                $badgeClass = [
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'declined' => 'danger'
                                ][$ot->status ?? 'pending'];
                            @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                 <td>{{ $ot->employee->personalInfo->full_name ?? 'N/A' }}</td>
                                <td>{{ $ot->employee->employmentInfo->employmentType->name ?? '—' }}</td>
                                <td>{{ $startFormatted }}</td>
                                <td>{{ $endFormatted }}</td>
                                <td>{{ is_numeric($totalHours) ? number_format($totalHours, 2) : $totalHours }}</td>
                                <td>{{ $ot->employee->employmentInfo->position->name ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($ot->status ?? 'pending') }}</span>
                                </td>
                                <td>{{ $ot->employee->employmentInfo->department->name ?? '—' }}</td>
                                <td>{{ $ot->reason }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#{{ $modalId }}">
                                        Take Action
                                    </button>

                                    {{-- ACTION MODAL --}}
                                    <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('overtimes.approval.update', $ot->id) }}">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="{{ $modalId }}Label">Overtime Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <dl class="row">
                                                            <dt class="col-sm-3">Employee</dt>
                                                            <dd class="col-sm-9">{{ $employee }}</dd>

                                                            <dt class="col-sm-3">Start</dt>
                                                            <dd class="col-sm-9">{{ $startFormatted }}</dd>

                                                            <dt class="col-sm-3">End</dt>
                                                            <dd class="col-sm-9">{{ $endFormatted }}</dd>

                                                            <dt class="col-sm-3">Total OT Hours</dt>
                                                            <dd class="col-sm-9">{{ is_numeric($totalHours) ? number_format($totalHours, 2) : $totalHours }}</dd>

                                                            <dt class="col-sm-3">Reason</dt>
                                                            <dd class="col-sm-9">{{ $ot->reason }}</dd>
                                                        </dl>

                                                        <hr>

                                                        <div class="mb-3">
                                                            <label for="status{{ $ot->id }}" class="form-label">Action</label>
                                                            <select name="status" id="status{{ $ot->id }}" class="form-select" required>
                                                                <option value="" disabled selected>-- Select Status --</option>
                                                                <option value="approved">Approve</option>
                                                                <option value="declined">Decline</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="remarks{{ $ot->id }}" class="form-label">Remarks</label>
                                                            <textarea name="remarks" id="remarks{{ $ot->id }}" class="form-control" placeholder="Optional remarks">{{ old('remarks') }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- /ACTION MODAL --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">No pending overtime approvals.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    <style>
        .dataTables_filter { margin-bottom: 1rem; }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#overtimeApprovalTable').DataTable({
                responsive: true,
                dom: '<"top d-flex justify-content-between align-items-center mb-2"Bf>rt<"bottom mt-3"ip>',
                buttons: [
                    { extend: 'csv', text: 'CSV', exportOptions: { columns: ':not(:last-child)' } },
                    { extend: 'excel', text: 'Excel', exportOptions: { columns: ':not(:last-child)' } },
                    { extend: 'pdf', text: 'PDF', exportOptions: { columns: ':not(:last-child)' } },
                    { extend: 'print', text: 'Print', exportOptions: { columns: ':not(:last-child)' } }
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                columnDefs: [
                    { responsivePriority: 1, targets: 1 },
                    { responsivePriority: 2, targets: 4 },
                    { responsivePriority: 3, targets: -1 },
                    { className: "text-center", targets: [0, 5, 6, 7] },
                    { className: "text-end", targets: -1 }
                ]
            });

            setTimeout(() => $('#success-alert').fadeOut('slow'), 3000);
        });
    </script>
@endpush
