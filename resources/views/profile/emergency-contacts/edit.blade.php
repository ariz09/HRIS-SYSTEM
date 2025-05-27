@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/validation.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>
            <i class="fas fa-address-book me-2"></i>
            My Emergency Contacts
        </h4>
        <a href="{{ route('profile.index') }}" 
            class="btn btn-outline-secondary rounded-pill" 
            id="back-button">
            <i class="fas fa-arrow-left me-1"></i> Back to Profile
        </a>
    </div>

    <form action="{{ route('profile.emergency-contacts.update') }}" method="POST" id="emergencyContactsForm">
        @csrf
        @method('PUT')

        <div id="contacts-container" class="row g-4">
            @foreach($contacts as $index => $contact)
                <div class="contact-card col-md-6">
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-2">
                            <h6 class="mb-0 text-white">
                                Contact #{{ $index + 1 }}
                            </h6>
                            @if($contact->id)
                                <button type="button" class="btn btn-sm btn-light delete-contact-btn text-danger rounded-circle" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteConfirmModal"
                                    data-contact-id="{{ $contact->id }}">
                                    <i class="fas fa-times"></i>
                                </button>
                            @else
                                <button type="button" class="btn btn-sm btn-light delete-contact-btn text-danger rounded-circle">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($contact->id)
                                <input type="hidden" name="contacts[{{ $index }}][id]" value="{{ $contact->id }}">
                            @endif
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" 
                                        name="contacts[{{ $index }}][fullname]" 
                                        class="form-control uppercase" 
                                        value="{{ old('contacts.'.$index.'.fullname', $contact->fullname) }}"
                                        required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Relationship <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                                    <select name="contacts[{{ $index }}][relationship]" class="form-select" required>
                                        <option value="">Select Relationship</option>
                                        @foreach($relationshipOptions as $value => $label)
                                            <option value="{{ $value }}" 
                                                {{ old('contacts.'.$index.'.relationship', $contact->relationship) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Contact Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" 
                                        name="contacts[{{ $index }}][contact_number]" 
                                        class="form-control" 
                                        value="{{ old('contacts.'.$index.'.contact_number', $contact->contact_number) }}"
                                        required
                                        pattern="^(09|\+639)\d{9}$"
                                        title="Philippine mobile number format (09xxxxxxxxx or +639xxxxxxxxx)">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <textarea name="contacts[{{ $index }}][address]" 
                                        class="form-control uppercase" 
                                        rows="2">{{ old('contacts.'.$index.'.address', $contact->address) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($contacts->count() < 2)
            <div class="alert alert-warning d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2 fs-4"></i>
                <div>You must provide at least 2 emergency contacts.</div>
            </div>
        @endif

        <div class="d-flex justify-content-between mt-1">
            <button type="button" id="add-contact-btn" class="btn btn-outline-primary rounded-pill mb-3">
                <i class="fas fa-plus me-1"></i> Add Contact
            </button>

            <button type="submit" class="btn btn-success rounded-pill mb-3">
                <i class="fas fa-save me-1"></i> Save Changes
            </button>
        </div>
    </form>
</div>

<!-- Contact Card Template -->
<template id="contact-template">
    <div class="contact-card col-md-6">
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-2">
                <h6 class="mb-0 text-white">
                    Contact #<span class="contact-number"></span>
                </h6>
                <button type="button" class="btn btn-sm btn-light delete-contact-btn text-danger rounded-circle">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="contacts[__INDEX__][fullname]" class="form-control uppercase" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Relationship <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                        <select name="contacts[__INDEX__][relationship]" class="form-select" required>
                            <option value="">Select Relationship</option>
                            @foreach($relationshipOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Contact Number <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" 
                            name="contacts[__INDEX__][contact_number]" 
                            class="form-control" 
                            required
                            pattern="^(09|\+639)\d{9}$"
                            title="Philippine mobile number format (09xxxxxxxxx or +639xxxxxxxxx)">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <textarea name="contacts[__INDEX__][address]" class="form-control uppercase" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Delete Contact</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this emergency contact?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" id="deleteContactForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/validation.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        let contactIndex = {{ $contacts->count() }};
        const addContactBtn = document.getElementById('add-contact-btn');
        const contactsContainer = document.getElementById('contacts-container');
        const contactTemplate = document.getElementById('contact-template');
        const deleteModal = document.getElementById('deleteConfirmModal');
        const deleteForm = document.getElementById('deleteContactForm');

        // Add new contact
        addContactBtn.addEventListener('click', () => {
            const newContact = contactTemplate.content.cloneNode(true);
            newContact.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace('__INDEX__', contactIndex);
            });
            newContact.querySelector('.contact-number').textContent = contactIndex + 1;
            contactsContainer.appendChild(newContact);
            contactIndex++;
        });

        // Handle delete buttons
        document.addEventListener('click', e => {
            const deleteBtn = e.target.closest('.delete-contact-btn');
            if (deleteBtn) {
                const contactCard = deleteBtn.closest('.contact-card');
                const contactId = deleteBtn.dataset.contactId;

                if (contactId) {
                    // Existing contact - set up the delete form
                    deleteForm.action = `/profile/emergency-contacts/${contactId}`;
                    const modal = new bootstrap.Modal(deleteModal);
                    modal.index();
                } else {
                    // New contact - just remove the card
                    contactCard.remove();
                }
            }
        });

        // Back button validation
        const backButton = document.getElementById('back-button');
        backButton.addEventListener('click', function(e) {
            const contactCards = document.querySelectorAll('.contact-card');
            if (contactCards.length < 2) {
                e.preventDefault();
                alert('You must provide at least 2 emergency contacts before going back.');
                return;
            }

            let isValid = true;
            for (let i = 0; i < 2; i++) {
                const card = contactCards[i];
                const fullname = card.querySelector('input[name*="[fullname]"]');
                const relationship = card.querySelector('select[name*="[relationship]"]');
                const contactNumber = card.querySelector('input[name*="[contact_number]"]');

                if (!fullname?.value.trim() || !relationship?.value || !contactNumber?.value.trim()) {
                    isValid = false;
                    break;
                }
            }

            if (!isValid) {
                e.preventDefault();
                alert('Please fill out all required fields for the first two emergency contacts before going back.');
            }
        });
    });
    </script>
@endpush