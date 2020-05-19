<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Export Data in to the Excel
 */
trait ExcelExportable
{
    private $export_format = 'text/csv';
    /**
     * Define column names that need to be in Excel file
     *
     * @var array
     */
    protected $excelColumnNames = [];


    /**
     * Excel row data
     *
     * @var array
     */
    protected $excelRowData = [];


    /**
     * Genrarate to excel
     *
     * @return void
     */
    public function generate($fileName = '')
    {
        try {
            if (count($this->excelRowData) <= 0) {

                // TO DO:  Create custom Exception class and handle excel row related exceptions
                // throw new YourNewException('Custom Message')
                return null;
            }


            //TO DO:  Handle exception for Columns length and row length

            $fileName = $fileName."". $this->getDefaultFileName();
            return new StreamedResponse(function () {
                // Open output stream
                $handle = fopen('php://output', 'w');

                // Add CSV headers
                fputcsv($handle, $this->excelColumnNames);

                foreach ($this->excelRowData as $row) {
                    fputcsv($handle, (array)$row);
                }

                fclose($handle);
            }, 200, [
                'Content-Type' => $this->export_format,
                'Content-Disposition' => 'attachment; filename="'.$fileName.'".csv'
            ]);
        } catch (\Exception $ex) {

            Log::error("Report generate Exception".$ex->getMessage());
        }
    }

    /**
     * Get Default Filename
     *
     * @return void
     */
    private function getDefaultFileName()
    {

        return Carbon::now()->format('Y-m-d_H:i:s');
    }
}