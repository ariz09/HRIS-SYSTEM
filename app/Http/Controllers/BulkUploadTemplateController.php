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
use Illuminate\Support\Facades\Log;

class BulkUploadTemplateController extends Controller
{
    public function downloadTemplate()
    {
        try {
            // Increase resources
            ini_set('memory_limit', '512M');
            set_time_limit(300);

            // Verify temp directory
            $tempDir = sys_get_temp_dir();
            if (!is_writable($tempDir)) {
                throw new \Exception("Temp directory not writable: $tempDir");
            }

            $spreadsheet = new Spreadsheet();
            $spreadsheet->removeSheetByIndex(0); // Remove default sheet

            // Pull actual DB data for dropdown references with chunking
            $references = $this->getReferenceData();

            // Create reference sheets with error handling
            $this->createReferenceSheets($spreadsheet, $references);

            // Create the main Employees sheet
            $employeeSheet = $spreadsheet->getSheet(0);
            $employeeSheet->setTitle('Employees');

            // Set headers and sample data
            $this->setupEmployeeSheet($employeeSheet);

            // Apply styles and validation
            $this->formatEmployeeSheet($employeeSheet, $references);

            // Write and return the file with error handling
            return $this->generateDownloadResponse($spreadsheet);
            
        } catch (\Exception $e) {
            Log::error('Template download failed: '.$e->getMessage()."\n".$e->getTraceAsString());
            return back()->with('error', 'Failed to generate template. Please try again later.');
        }
    }

    protected function getReferenceData()
    {
        return [
            'Agencies' => Agency::select('id', 'name')->get()->toArray(),
            'Departments' => Department::select('id', 'name')->get()->toArray(),
            'Employment_Status' => EmploymentStatus::select('id', 'name')->get()->toArray(),
            'CDM_Levels' => CDMLevel::select('id', 'name')->get()->toArray(),
            'Positions' => Position::select('id', 'name')->get()->toArray(),
            'Employment_Types' => EmploymentType::select('id', 'name')->get()->toArray(),
        ];
    }

    protected function createReferenceSheets($spreadsheet, $references)
    {
        foreach ($references as $sheetName => $data) {
            try {
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
            } catch (\Exception $e) {
                Log::error("Failed to create reference sheet $sheetName: ".$e->getMessage());
                continue;
            }
        }
    }

    protected function setupEmployeeSheet($sheet)
    {
        // Headers with government IDs
        $headers = [
            'first_name', 'middle_name', 'last_name', 'name_suffix', 'preferred_name',
            'gender', 'birthday', 'email', 'phone_number', 'civil_status',
            'hiring_date', 'employment_status_id', 'agency_id', 'department_id',
            'cdm_level_id', 'position_id', 'employment_type_id', 'basic_pay',
            'rata', 'comm_allowance', 'transpo_allowance', 'parking_toll_allowance',
            'clothing_allowance', 'atm_account_number', 'bank_name', 'address',
            'sss_number', 'pag_ibig_number', 'philhealth_number', 'tin'
        ];
        
        $sheet->fromArray($headers, null, 'A1');

        // Sample data with government IDs
        $sampleData = [
            'John', 'Middle', 'Doe', '', 'JD',
            'Male', '1990-01-01', 'john.doe@example.com', '09171234567', 'Single',
            '2024-01-15', '1 - Active', '1 - Sample Agency', '1 - Sample Department',
            '1 - Sample Level', '1 - Sample Position', '1 - Regular', '25000',
            '5000', '2000', '1500', '1000', '1000', '1234567890', 'BPI',
            '123 Sample Street, Sample City', '12-3456789-0', '1234-5678-9012',
            '12-345678901-2', '123-456-789-012'
        ];
        
        $sheet->fromArray($sampleData, null, 'A2');
    }

    protected function formatEmployeeSheet($sheet, $references)
    {
        $sheet->freezePane('A2');
        
        // Style header row
        $sheet->getStyle('A1:AD1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9E1F2']]
        ]);

        // Style sample data row
        $sheet->getStyle('A2:AD2')->applyFromArray([
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF2CC']]
        ]);

        // Auto-size columns
        foreach (range('A', 'AD') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set dropdown validations
        $this->addDropdownValidations($sheet, $references);
    }

    protected function addDropdownValidations($sheet, $references)
    {
        $dropdownColumns = [
            'L' => 'Employment_Status',
            'M' => 'Agencies',
            'N' => 'Departments',
            'O' => 'CDM_Levels',
            'P' => 'Positions',
            'Q' => 'Employment_Types',
        ];

        foreach ($dropdownColumns as $col => $refSheet) {
            if (!isset($references[$refSheet])) continue;
            
            $rowCount = count($references[$refSheet]) + 1;
            for ($row = 2; $row <= 100; $row++) {
                try {
                    $validation = $sheet->getCell("{$col}{$row}")->getDataValidation();
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
                } catch (\Exception $e) {
                    Log::error("Failed to set validation for {$col}{$row}: ".$e->getMessage());
                }
            }
        }

        // Gender validation
        $this->setSimpleValidation($sheet, 'F2', '"Male,Female,Other"');
        
        // Civil status validation
        $this->setSimpleValidation($sheet, 'J2', '"Single,Married,Divorced,Widowed,Separated"');
    }

    protected function setSimpleValidation($sheet, $cell, $formula)
    {
        try {
            $validation = $sheet->getCell($cell)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowDropDown(true);
            $validation->setFormula1($formula);
        } catch (\Exception $e) {
            Log::error("Failed to set validation for $cell: ".$e->getMessage());
        }
    }

    protected function generateDownloadResponse($spreadsheet)
    {
        $fileName = 'Employee_Upload_Template_'.date('Ymd_His').'.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);

        try {
            $writer = new Xlsx($spreadsheet);
            $writer->save($tempFile);

            if (!file_exists($tempFile)) {
                throw new \Exception("Failed to generate spreadsheet file");
            }

            return response()
                ->download($tempFile, $fileName, [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ])
                ->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            if (file_exists($tempFile)) {
                @unlink($tempFile);
            }
            throw $e;
        } finally {
            // Clean up
            unset($spreadsheet);
        }
    }
}