<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Illuminate\Support\Facades\Response;
use App\Models\Agency;
use App\Models\Department;
use App\Models\EmploymentStatus;
use App\Models\CDMLevel;
use App\Models\Position;
use App\Models\EmploymentType;

class BulkUploadTemplateController extends Controller
{
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // Remove default sheet

        // Pull actual DB data for dropdown references
        $agencies = Agency::select('id', 'name')->get()->toArray();
        $departments = Department::select('id', 'name')->get()->toArray();
        $employmentStatuses = EmploymentStatus::select('id', 'name')->get()->toArray();
        $cdmLevels = CDMLevel::select('id', 'name')->get()->toArray();
        $positions = Position::select('id', 'name')->get()->toArray();
        $employmentTypes = EmploymentType::select('id', 'name')->get()->toArray();

        $references = [
            'Agencies' => $agencies,
            'Departments' => $departments,
            'Employment_Status' => $employmentStatuses,
            'CDM_Levels' => $cdmLevels,
            'Positions' => $positions,
            'Employment_Types' => $employmentTypes,
        ];

        // Create reference sheets with ID - Name in column B
        foreach ($references as $sheetName => $data) {
            $sheet = new Worksheet($spreadsheet, $sheetName);
            $sheet->setTitle($sheetName);
            $spreadsheet->addSheet($sheet);
            $sheet->fromArray(['id', 'value'], null, 'A1');

            foreach ($data as $index => $item) {
                $row = $index + 2;
                $sheet->setCellValue("A$row", $item['id']);
                $sheet->setCellValue("B$row", "{$item['id']} - {$item['name']}");
            }

            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
        }

        // Create the main Employees sheet
        $employeeSheet = new Worksheet($spreadsheet, 'Employees');
        $employeeSheet->setTitle('Employees');
        $spreadsheet->addSheet($employeeSheet, 0);

        // Headers with simple field names
        $headers = [
            'first_name',
            'middle_name',
            'last_name',
            'name_suffix',
            'preferred_name',
            'gender',
            'birthday',
            'email',
            'phone_number',
            'civil_status',
            'hiring_date',
            'employment_status_id',
            'agency_id',
            'department_id',
            'cdm_level_id',
            'position_id',
            'employment_type_id',
            'basic_pay',
            'rata',
            'comm_allowance',
            'transpo_allowance',
            'parking_toll_allowance',
            'clothing_allowance',
            'atm_account_number',
            'bank_name',
            'address'
        ];
        $employeeSheet->fromArray($headers, null, 'A1');

        // Add sample data in row 2
        $sampleData = [
            'John',
            'Middle',
            'Doe',
            '',
            'JD',
            'Male',
            '1990-01-01',
            'john.doe@example.com',
            '09171234567',
            'Single',
            '2024-01-15',
            '1 - Active',
            '1 - Sample Agency',
            '1 - Sample Department',
            '1 - Sample Level',
            '1 - Sample Position',
            '1 - Regular',
            '25000',
            '5000',
            '2000',
            '1500',
            '1000',
            '1000',
            '1234567890',
            'BPI',
            '123 Sample Street, Sample City'
        ];
        $employeeSheet->fromArray($sampleData, null, 'A2');

        // Style header row
        $employeeSheet->freezePane('A2');
        $employeeSheet->getStyle('A1:Z1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9E1F2']
            ]
        ]);

        // Style sample data row
        $employeeSheet->getStyle('A2:Z2')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFF2CC']
            ]
        ]);

        foreach (range('A', 'Z') as $col) {
            $employeeSheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Dropdown validation columns
        $dropdownColumns = [
            'L' => 'Employment_Status',
            'M' => 'Agencies',
            'N' => 'Departments',
            'O' => 'CDM_Levels',
            'P' => 'Positions',
            'Q' => 'Employment_Types',
        ];

        foreach ($dropdownColumns as $col => $refSheet) {
            $rowCount = count($references[$refSheet]) + 1;
            for ($row = 2; $row <= 100; $row++) {
                $validation = $employeeSheet->getCell("{$col}{$row}")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Invalid option');
                $validation->setError('Value is not in list.');
                $validation->setPromptTitle('Pick from list');
                $validation->setPrompt('Please pick a value from the dropdown.');
                $validation->setFormula1("'$refSheet'!\$B\$2:\$B\$$rowCount");
            }
        }

        // Add data validation for gender
        $genderValidation = $employeeSheet->getCell('F2')->getDataValidation();
        $genderValidation->setType(DataValidation::TYPE_LIST);
        $genderValidation->setErrorStyle(DataValidation::STYLE_STOP);
        $genderValidation->setAllowBlank(false);
        $genderValidation->setShowDropDown(true);
        $genderValidation->setFormula1('"Male,Female,Other"');

        // Add data validation for civil status
        $civilStatusValidation = $employeeSheet->getCell('J2')->getDataValidation();
        $civilStatusValidation->setType(DataValidation::TYPE_LIST);
        $civilStatusValidation->setErrorStyle(DataValidation::STYLE_STOP);
        $civilStatusValidation->setAllowBlank(false);
        $civilStatusValidation->setShowDropDown(true);
        $civilStatusValidation->setFormula1('"Single,Married,Divorced,Widowed,Separated"');

        // Write and return the file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Employee_Upload_Template.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return Response::download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
