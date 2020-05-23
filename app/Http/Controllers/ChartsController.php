<?php

namespace App\Http\Controllers;

use App\Services\MovieService;
use App\Services\Reports\ExportReportService;
use App\Services\ReportsService;
use App\Services\TheatreService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChartsController extends Controller
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
     * ChartsController constructor.
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
     * @param MessageBag $message_bag
     * @return Application|Factory|View
     */
    public function movieSalesByTheatre(Request $request, MessageBag $message_bag)
    {
        if(Auth::user()->role == 1 || Auth::user()->role == 2) {
            $requestParams = $request->all();
            $param = null;
            $records = null;
            $fomDate = null;
            $toDate = null;
            $theatreId = null;
            if ($requestParams) {
                $theatreId = $requestParams['theatreSelect'];
                $request->validate([
                    'from-date' => 'required|date|date_format:Y-m-d|before:to-date',
                    'to-date' => 'required|date|date_format:Y-m-d|after:from-date',
                ]);
                $fomDate = $requestParams['from-date'];
                $toDate = $requestParams['to-date'];
                $param = 'ticket-sales-range?StartDate=' . $fomDate . '&EndDate=' . $toDate . '&TheatreId=' . $theatreId;
            } else {
                $param = 'ticket-sales-range?StartDate=2020-01-01&EndDate=2020-06-01';
            }
            $response = $this->reportService->getApiData($param);
            if($response){
                $records = $response->results;
            } else {
                $records = null;
            }

            foreach ($records as $record) {
                $movieArr[] = $record->movieName;
                $salesArr[] = number_format((float)$record->totalSales, 2, '.', '');
                $taxArr[] = number_format((float)$record->tax, 2, '.', '');
            }

            $history = ['fromDate' => $fomDate, 'toDate' => $toDate, 'theatreId' => $theatreId];
            $theatres = $this->theatreService->getAllTheatres();

            return view('charts.movieSales', compact('records', 'theatres', 'history', 'movieArr', 'salesArr', 'taxArr'));
        } else {
            return redirect('home');
        }
    }

    /**
     * @param Request $request
     * @param MessageBag $message_bag
     * @return Application|Factory|RedirectResponse|Redirector|View|StreamedResponse|void|null
     */
    public function getMovieTicketSales(Request $request, MessageBag $message_bag)
    {
        if (Auth::user()->role == 1 || Auth::user()->role == 2) {
            $requestParams = $request->all();
            $fomDate = null;
            $toDate = null;

            if ($requestParams) {
                $request->validate([
                    'from-date' => 'required|date|date_format:Y-m-d|before:to-date',
                    'to-date' => 'required|date|date_format:Y-m-d|after:from-date',
                ]);
                $fomDate = $requestParams['from-date'];
                $toDate = $requestParams['to-date'];
                $param = 'box-office?StartDate=' . $fomDate . '&EndDate=' . $toDate;
            } else {
                $param = 'box-office?StartDate=2020-01-01&EndDate=2020-06-01';
            }

            $response = $this->reportService->getApiData($param);
            if($response){
                $theaterSales = $response->theatreSales;
            } else {
                $theaterSales = null;
            }

            foreach ($theaterSales as $record) {
                $movieArr[] = $record->movieName;
                $ticketsArr[] = $record->totalTickets;
            }

            $history = ['fromDate' => $fomDate, 'toDate' => $toDate];

            return view('charts.ticketSales', compact('movieArr', 'ticketsArr', 'history'));
        } else {
            return redirect('home');
        }
    }

    /**
     * @param Request $request
     * @param MessageBag $message_bag
     * @return Application|Factory|RedirectResponse|Redirector|View
     */
    public function getMovieTicketsIncome(Request $request, MessageBag $message_bag)
    {
        if (Auth::user()->role == 1 || Auth::user()->role == 2) {
            $requestParams = $request->all();
            $fomDate = null;
            $toDate = null;

            if ($requestParams) {
                $request->validate([
                    'from-date' => 'required|date|date_format:Y-m-d|before:to-date',
                    'to-date' => 'required|date|date_format:Y-m-d|after:from-date',
                ]);
                $fomDate = $requestParams['from-date'];
                $toDate = $requestParams['to-date'];
                $param = 'box-office?StartDate=' . $fomDate . '&EndDate=' . $toDate;
            } else {
                $param = 'box-office?StartDate=2020-01-01&EndDate=2020-06-01';
            }

            $response = $this->reportService->getApiData($param);
            if($response){
                $theaterSales = $response->theatreSales;
            } else {
                $theaterSales = null;
            }

            foreach ($theaterSales as $record) {
                $movieArr[] = $record->movieName;
                $ticketsArr[] = number_format((float)$record->netIncome, 2, '.', '');
            }

            $history = ['fromDate' => $fomDate, 'toDate' => $toDate];

            return view('charts.ticketsIncome', compact('movieArr', 'ticketsArr', 'history'));
        } else {
            return redirect('home');
        }
    }
}
