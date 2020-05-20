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
use App\Services\Reports\ExportReportService;

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
     * @var ExportReportService
     */
    private $exportReportService;

    /**
     * ReportsController constructor.
     * @param ReportsService $reportService
     * @param TheatreService $theatreService
     * @param MovieService $movieService
     * @param ExportReportService $exportReportService
     */
    public function __construct(ReportsService $reportService, TheatreService $theatreService,
                                MovieService $movieService, ExportReportService $exportReportService)
    {
        $this->middleware('auth');
        $this->reportService =  $reportService;
        $this->theatreService = $theatreService;
        $this->movieService = $movieService;
        $this->exportReportService = $exportReportService;
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

        if (isset($requestParams['export'])){
            return  $this->exportReportService->getSelectionExport($theaterSales)->generate('box-office-summary-');
        }

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

            if (isset($requestParams['export'])){
                return  $this->exportReportService->getSelectionExport($records)->generate('ticket-sales-');
            }


        $history = ['fromDate' =>$fomDate, 'toDate' => $toDate, 'theatreId' => $theatreId];
        $theatres = $this->theatreService->getAllTheatres();

        return view('reports.ticketSales', compact('records', 'theatres', 'history'));

    }

    /**
     * @param Request $request
     * @param MessageBag $message_bag
     * @return Application|Factory|View
     */
    public function getDailyCollectionByMovieAndTheatre(Request $request, MessageBag $message_bag)
    {
        $requestParams = $request->all();
        $param = null;
        $records = null;
        $date = null;
        $movieId = null;
        $theatreId = null;
        if($requestParams) {
            $theatreId = $requestParams['theatreSelect'];
            $movieId = $requestParams['movieId'];
            $request->validate([
                'date' => 'required|date|date_format:Y-m-d',
            ]);
            $date = $requestParams['date'];
            $param = 'daily-collection?MovieId='.$movieId.'&TheatreId='.$theatreId.'&Date='.$date;
        }else {
            $param = '';
        }

        $response = $this->reportService->getApiData($param);
        if ($response){
            $records = $response->result;
        } else {
            $records = null;
        }

        $history = ['date' =>$date, 'movieId' => $movieId, 'theatreId' => $theatreId];
        $theatres = $this->theatreService->getAllTheatres();
        $movies = $this->movieService->getAllMovies();


        return view('reports.dailyCollection', compact('records', 'theatres', 'movies', 'history'));

    }

    public function getConcessionSalesByMovie(Request $request)
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
            $param = 'concession-sales?StartDate='.$fomDate.'&EndDate='.$toDate;
        } else {
            $param = '';
        }

        $response = $this->reportService->getApiData($param);
        if ($response){
            $records = $response->concessionSales;
        } else {
            $records = null;
        }

        if (isset($requestParams['export'])){
            return  $this->exportReportService->getSelectionExport($records)->generate('concession-sales-');
        }

        $dateRage = ['fromDate' =>$fomDate, 'toDate' => $toDate];

        return view('reports.concession', compact('records', 'dateRage'));

    }
}
