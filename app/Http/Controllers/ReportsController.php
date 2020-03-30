<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use GuzzleHttp\Client;
use Barryvdh\DomPDF\Facade as PDF;

class ReportsController extends Controller
{
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
}
