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
                <h3 class="card-title">Ticket Sales</h3>
            </div>
            <!-- /.card-header -->
            <a href="{{ URL::to('/sales/pdf') }}">Export PDF</a>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
{{--                    <div class="row">--}}
{{--                        <div class="col-sm-12 col-md-6">--}}
{{--                            <div class="dataTables_length" id="example1_length"><label>Show <select--}}
{{--                                        name="example1_length" aria-controls="example1"--}}
{{--                                        class="custom-select custom-select-sm form-control form-control-sm">--}}
{{--                                        <option value="10">10</option>--}}
{{--                                        <option value="25">25</option>--}}
{{--                                        <option value="50">50</option>--}}
{{--                                        <option value="100">100</option>--}}
{{--                                    </select> entries</label></div>--}}
{{--                        </div>--}}
{{--                        <div class="col-sm-12 col-md-6">--}}
{{--                            <div id="example1_filter" class="dataTables_filter"><label>Search:<input type="search"--}}
{{--                                                                                                     class="form-control form-control-sm"--}}
{{--                                                                                                     placeholder=""--}}
{{--                                                                                                     aria-controls="example1"></label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
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
{{--                    <div class="row">--}}
{{--                        <div class="col-sm-12 col-md-5">--}}
{{--                            <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing 1--}}
{{--                                to 10 of 57 entries--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-sm-12 col-md-12">--}}
{{--                            <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">--}}
{{--                                <ul class="pagination">--}}
{{--                                    <li class="paginate_button page-item previous disabled" id="example1_previous"><a--}}
{{--                                            href="#" aria-controls="example1" data-dt-idx="0" tabindex="0"--}}
{{--                                            class="page-link">Previous</a></li>--}}
{{--                                    <li class="paginate_button page-item active"><a href="#" aria-controls="example1"--}}
{{--                                                                                    data-dt-idx="1" tabindex="0"--}}
{{--                                                                                    class="page-link">1</a></li>--}}
{{--                                    <li class="paginate_button page-item "><a href="#" aria-controls="example1"--}}
{{--                                                                              data-dt-idx="2" tabindex="0"--}}
{{--                                                                              class="page-link">2</a></li>--}}
{{--                                    <li class="paginate_button page-item "><a href="#" aria-controls="example1"--}}
{{--                                                                              data-dt-idx="3" tabindex="0"--}}
{{--                                                                              class="page-link">3</a></li>--}}
{{--                                    <li class="paginate_button page-item "><a href="#" aria-controls="example1"--}}
{{--                                                                              data-dt-idx="4" tabindex="0"--}}
{{--                                                                              class="page-link">4</a></li>--}}
{{--                                    <li class="paginate_button page-item "><a href="#" aria-controls="example1"--}}
{{--                                                                              data-dt-idx="5" tabindex="0"--}}
{{--                                                                              class="page-link">5</a></li>--}}
{{--                                    <li class="paginate_button page-item "><a href="#" aria-controls="example1"--}}
{{--                                                                              data-dt-idx="6" tabindex="0"--}}
{{--                                                                              class="page-link">6</a></li>--}}
{{--                                    <li class="paginate_button page-item next" id="example1_next"><a href="#"--}}
{{--                                                                                                     aria-controls="example1"--}}
{{--                                                                                                     data-dt-idx="7"--}}
{{--                                                                                                     tabindex="0"--}}
{{--                                                                                                     class="page-link">Next</a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </section>
    <!-- /.content -->
@endsection



