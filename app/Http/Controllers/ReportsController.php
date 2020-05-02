<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use GuzzleHttp\Client;
use Barryvdh\DomPDF\Facade as PDF;

class ReportsController extends Controller
{
    const REPORT_API = 'http://api.securedserver.xyz/api/report/';
    const SALES_URL = 'http://api.securedserver.xyz/api/report/sales';
    const SALES_THEATRES = 'http://api.securedserver.xyz/api/theatre';
    const SALES_THEATRE_WISE = 'http://api.securedserver.xyz/api/report/box-office?StartDate=2020-01-31&EndDate=2020-02-04';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sales()
    {

        $client = new Client();
        $res = $client->get(self::SALES_URL);
//        echo $res->getStatusCode(); // 200
//        echo $res->getBody();
        $response = json_decode($res->getBody());

//        dd($response);
        $theaterSales = $response->result->theatreSales;
//        dd($theaterSales);
        return view('reports.sales', compact('theaterSales'));


    }

    public function getSalesReport()
    {

    }

    public function export_pdf()
    {
        $client = new Client();
        $res = $client->get('http://api.securedserver.xyz/api/report/sales');
        $response = json_decode($res->getBody());
        $theaterSales = $response->result->theatreSales;
        // Send data to the view using loadView function of PDF facade
        $pdf = PDF::loadView('reports.sales', $theaterSales);
        // If you want to store the generated pdf to the server then you can use the store function
        $pdf->save(storage_path() . '_filename.pdf');
        // Finally, you can download the file using download function
        return $pdf->download('reports.sales');

    }

    public function boxOfficeSummary(Request $request)
    {
        $requestParams = $request->all();
        $fomDate = null;
        $toDate = null;
        if($requestParams){
            $request->validate([
                'from-date'   => 'required|date|date_format:Y-m-d|before:to-date',
                'to-date'   => 'required|date|date_format:Y-m-d|after:from-date',
            ]);
            $fomDate = $requestParams['from-date'];
            $toDate = $requestParams['to-date'];
            $param = 'box-office?StartDate='.$fomDate.'&EndDate='.$toDate;
        } else {
            $param = 'box-office';
        }

        $client = new Client();
        $res = $client->get(self::REPORT_API.$param);
        $response = json_decode($res->getBody());
        $theaterSales = $response->theatreSales;

        $dateRage = ['fromDate' =>$fomDate, 'toDate' => $toDate];

        return view('reports.summary', compact('theaterSales', 'dateRage'));


    }
}
