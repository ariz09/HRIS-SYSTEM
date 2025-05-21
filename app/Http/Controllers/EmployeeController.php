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

        foreach ($employees as $employee) {
            \Log::info("Employee #{$employee->id} - Employment Type ID: {$employee->employment_type_id}");
            if ($employee->employmentType) {
                \Log::info("Found employment type: {$employee->employmentType->name}");
            } else {
                \Log::warning("No employment type found for employee #{$employee->id}");
            }
        }

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
                    'address' => $request->address, // Ensure address is included
                    'profile_picture' => $profilePicPath,
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
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header = array_map('trim', array_shift($data));

        // Get the latest number only once
        $latestEmployee = EmploymentInfo::orderBy('employee_number', 'desc')->first();
        $latestNumber = $latestEmployee ? (int)$latestEmployee->employee_number : 0;

        foreach ($data as $row) {
            $row = array_combine($header, $row);

            // Generate employee number
            $latestNumber++;
            $newEmployeeNumber = str_pad($latestNumber, 6, '0', STR_PAD_LEFT);

            // Create user
            $user = User::create([
                'name' => $row['first_name'] . ' ' . $row['last_name'],
                'email' => $row['email'],
                'password' => bcrypt('PassW0rd@2025'),
            ]);

            // Create personal info
            PersonalInfo::create([
                'user_id' => $user->id,
                'first_name' => $row['first_name'],
                'middle_name' => $row['middle_name'],
                'last_name' => $row['last_name'],
                'name_suffix' => $row['name_suffix'],
                'preferred_name' => $row['preferred_name'],
                'gender' => $row['gender'],
                'birthday' => $row['birthday'],
                'email' => $row['email'],
                'phone_number' => $row['phone_number'],
                'civil_status' => $row['civil_status'],
            ]);

            // Create employment info
            EmploymentInfo::create([
                'user_id' => $user->id,
                'employee_number' => $newEmployeeNumber,
                'hiring_date' => $row['hiring_date'],
                'employment_status_id' => $row['employment_status_id'],
                'agency_id' => $row['agency_id'],
                'department_id' => $row['department_id'],
                'cdm_level_id' => $row['cdm_level_id'],
                'position_id' => $row['position_id'],
                'employment_type_id' => $row['employment_type_id'],
            ]);

            // Compensation info
            CompensationPackage::create([
                'employee_number' => $newEmployeeNumber,
                'basic_pay' => $row['basic_pay'],
                'rata' => $row['rata'],
                'comm_allowance' => $row['comm_allowance'],
                'transpo_allowance' => $row['transpo_allowance'],
                'parking_toll_allowance' => $row['parking_toll_allowance'],
                'clothing_allowance' => $row['clothing_allowance'],
                'atm_account_number' => $row['atm_account_number'],
                'bank_name' => $row['bank_name'],
            ]);
        }

        return redirect()->back()->with('success', 'Bulk upload successful.');
    }


public function downloadTemplate()
{
    $headers = [
        'first_name', 'middle_name', 'last_name', 'name_suffix', 'preferred_name', 'gender', 'birthday',
        'email', 'phone_number', 'civil_status', 'hiring_date', 'employment_status_id', 'agency_id',
        'department_id', 'cdm_level_id', 'position_id', 'employment_type_id', 'basic_pay', 'rata',
        'comm_allowance', 'transpo_allowance', 'parking_toll_allowance', 'clothing_allowance',
        'atm_account_number', 'bank_name'
    ];

    // Sample data (IDs from reference tables)
    $sample = [[
        'Juan', 'Dela', 'Cruz', '', 'JD', 'Male', '1990-01-01',
        'juan@example.com', '09171234567', 'Single', '2024-01-15', EmploymentStatus::first()?->id ?? '', Agency::first()?->id ?? '',
        Department::first()?->id ?? '', CDMLevel::first()?->id ?? '', Position::first()?->id ?? '', EmploymentType::first()?->id ?? '','25000', '5000',
        '2000', '1500', '1000','1000', '1234567890', 'BPI'
    ]];

    $callback = function () use ($headers, $sample) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $headers);

        foreach ($sample as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
    };

    return Response::stream($callback, 200, [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=employee_upload_template.csv",
    ]);
}


}
