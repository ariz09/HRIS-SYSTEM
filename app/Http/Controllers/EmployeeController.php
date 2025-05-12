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

class EmployeeController extends Controller
{
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
        // Generate default employee number
        $latestEmployee = EmploymentInfo::orderBy('employee_number', 'desc')->first();
        $defaultEmployeeNumber = '000001';

        if ($latestEmployee) {
            $latestNumber = (int)$latestEmployee->employee_number;
            $defaultEmployeeNumber = str_pad($latestNumber + 1, 6, '0', STR_PAD_LEFT);
        }

        return view('employees.create', [
            'agencies' => Agency::all(),
            'positions' => Position::all(),
            'departments' => Department::all(),
            'statuses' => EmploymentStatus::all(),
            'employmentTypes' => EmploymentType::all(),
            'cdmLevels' => CDMLevel::all(),
            'defaultEmployeeNumber' => $defaultEmployeeNumber
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'hiring_date' => 'required|date',
            'basic_pay' => 'required|numeric',
            'employment_type_id' => 'required|exists:employment_types,id',
        ]);

        try {
            // Generate employee number
            $latestEmployee = EmploymentInfo::orderBy('employee_number', 'desc')->first();
            $newEmployeeNumber = '000001';
            if ($latestEmployee) {
                $latestNumber = (int)$latestEmployee->employee_number;
                $newEmployeeNumber = str_pad($latestNumber + 1, 6, '0', STR_PAD_LEFT);
            }

            // Predefined password
            $predefinedPassword = 'PassW0rd@2025';

            // Create User Account with predefined password
            $user = User::create([
                'email' => $request->email,  // Email is saved but not used for email-related functionality
                'password' => Hash::make($predefinedPassword), // Hash the predefined password
                'name' => $request->first_name . ' ' . $request->last_name, // Combine first and last name
                'role' => 'employee', // Set a role, can be adjusted as needed
            ]);

            // Create Personal Info
            $personalInfo = PersonalInfo::create([
                'user_id' => $user->id, // Link user to personal info
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'name_suffix' => $request->name_suffix,
                'preferred_name' => $request->preferred_name,
                'gender' => $request->gender,
                'birthday' => $request->birthday,
                'email' => $request->email,  // Email kept here for storage purposes
                'phone_number' => $request->phone_number,
                'civil_status' => $request->civil_status,
            ]);

            // Create Employment Info
            $employmentInfo = EmploymentInfo::create([
                'user_id' => $user->id, // Link user to employment info
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

            return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error("Error creating employee: " . $e->getMessage());
            return redirect()->route('employees.index')->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function destroy(EmploymentInfo $employee)
    {
        try {
            \DB::beginTransaction();

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

            \DB::commit();
            return redirect()->route('employees.index')->with('success', 'Employee deleted successfully');
        } catch (\Exception $e) {
            \DB::rollback();
            Log::error('Error deleting employee: ' . $e->getMessage());
            return redirect()->route('employees.index')->with('error', 'Error deleting employee: ' . $e->getMessage());
        }
    }
}
