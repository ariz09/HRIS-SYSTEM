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

        // Create the main Employees sheet with proper headers
        $employeeSheet = new Worksheet($spreadsheet, 'Employees');
        $employeeSheet->setTitle('Employees');
        $spreadsheet->addSheet($employeeSheet, 0); // Insert at first position

        // Proper human-readable headers
        $headers = [
            'First Name',
            'Middle Name',
            'Last Name',
            'Name Suffix',
            'Preferred Name',
            'Gender',
            'Birthday',
            'Email',
            'Phone Number',
            'Civil Status',
            'Hiring Date',
            'Employment Status',
            'Agency',
            'Department',
            'CDM Level',
            'Position',
            'Employment Type',
            'Basic Pay',
            'RATA',
            'Commission Allowance',
            'Transportation Allowance',
            'Parking/Toll Allowance',
            'Clothing Allowance',
            'ATM Account Number',
            'Bank Name',
        ];
        $employeeSheet->fromArray($headers, null, 'A1');

        // Style header row
        $employeeSheet->freezePane('A2');
        $employeeSheet->getStyle('A1:Y1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);
        foreach (range('A', 'Y') as $col) {
            $employeeSheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Dropdown validation columns map: letters => reference sheets
        $dropdownColumns = [
            'L' => 'Employment_Status', // Employment Status
            'M' => 'Agencies',          // Agency
            'N' => 'Departments',       // Department
            'O' => 'CDM_Levels',        // CDM Level
            'P' => 'Positions',         // Position
            'Q' => 'Employment_Types',  // Employment Type
        ];

        foreach ($dropdownColumns as $col => $refSheet) {
            $rowCount = count($references[$refSheet]) + 1;
            for ($row = 2; $row <= 100; $row++) {
                $validation = $employeeSheet->getCell("{$col}{$row}")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                $validation->setAllowBlank(true);
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

        // Write and return the file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Employee_Upload_Template_with_IDs.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return Response::download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
