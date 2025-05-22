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
use Illuminate\Support\Str;


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

        /*       foreach ($employees as $employee) {
            \Log::info("Employee #{$employee->id} - Employment Type ID: {$employee->employment_type_id}");
            if ($employee->employmentType) {
                \Log::info("Found employment type: {$employee->employmentType->name}");
            } else {
                \Log::warning("No employment type found for employee #{$employee->id}");
            }
        } */

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
            'cdm_level_id' => 'The cdm_level field is required.',
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
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $employee->user_id,
            'phone_number' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
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
            'employment_status_id' => 'required|exists:employment_statuses,id',
            'atm_account_number' => 'nullable|numeric',
            'address' => 'required|string|max:500',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Handle profile picture
            $profilePicPath = $employee->personalInfo->profile_picture ?? null;
            if ($request->hasFile('profile_picture')) {
                if ($profilePicPath && Storage::disk('public')->exists($profilePicPath)) {
                    Storage::disk('public')->delete($profilePicPath);
                }
                $profilePicPath = $request->file('profile_picture')->store('profile_picture', 'public');
            }

            // Update Personal Info
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

            // Update User
            if ($employee->user) {
                $employee->user->update([
                    'email' => $request->email,
                    'name' => $request->first_name . ' ' . $request->last_name,
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
            $compensation = CompensationPackage::where('employee_number', $employee->employee_number)->first();
            if ($compensation) {
                $compensation->update([
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
            DB::rollBack();
            Log::error('Error updating employee: ' . $e->getMessage());

            return redirect()->back()->with('error', 'An error occurred while updating the employee.');
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
        $request->validate([
            'employee_csv' => 'required|mimes:csv,txt|max:10240'
        ]);
    
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
    
        // Enable query logging for debugging
        DB::enableQueryLog();
    
        foreach ($data as $row) {
            $rowIndex++;
    
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }
    
            try {
                $rowData = array_combine($header, $row);
                
                // Log the row data for debugging
                Log::debug("Processing row $rowIndex", $rowData);
    
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
                ], [
                    'phone_number.regex' => 'The phone number must be a valid Philippine mobile number (e.g., 09171234567 or +639171234567)',
                    'atm_account_number.numeric' => 'The ATM account number must contain only numbers',
                    'address.required' => 'The address field is required.',
                    'exists' => 'The selected :attribute is invalid.',
                    'unique' => 'The :attribute has already been taken.',
                ]);
    
                if ($validator->fails()) {
                    $errors[] = [
                        'row' => $rowIndex,
                        'errors' => $validator->errors()->all(),
                        'data' => $rowData
                    ];
                    continue;
                }
    
                DB::beginTransaction();
    
                $employeeNumber = self::generateEmployeeNumber();
                Log::info("Generated employee number: $employeeNumber");
    
                // Create User Account
                $user = User::create([
                    'email' => $rowData['email'],
                    'password' => Hash::make('PassW0rd@2025'),
                    'name' => $rowData['first_name'] . ' ' . $rowData['last_name'],
                    'role' => 'employee',
                ]);
                Log::info("Created user with ID: {$user->id}");
    
                // Create Personal Info
                $personalInfo = PersonalInfo::create([
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
                Log::info("Created personal info with ID: {$personalInfo->id}");
    
                // Create Employment Info
                $employmentInfo = EmploymentInfo::create([
                    'user_id' => $user->id,
                    'employee_number' => $employeeNumber,
                    'hiring_date' => $rowData['hiring_date'],
                    'employment_status_id' => $rowData['employment_status_id'] ?? EmploymentStatus::first()->id,
                    'agency_id' => $rowData['agency_id'],
                    'department_id' => $rowData['department_id'],
                    'cdm_level_id' => $rowData['cdm_level_id'],
                    'position_id' => $rowData['position_id'],
                    'employment_type_id' => $rowData['employment_type_id'],
                ]);
                Log::info("Created employment info with ID: {$employmentInfo->id}");
    
                // Create Compensation Package
                $compensation = CompensationPackage::create([
                    'employee_number' => $employeeNumber,
                    'basic_pay' => $rowData['basic_pay'],
                    'rata' => $rowData['rata'] ?? 0,
                    'comm_allowance' => $rowData['comm_allowance'] ?? 0,
                    'transpo_allowance' => $rowData['transpo_allowance'] ?? 0,
                    'parking_toll_allowance' => $rowData['parking_toll_allowance'] ?? 0,
                    'clothing_allowance' => $rowData['clothing_allowance'] ?? 0,
                    'atm_account_number' => $rowData['atm_account_number'] ?? null,
                    'bank_name' => $rowData['bank_name'] ?? null,
                ]);
                Log::info("Created compensation package with ID: {$compensation->id}");
    
                DB::commit();
                $successCount++;
                
            } catch (\Exception $e) {
                DB::rollBack();
                $errorMessage = "Row $rowIndex: " . $e->getMessage();
                Log::error($errorMessage);
                Log::error($e->getTraceAsString());
                
                $errors[] = [
                    'row' => $rowIndex,
                    'errors' => [$errorMessage],
                    'data' => $rowData ?? null
                ];
            }
        }
    
        // Log all executed queries
        Log::debug('Executed queries:', DB::getQueryLog());
        DB::disableQueryLog();
    
        $message = "$successCount employees uploaded successfully.";
        if (count($errors) > 0) {
            $message .= " " . count($errors) . " rows had errors.";
        }
    
        return back()->with([
            'success' => $message,
            'uploadErrors' => $errors
        ]);
    }
}
