@extends('layouts.main')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Reports</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Box Office Summary Movie wise</h3>
            </div>
            <!-- /.card-header -->
            <form method="POST" action="{{ route('reports.boxofficesummary') }}">
                {{ csrf_field() }}
                <input type="date" id="from-date" name="from-date" value="{{$dateRage['fromDate'] ? $dateRage['fromDate'] : ''}}" required>
                <input type="date" id="to-date" name="to-date" value="{{$dateRage['toDate'] ? $dateRage['toDate'] : ''}}" required>
                <button href="javascript:" class="btn btn-primary" type="submit">Select Date Range</button>
            </form>
            <a href="{{ URL::to('/sales/pdf') }}">Export PDF</a>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">

                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example1" class="table table-bordered table-striped dataTable" role="grid"
                                   aria-describedby="example1_info">
                                <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-sort="ascending"
                                        aria-label="Rendering engine: activate to sort column descending"
                                        style="width: 170px;">Movie Name
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Browser: activate to sort column ascending" style="width: 220px;">
                                        Complement Tickets
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Platform(s): activate to sort column ascending"
                                        style="width: 194px;">Total Booked Tickets
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 144px;">Total Tickets
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="CSS grade: activate to sort column ascending" style="width: 101px;">
                                        Box Office
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="CSS grade: activate to sort column ascending" style="width: 101px;">
                                        Tax
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="CSS grade: activate to sort column ascending" style="width: 101px;">
                                        Net Income
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($theaterSales as $theaterSale)
                                    <tr role="row" class="odd">
                                        <td class="sorting_1">{{$theaterSale->movieName}}</td>
                                        <td>{{$theaterSale->complimentTickets}}</td>
                                        <td>{{$theaterSale->totalBookedTicket}}</td>
                                        <td>{{$theaterSale->totalTickets}}</td>
                                        <td>{{$theaterSale->boxOffice}}</td>
                                        <td>{{$theaterSale->tax}}</td>
                                        <td>{{$theaterSale->netIncome}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </section>
    <!-- /.content -->
@endsection



