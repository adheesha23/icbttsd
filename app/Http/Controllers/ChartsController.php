<?php

namespace App\Http\Controllers;

use App\Services\MovieService;
use App\Services\Reports\ExportReportService;
use App\Services\ReportsService;
use App\Services\TheatreService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\View\View;

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
                $param = 'ticket-sales-range?StartDate=2020-01-01&EndDate=2020-06-01&TheatreId=1';
            }
            $response = $this->reportService->getApiData($param);
            $records = $response->results;

            foreach ($records as $record) {
                $movieArr[] = $record->movieName;
                $salesArr[] = $record->totalSales;
                $taxArr[] = $record->tax;
            }

            $history = ['fromDate' => $fomDate, 'toDate' => $toDate, 'theatreId' => $theatreId];
            $theatres = $this->theatreService->getAllTheatres();

            return view('charts.movieSales', compact('records', 'theatres', 'history', 'movieArr', 'salesArr', 'taxArr'));
        }
    }
}
