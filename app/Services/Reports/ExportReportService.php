<?php

namespace App\Services\Reports;


/**
 * Class SelectionsReportService
 * @package App\Services\Reports
 */
class ExportReportService extends BaseReportService
{
    /**
     * @param $records
     * @return $this
     */
    public function getSelectionExport($records)
    {
        $recordsArr = json_decode(json_encode($records), true);
        $array_keys = array();
        $array_values = array();
        foreach ($recordsArr as $record){
            $array_keys = array_keys($record);
            $array_values[] = array_values($record);
        }

        $this->excelColumnNames = $array_keys;

        $this->excelRowData = $array_values;

        return $this;
    }
}
