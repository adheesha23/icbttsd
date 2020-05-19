<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Requests;
use GuzzleHttp\Client;
use Barryvdh\DomPDF\Facade as PDF;
use App\Services\ReportsService;
use App\Services\TheatreService;
use App\Services\MovieService;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\MessageBag;

class ReportsController extends Controller
{
    /**
     * @var ReportsService
     */
    private $reportService;
    /**
     * @var TheatreService
     */
    private $theatreService;
    /**
     * @var MovieService
     */
    private $movieService;

    /**
     * ReportsController constructor.
     * @param ReportsService $reportService
     * @param TheatreService $theatreService
     * @param MovieService $movieService
     */
    public function __construct(ReportsService $reportService, TheatreService $theatreService, MovieService $movieService)
    {
        $this->middleware('auth');
        $this->reportService =  $reportService;
        $this->theatreService = $theatreService;
        $this->movieService = $movieService;
    }

    /**
     * @return mixed
     */
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

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
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
            $param = 'box-office?StartDate=2020-01-01&EndDate=2020-06-01';
        }

        $response = $this->reportService->getApiData($param);

        $theaterSales = $response->theatreSales;

        $dateRage = ['fromDate' =>$fomDate, 'toDate' => $toDate];

        return view('reports.summary', compact('theaterSales', 'dateRage'));

    }

    /**
     * @return Application|Factory|View
     */
    public function getTheatres()
    {
        $records = $this->theatreService->getAllTheatres();
        return view('reports.theatres', compact('records'));
    }

    /**
     * @return Application|Factory|View
     */
    public function getAllMovies()
    {
        $records = $this->movieService->getAllMovies();
        return view('reports.movies', compact('records'));
    }

    /**
     * @param Request $request
     * @param MessageBag $message_bag
     * @return Application|Factory|View
     */
    public function getTicketSalesTodayByTheatre(Request $request, MessageBag $message_bag)
    {
        $requestParams = $request->all();
        $param = null;
        $records = null;

        if($requestParams) {
            $theatreId = $requestParams['theatreSelect'];
            $nowTime = Carbon::now();
            $toDate = date('Y-m-d', strtotime($nowTime->toDateTimeString()));

            $param = 'ticket-sales?TheatreId='.$theatreId.'&Date=' . $toDate;

            $response = $this->reportService->getApiData($param);
            $records = $response->results;
        }

        $theatres = $this->theatreService->getAllTheatres();

        return view('reports.todaySales', compact('records', 'theatres'));

    }

    /**
     * @param Request $request
     * @param MessageBag $message_bag
     * @return Application|Factory|View
     */
    public function getTicketSalesByTheatreAndDate(Request $request, MessageBag $message_bag)
    {
        $requestParams = $request->all();
        $param = null;
        $records = null;
        $fomDate = null;
        $toDate = null;
        $theatreId = null;
        if($requestParams) {
            $theatreId = $requestParams['theatreSelect'];
            $request->validate([
                'from-date' => 'required|date|date_format:Y-m-d|before:to-date',
                'to-date' => 'required|date|date_format:Y-m-d|after:from-date',
            ]);
            $fomDate = $requestParams['from-date'];
            $toDate = $requestParams['to-date'];
            $param = 'ticket-sales-range?StartDate=' . $fomDate . '&EndDate=' . $toDate . '&TheatreId=' . $theatreId;
        }else {
            $param = 'ticket-sales-range?StartDate=2020-01-01&EndDate=2020-06-01&TheatreId=1';
        }
            $response = $this->reportService->getApiData($param);
            $records = $response->results;


        $history = ['fromDate' =>$fomDate, 'toDate' => $toDate, 'theatreId' => $theatreId];
        $theatres = $this->theatreService->getAllTheatres();

        return view('reports.ticketSales', compact('records', 'theatres', 'history'));

    }
}
