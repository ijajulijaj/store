<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Vendor;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VendorsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $data = [
                'outlet_code' => $row['outlet_code'],
                'location' => $row['location'],
                'mch_code' => $row['mch_code'],
                'article_no' => $row['article_no'],
                'article_description' => $row['article_description'],
                'uom' => $row['uom'],
                'eanno' => $row['eanno'],
            ];

            // Check if a record with exactly the same values already exists
            $exists = Vendor::where($data)->exists();

            if (!$exists) {
                Vendor::create($data);
            }
        }
    }

    private function parseDate($dateString, $fieldName)
    {
        Log::info("Parsing date for {$fieldName}: {$dateString}");

        // If the date string is empty or null, return null
        if (empty($dateString)) {
            return null;
        }

        // Check if the date is in Excel date format (numeric)
        if (is_numeric($dateString)) {
            try {
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateString);
                return $date->format('Y-m-d');
            } catch (\Exception $e) {
                Log::warning("Failed to parse Excel date for {$fieldName}: {$dateString}");
                return null;
            }
        }

        // Attempt to parse the date string directly
        try {
            $date = new DateTime($dateString);
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            Log::warning("Failed to parse date for {$fieldName} with DateTime: {$dateString}");
            return null;
        }

        // Attempt to parse using common date formats
        $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y', 'd-m-Y', 'm-d-Y'];
        foreach ($formats as $format) {
            try {
                $date = DateTime::createFromFormat($format, $dateString);
                if ($date && $date->format($format) === $dateString) {
                    return $date->format('Y-m-d');
                }
            } catch (\Exception $e) {
                Log::warning("Failed to parse date for {$fieldName} with format {$format}: {$dateString}");
            }
        }

        // Log the failure to parse the date, and return null
        Log::error("All parsing attempts failed for {$fieldName}. Returning null.");
        return null;
    }
}
