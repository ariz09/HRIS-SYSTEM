<?php
namespace App\Http\Controllers;

use App\Models\PersonalInfo;
use App\Models\EmploymentInfo;
use App\Models\CompensationPackage;
use App\Models\Agency;
use App\Models\Position;
use App\Models\Department;
use App\Models\EmploymentStatus;
use App\Models\EmploymentType;
use App\Models\CDMLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{

    public static function generateEmployeeNumber()
    {
        $latestEmployee = EmploymentInfo::orderBy('employee_number', 'desc')->first();
        $defaultEmployeeNumber = '000001';

        if ($latestEmployee) {
            $latestNumber = (int)$latestEmployee->employee_number;
            $defaultEmployeeNumber = str_pad($latestNumber + 1, 6, '0', STR_PAD_LEFT);
        }

        return $defaultEmployeeNumber;
    }

    public function index()
    {
        $employees = EmploymentInfo::with('personalInfo', 'agency', 'position', 'employmentStatus', 'department', 'employmentType')
            ->orderBy('employee_number')
            ->paginate(10);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {

        return view('employees.create', [
            'agencies' => Agency::all(),
            'positions' => Position::all(),
            'departments' => Department::all(),
            'statuses' => EmploymentStatus::all(),
            'employmentTypes' => EmploymentType::all(),
            'cdmLevels' => CDMLevel::all(),
            'defaultEmployeeNumber' => $this->generateEmployeeNumber(),
            'isEdit' => false // Pass false when creating
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone_number' => [
                'required',
                'regex:/^(09|\+639)\d{9}$/'
            ],
            'birthday' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'civil_status' => 'required|in:Single,Married,Divorced,Widowed,Separated',
            'hiring_date' => 'required|date',
            'basic_pay' => 'required|numeric',
            'employment_type_id' => 'required|exists:employment_types,id',
            'position_id' => 'required|exists:positions,id',
            'cdm_level_id' => 'required|exists:cdm_levels,id',
            'department_id' => 'required|exists:departments,id',
            'agency_id' => 'required|exists:agencies,id',
            'atm_account_number' => 'nullable|numeric',
            'address' => 'required|string|max:500', // Made address required
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'phone_number.regex' => 'The phone number must be a valid Philippine mobile number (e.g., 09171234567 or +639171234567)',
            'atm_account_number.numeric' => 'The ATM account number must contain only numbers',
            'address.required' => 'The address field is required.',
            'first_name' => 'The first_name field is required.',
            'last_name' => 'The last_name field is required.',
            'email' => 'The email field is required.',
            'birthday' => 'The birthday field is required.',
            'gender' => 'The gender field is required.',
            'civil_status' => 'The civil_status field is required.',
            'hiring_date' => 'The hiring_date field is required.',
            'basic_pay' => 'The basic_pay field is required.',
            'employment_type_id' => 'The employment_type field is required.',
            'position_id' => 'The position field is required.',
            'cdm_level_id' =>'The cdm_level field is required.',
            'department_id' => 'The department field is required.',
            'agency_id' => 'The agency field is required.',

        ]);

        try {
            DB::beginTransaction();

            // Handle profile picture upload
            $profilePicPath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicPath = $request->file('profile_picture')->store('profile_picture', 'public');
            }

            // Generate employee number
            $newEmployeeNumber = $this->generateEmployeeNumber();

            // Create User Account
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make('PassW0rd@2025'),
                'name' => $request->first_name . ' ' . $request->last_name,
                'role' => 'employee',
            ]);

            // Create Personal Info - MAKE SURE ALL FIELDS ARE INCLUDED
            $personalInfo = PersonalInfo::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'name_suffix' => $request->name_suffix,
                'preferred_name' => $request->preferred_name,
                'gender' => $request->gender,
                'birthday' => $request->birthday,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'civil_status' => $request->civil_status,
                'address' => $request->address, 
                'profile_picture' => $profilePicPath,
            ]);

            // Create Employment Info
            $employmentInfo = EmploymentInfo::create([
                'user_id' => $user->id,
                'employee_number' => $newEmployeeNumber,
                'hiring_date' => $request->hiring_date,
                'employment_status_id' => $request->employment_status_id,
                'agency_id' => $request->agency_id,
                'department_id' => $request->department_id,
                'cdm_level_id' => $request->cdm_level_id,
                'position_id' => $request->position_id,
                'employment_type_id' => $request->employment_type_id,
            ]);

            // Create Compensation Info
            CompensationPackage::create([
                'employee_number' => $newEmployeeNumber,
                'basic_pay' => $request->basic_pay,
                'rata' => $request->rata,
                'comm_allowance' => $request->comm_allowance,
                'transpo_allowance' => $request->transpo_allowance,
                'parking_toll_allowance' => $request->parking_toll_allowance,
                'clothing_allowance' => $request->clothing_allowance,
                'atm_account_number' => $request->atm_account_number,
                'bank_name' => $request->bank_name,
            ]);

            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error creating employee: " . $e->getMessage());
            return redirect()->route('employees.index')->with('error', 'Something went wrong. Please try again.');
        }
    }


            public function edit(EmploymentInfo $employee)
        {
            $employee->load(['personalInfo']); // Or use 'employee' if that's the relationship name

            return view('employees.edit', [
                'employee' => $employee,
                'statuses' => EmploymentStatus::all(),
                'employmentTypes' => EmploymentType::all(),
                'agencies' => Agency::all(),
                'departments' => Department::all(),
                'cdmLevels' => CDMLevel::all(),
                'positions' => Position::all(),
                'isEdit' => true
            ]);
        }

        public function update(Request $request, EmploymentInfo $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'name_suffix' => 'nullable|string|max:50',
            'preferred_name' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('personal_infos', 'email')->ignore($employee->personalInfo->id)
            ],
            'phone_number' => [
                'required',
                'regex:/^(09|\+639)\d{9}$/'
            ],
            'birthday' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'civil_status' => 'required|in:Single,Married,Divorced,Widowed,Separated',
            'address' => 'required|string|max:500',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    
            // Employment Info
            'hiring_date' => 'required|date',
            'employment_type_id' => 'required|exists:employment_types,id',
            'employment_status_id' => 'required|exists:employment_statuses,id',
            'position_id' => 'required|exists:positions,id',
            'cdm_level_id' => 'required|exists:cdm_levels,id',
            'department_id' => 'required|exists:departments,id',
            'agency_id' => 'required|exists:agencies,id',
    
            // Compensation
            'basic_pay' => 'required|numeric|min:0',
            'rata' => 'nullable|numeric|min:0',
            'comm_allowance' => 'nullable|numeric|min:0',
            'transpo_allowance' => 'nullable|numeric|min:0',
            'parking_toll_allowance' => 'nullable|numeric|min:0',
            'clothing_allowance' => 'nullable|numeric|min:0',
            'atm_account_number' => 'nullable|numeric',
            'bank_name' => 'nullable|string|max:255',
        ], [
            'phone_number.regex' => 'The phone number must be a valid Philippine mobile number (e.g., 09171234567 or +639171234567)',
            'atm_account_number.numeric' => 'The ATM account number must contain only numbers',
            'address.required' => 'The address field is required.',
            'first_name' => 'The first_name field is required.',
            'last_name' => 'The last_name field is required.',
            'email' => 'The email field is required.',
            'birthday' => 'The birthday field is required.',
            'gender' => 'The gender field is required.',
            'civil_status' => 'The civil_status field is required.',
            'hiring_date' => 'The hiring_date field is required.',
            'basic_pay' => 'The basic_pay field is required.',
            'employment_type_id' => 'The employment_type field is required.',
            'position_id' => 'The position field is required.',
            'cdm_level_id' =>'The cdm_level field is required.',
            'department_id' => 'The department field is required.',
            'agency_id' => 'The agency field is required.',
        ]);

        try {
            DB::beginTransaction();

            $profilePicPath = $employee->personalInfo->profile_picture ?? null;
            if ($request->hasFile('profile_picture')) {
                // Delete old profile pic if exists
                if ($profilePicPath && Storage::disk('public')->exists($profilePicPath)) {
                    Storage::disk('public')->delete($profilePicPath);
                }
                
                // Store new profile pic
                $profilePicPath = $request->file('profile_picture')->store('profile_picture', 'public');
            }

            // Update Personal Info - MAKE SURE TO INCLUDE ADDRESS
            if ($employee->personalInfo) {
                $employee->personalInfo->update([
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'name_suffix' => $request->name_suffix,
                    'preferred_name' => $request->preferred_name,
                    'gender' => $request->gender,
                    'birthday' => $request->birthday,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'civil_status' => $request->civil_status,
                    'address' => $request->address,
                    'profile_picture' => $profilePicPath,
                ]);
            }
            
            // Update Employment Info 
            $employee->update([
                'hiring_date' => $request->hiring_date,
                'employment_status_id' => $request->employment_status_id,
                'agency_id' => $request->agency_id,
                'department_id' => $request->department_id,
                'cdm_level_id' => $request->cdm_level_id,
                'position_id' => $request->position_id,
                'employment_type_id' => $request->employment_type_id,
            ]);
            
            // Update Compensation Package
            if ($employee->compensationPackage) {
                $employee->compensationPackage->update([
                    'basic_pay' => $request->basic_pay,
                    'rata' => $request->rata,
                    'comm_allowance' => $request->comm_allowance,
                    'transpo_allowance' => $request->transpo_allowance,
                    'parking_toll_allowance' => $request->parking_toll_allowance,
                    'clothing_allowance' => $request->clothing_allowance,
                    'atm_account_number' => $request->atm_account_number,
                    'bank_name' => $request->bank_name,
                ]);
            }



            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating employee: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function destroy(EmploymentInfo $employee)
    {
        try {
            DB::beginTransaction();

            // Delete related records
            if ($employee->compensationPackage) {
                $employee->compensationPackage()->delete();
            }

            if ($employee->personalInfo) {
                $employee->personalInfo()->delete();
            }

            // Delete user account
            if ($employee->user) {
                $employee->user()->delete();
            }

            // Finally delete the employee
            $employee->delete();

            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Employee deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting employee: ' . $e->getMessage());
            return redirect()->route('employees.index')->with('error', 'Error deleting employee: ' . $e->getMessage());
        }
    }

    public function bulkUpload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'employee_csv' => 'required|mimes:csv,txt|max:10240'
        ]);

        try {
            $file = $request->file('employee_csv');
            $data = array_map('str_getcsv', file($file));
            
            // Clean headers by removing everything after (including parentheses)
            $header = array_map(function($h) {
                return trim(preg_replace('/\s*\(.*\)\s*/', '', strtolower($h)));
            }, $data[0]);
            
            unset($data[0]); // remove header

            $errors = [];
            $successCount = 0;
            $rowIndex = 1;

            // Get the latest employee number once
            $latestEmployee = EmploymentInfo::orderBy('employee_number', 'desc')->first();
            $latestNumber = $latestEmployee ? (int)$latestEmployee->employee_number : 0;

            foreach ($data as $row) {
                $rowIndex++;

                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                DB::beginTransaction();

                try {
                    $rowData = array_combine($header, $row);
                    
                    // Extract just the ID from dropdown values (format: "ID - Description")
                    $idFields = [
                        'employment_status_id',
                        'agency_id',
                        'department_id',
                        'cdm_level_id',
                        'position_id',
                        'employment_type_id'
                    ];
                    
                    foreach ($idFields as $field) {
                        if (isset($rowData[$field]) && preg_match('/^(\d+)\s*-\s*.+/', $rowData[$field], $matches)) {
                            $rowData[$field] = $matches[1]; // Extract just the ID number
                        }
                    }

                    // Generate employee number
                    $latestNumber++;
                    $newEmployeeNumber = str_pad($latestNumber, 6, '0', STR_PAD_LEFT);

                    // Validate required fields
                    $validator = Validator::make($rowData, [
                        'first_name' => 'required|string|max:255',
                        'last_name' => 'required|string|max:255',
                        'email' => 'required|email|max:255|unique:users,email',
                        'phone_number' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
                        'birthday' => 'required|date',
                        'gender' => 'required|in:Male,Female,Other',
                        'civil_status' => 'required|in:Single,Married,Divorced,Widowed,Separated',
                        'hiring_date' => 'required|date',
                        'employment_type_id' => 'required|exists:employment_types,id',
                        'position_id' => 'required|exists:positions,id',
                        'cdm_level_id' => 'required|exists:cdm_levels,id',
                        'department_id' => 'required|exists:departments,id',
                        'agency_id' => 'required|exists:agencies,id',
                        'basic_pay' => 'required|numeric',
                        'atm_account_number' => 'nullable|numeric',
                        'address' => 'required|string|max:500',
                    ]);

                    if ($validator->fails()) {
                        throw new \Exception(implode(', ', $validator->errors()->all()));
                    }

                    // Create user
                    $user = User::create([
                        'name' => $rowData['first_name'] . ' ' . $rowData['last_name'],
                        'email' => $rowData['email'],
                        'password' => bcrypt('PassW0rd@2025'),
                        'role' => 'employee',
                    ]);

                    // Create personal info
                    PersonalInfo::create([
                        'user_id' => $user->id,
                        'first_name' => $rowData['first_name'],
                        'middle_name' => $rowData['middle_name'] ?? null,
                        'last_name' => $rowData['last_name'],
                        'name_suffix' => $rowData['name_suffix'] ?? null,
                        'preferred_name' => $rowData['preferred_name'] ?? null,
                        'gender' => $rowData['gender'],
                        'birthday' => $rowData['birthday'],
                        'email' => $rowData['email'],
                        'phone_number' => $rowData['phone_number'],
                        'civil_status' => $rowData['civil_status'],
                        'address' => $rowData['address'],
                    ]);

                    // Create employment info
                    EmploymentInfo::create([
                        'user_id' => $user->id,
                        'employee_number' => $newEmployeeNumber,
                        'hiring_date' => $rowData['hiring_date'],
                        'employment_status_id' => $rowData['employment_status_id'],
                        'agency_id' => $rowData['agency_id'],
                        'department_id' => $rowData['department_id'],
                        'cdm_level_id' => $rowData['cdm_level_id'],
                        'position_id' => $rowData['position_id'],
                        'employment_type_id' => $rowData['employment_type_id'],
                    ]);

                    // Create compensation package
                    CompensationPackage::create([
                    'employee_number' => $newEmployeeNumber,
                    'basic_pay' => $rowData['basic_pay'],
                    'rata' => $rowData['rata'] ?? 0,
                    'comm_allowance' => $rowData['comm_allowance'] ?? 0,
                    'transpo_allowance' => $rowData['transpo_allowance'] ?? 0,
                    'parking_toll_allowance' => $rowData['parking_toll_allowance'] ?? 0,
                    'clothing_allowance' => $rowData['clothing_allowance'] ?? 0,
                    'atm_account_number' => $rowData['atm_account_number'] ?? null,
                    'bank_name' => $rowData['bank_name'] ?? null,
                ]);

                DB::commit();
                $successCount++;
                
            } catch (\Exception $e) {
                DB::rollBack();
                $errors[] = [
                    'row' => $rowIndex,
                    'errors' => [$e->getMessage()],
                    'data' => $rowData ?? null
                ];
            }
        }

        if (count($errors) > 0) {
            return redirect()
                ->back()
                ->with('error', "$successCount employees uploaded, but " . count($errors) . " rows had errors.")
                ->with('error_details', $errors);
        }

        return redirect()->back()->with('success', "$successCount employees uploaded successfully.");

    } catch (\Exception $e) {
        return redirect()
            ->back()
            ->with('error', 'Error processing file: ' . $e->getMessage());
    }
}




}
