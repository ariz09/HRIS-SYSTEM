@extends('layouts.app')
<style>
    /* Custom small close button */
    .btn-close {
        background-size: 0.65rem;
        opacity: 0.8;
    }
    .btn-close:hover {
        opacity: 1;
    }
    
    /* Tight modal spacing */
    .modal-content {
        border-radius: 0.3rem;
    }
</style>

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Inactive Users</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Inactive Users</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-users me-1"></i>
            Users Waiting Approval
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="inactiveUsers" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registered At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inactiveUsers as $user)
                            <tr>
                                <td>{{ $user->employmentInfo->employee_number }}</td>
                                <td>{{ $user->name }} </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#activateModal{{ $user->id }}">
                                        <i class="fas fa-user-check"></i> Activate
                                    </button>
                                    
                                    
<!-- Activation Modal - Simplified but keeps your red theme -->
<div class="modal fade" id="activateModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <!-- Red Header with X button -->
            <div class="modal-header bg-danger text-white py-2 px-3">
                <h6 class="modal-title mb-0">Activate User</h6>
                <button type="button" class="btn btn-sm btn-light text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                    <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                </button>
            </div>
            
            <!-- Compact Body -->
            <div class="modal-body p-3">
                <div class="d-flex align-items-start mb-3">
                    <div class="bg-danger bg-opacity-10 p-2 rounded-circle me-3">
                        <i class="fas fa-user-check text-danger"></i>
                    </div>
                    <div>
                        <p class="mb-1 fw-bold">Confirm activation for:</p>
                        <div class="small">
                            <div class="mb-1"><span class="text-muted">Name:</span> {{ $user->name }}</div>
                            <div class="mb-1"><span class="text-muted">ID:</span> {{ $user->employmentInfo->employee_number ?? 'N/A' }}</div>
                            <div><span class="text-muted">Email:</span> {{ $user->email }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Small Footer Buttons -->
            <div class="modal-footer p-2 border-0">
                <button type="button" 
                        class="btn btn-sm btn-outline-secondary px-3" 
                        data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <form action="{{ route('users.activate', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="btn btn-sm btn-success px-3">
                        <i class="fas fa-check me-1"></i> Activate
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No inactive users awaiting approval.</td>
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
<style>
    
    .modal-header {
        padding: 1rem;
    }
    .list-group-item {
        border-left: none;
        border-right: none;
    }
    .list-group-item:first-child {
        border-top: none;
    }
</style>
@endpush
<script>
    setDatatable("inactiveUsers", {
        dom: 'rtip', // minimal layout
        buttons: [] // no export buttons
    }); 
</script>