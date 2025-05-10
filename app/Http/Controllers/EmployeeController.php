<?php
// app/Http/Controllers/EmployeeController.php
namespace App\Http\Controllers;

use App\Models\PersonalInfo;
use App\Models\EmploymentInfo;
use App\Models\CompensationPackage;
use App\Models\Agency;
use App\Models\Position;
use App\Models\Department;
use App\Models\EmploymentStatus;
use App\Models\EmploymentType;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = EmploymentInfo::with('personalInfo', 'agency', 'position', 'employmentStatus', 'department', 'employmentType')->get(); // Corrected 'employmentTypes' to 'employmentType'
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $agencies = Agency::all();
        $positions = Position::all();
        $departments = Department::all();
        $statuses = EmploymentStatus::all();
        $employmentTypes = EmploymentType::all();

        return view('employees.create', compact('agencies', 'positions', 'departments', 'statuses','employmentTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'employee_number' => 'required|unique:employment_infos,employee_number',
            'hiring_date' => 'required|date',
            'basic_pay' => 'required|numeric',
            'employment_type_id' => 'required|exists:employment_types,id',
        ]);
        // Generate Employee Number
        $latestEmployee = EmploymentInfo::latest('id')->first();
        $newEmployeeNumber = '000001';
        if ($latestEmployee) {
            $latestNumber = (int)substr($latestEmployee->employee_number, -6);
            $newEmployeeNumber = str_pad($latestNumber + 1, 6, '0', STR_PAD_LEFT);
        }

        // Create Personal Info
        $personalInfo = PersonalInfo::create([
            'user_id' => auth()->id(),
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
        ]);

        // Create Employment Info
        $employmentInfo = EmploymentInfo::create([
            'user_id' => auth()->id(),
            'employee_number' => $newEmployeeNumber,
            'hiring_date' => $request->hiring_date,
            'employment_status_id' => $request->employment_status_id,
            'agency_id' => $request->agency_id,
            'department_id' => $request->department_id,
            'cdm_level_id' => $request->cdm_level_id,
            'position_id' => $request->position_id,
            'employment_type_id' => $request->employment_type_id, // <-- ADD THIS
        ]);

        // Create Compensation Package
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
    }

    public function destroy($id)
    {
        // Find the employee by ID
        $employee = EmploymentInfo::findOrFail($id);

        // Delete the employee
        $employee->delete();

        // Redirect with a success message
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
