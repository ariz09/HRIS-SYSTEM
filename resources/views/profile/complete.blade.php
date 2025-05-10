<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Complete Your Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('profile.complete.store') }}" class="space-y-6">
                        @csrf

                        <!-- Dependents -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium">Dependents</h3>
                            <div id="dependents-container">
                                @foreach($dependents as $index => $dependent)
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div>
                                            <x-input-label for="dependents[{{ $index }}][name]" value="Name" />
                                            <x-text-input id="dependents[{{ $index }}][name]" name="dependents[{{ $index }}][name]" type="text" class="mt-1 block w-full" :value="old('dependents.'.$index.'.name', $dependent->name)" required />
                                        </div>
                                        <div>
                                            <x-input-label for="dependents[{{ $index }}][relationship]" value="Relationship" />
                                            <x-text-input id="dependents[{{ $index }}][relationship]" name="dependents[{{ $index }}][relationship]" type="text" class="mt-1 block w-full" :value="old('dependents.'.$index.'.relationship', $dependent->relationship)" required />
                                        </div>
                                        <div>
                                            <x-input-label for="dependents[{{ $index }}][birthdate]" value="Birthdate" />
                                            <x-text-input id="dependents[{{ $index }}][birthdate]" name="dependents[{{ $index }}][birthdate]" type="date" class="mt-1 block w-full" :value="old('dependents.'.$index.'.birthdate', $dependent->birthdate)" required />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addDependent()" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Add Dependent
                            </button>
                        </div>

                        <!-- Education -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium">Education</h3>
                            <div id="education-container">
                                @foreach($educations as $index => $education)
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                        <div>
                                            <x-input-label for="educations[{{ $index }}][school]" value="School" />
                                            <x-text-input id="educations[{{ $index }}][school]" name="educations[{{ $index }}][school]" type="text" class="mt-1 block w-full" :value="old('educations.'.$index.'.school', $education->school)" required />
                                        </div>
                                        <div>
                                            <x-input-label for="educations[{{ $index }}][degree]" value="Degree" />
                                            <x-text-input id="educations[{{ $index }}][degree]" name="educations[{{ $index }}][degree]" type="text" class="mt-1 block w-full" :value="old('educations.'.$index.'.degree', $education->degree)" required />
                                        </div>
                                        <div>
                                            <x-input-label for="educations[{{ $index }}][start_date]" value="Start Date" />
                                            <x-text-input id="educations[{{ $index }}][start_date]" name="educations[{{ $index }}][start_date]" type="date" class="mt-1 block w-full" :value="old('educations.'.$index.'.start_date', $education->start_date)" required />
                                        </div>
                                        <div>
                                            <x-input-label for="educations[{{ $index }}][end_date]" value="End Date" />
                                            <x-text-input id="educations[{{ $index }}][end_date]" name="educations[{{ $index }}][end_date]" type="date" class="mt-1 block w-full" :value="old('educations.'.$index.'.end_date', $education->end_date)" required />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addEducation()" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Add Education
                            </button>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium">Emergency Contact</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="emergency_contact[name]" value="Name" />
                                    <x-text-input id="emergency_contact[name]" name="emergency_contact[name]" type="text" class="mt-1 block w-full" :value="old('emergency_contact.name', $emergencyContact?->name)" required />
                                </div>
                                <div>
                                    <x-input-label for="emergency_contact[relationship]" value="Relationship" />
                                    <x-text-input id="emergency_contact[relationship]" name="emergency_contact[relationship]" type="text" class="mt-1 block w-full" :value="old('emergency_contact.relationship', $emergencyContact?->relationship)" required />
                                </div>
                                <div>
                                    <x-input-label for="emergency_contact[contact_number]" value="Contact Number" />
                                    <x-text-input id="emergency_contact[contact_number]" name="emergency_contact[contact_number]" type="text" class="mt-1 block w-full" :value="old('emergency_contact.contact_number', $emergencyContact?->contact_number)" required />
                                </div>
                            </div>
                        </div>

                        <!-- Employment History -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium">Employment History</h3>
                            <div id="employment-history-container">
                                @foreach($employmentHistories as $index => $history)
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                        <div>
                                            <x-input-label for="employment_histories[{{ $index }}][company]" value="Company" />
                                            <x-text-input id="employment_histories[{{ $index }}][company]" name="employment_histories[{{ $index }}][company]" type="text" class="mt-1 block w-full" :value="old('employment_histories.'.$index.'.company', $history->company)" required />
                                        </div>
                                        <div>
                                            <x-input-label for="employment_histories[{{ $index }}][position]" value="Position" />
                                            <x-text-input id="employment_histories[{{ $index }}][position]" name="employment_histories[{{ $index }}][position]" type="text" class="mt-1 block w-full" :value="old('employment_histories.'.$index.'.position', $history->position)" required />
                                        </div>
                                        <div>
                                            <x-input-label for="employment_histories[{{ $index }}][start_date]" value="Start Date" />
                                            <x-text-input id="employment_histories[{{ $index }}][start_date]" name="employment_histories[{{ $index }}][start_date]" type="date" class="mt-1 block w-full" :value="old('employment_histories.'.$index.'.start_date', $history->start_date)" required />
                                        </div>
                                        <div>
                                            <x-input-label for="employment_histories[{{ $index }}][end_date]" value="End Date" />
                                            <x-text-input id="employment_histories[{{ $index }}][end_date]" name="employment_histories[{{ $index }}][end_date]" type="date" class="mt-1 block w-full" :value="old('employment_histories.'.$index.'.end_date', $history->end_date)" required />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addEmploymentHistory()" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Add Employment History
                            </button>
                        </div>

                        <!-- Employment Info -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium">Employment Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="employment_info[employee_id]" value="Employee ID" />
                                    <x-text-input id="employment_info[employee_id]" name="employment_info[employee_id]" type="text" class="mt-1 block w-full" :value="old('employment_info.employee_id', $employmentInfo?->employee_id)" required />
                                </div>
                                <div>
                                    <x-input-label for="employment_info[department]" value="Department" />
                                    <x-text-input id="employment_info[department]" name="employment_info[department]" type="text" class="mt-1 block w-full" :value="old('employment_info.department', $employmentInfo?->department)" required />
                                </div>
                                <div>
                                    <x-input-label for="employment_info[position]" value="Position" />
                                    <x-text-input id="employment_info[position]" name="employment_info[position]" type="text" class="mt-1 block w-full" :value="old('employment_info.position', $employmentInfo?->position)" required />
                                </div>
                            </div>
                        </div>

                        <!-- Employment Status -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium">Employment Status</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="employment_status[status]" value="Status" />
                                    <select id="employment_status[status]" name="employment_status[status]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="active" {{ old('employment_status.status', $employmentStatus?->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="on_leave" {{ old('employment_status.status', $employmentStatus?->status) == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                                        <option value="terminated" {{ old('employment_status.status', $employmentStatus?->status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="employment_status[effective_date]" value="Effective Date" />
                                    <x-text-input id="employment_status[effective_date]" name="employment_status[effective_date]" type="date" class="mt-1 block w-full" :value="old('employment_status.effective_date', $employmentStatus?->effective_date)" required />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function addDependent() {
            const container = document.getElementById('dependents-container');
            const index = container.children.length;

            const html = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <x-input-label for="dependents[${index}][name]" value="Name" />
                        <x-text-input id="dependents[${index}][name]" name="dependents[${index}][name]" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="dependents[${index}][relationship]" value="Relationship" />
                        <x-text-input id="dependents[${index}][relationship]" name="dependents[${index}][relationship]" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="dependents[${index}][birthdate]" value="Birthdate" />
                        <x-text-input id="dependents[${index}][birthdate]" name="dependents[${index}][birthdate]" type="date" class="mt-1 block w-full" required />
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', html);
        }

        function addEducation() {
            const container = document.getElementById('education-container');
            const index = container.children.length;

            const html = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <x-input-label for="educations[${index}][school]" value="School" />
                        <x-text-input id="educations[${index}][school]" name="educations[${index}][school]" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="educations[${index}][degree]" value="Degree" />
                        <x-text-input id="educations[${index}][degree]" name="educations[${index}][degree]" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="educations[${index}][start_date]" value="Start Date" />
                        <x-text-input id="educations[${index}][start_date]" name="educations[${index}][start_date]" type="date" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="educations[${index}][end_date]" value="End Date" />
                        <x-text-input id="educations[${index}][end_date]" name="educations[${index}][end_date]" type="date" class="mt-1 block w-full" required />
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', html);
        }

        function addEmploymentHistory() {
            const container = document.getElementById('employment-history-container');
            const index = container.children.length;

            const html = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <x-input-label for="employment_histories[${index}][company]" value="Company" />
                        <x-text-input id="employment_histories[${index}][company]" name="employment_histories[${index}][company]" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="employment_histories[${index}][position]" value="Position" />
                        <x-text-input id="employment_histories[${index}][position]" name="employment_histories[${index}][position]" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="employment_histories[${index}][start_date]" value="Start Date" />
                        <x-text-input id="employment_histories[${index}][start_date]" name="employment_histories[${index}][start_date]" type="date" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="employment_histories[${index}][end_date]" value="End Date" />
                        <x-text-input id="employment_histories[${index}][end_date]" name="employment_histories[${index}][end_date]" type="date" class="mt-1 block w-full" required />
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', html);
        }
    </script>
    @endpush
</x-app-layout>
