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
                <h3 class="card-title">Occupancy By Session</h3>
            </div>
            <!-- /.card-header -->


            <form role="form" method="POST" action="{{ route('reports.occupancy') }}">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>From Date:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="date" class="form-control" data-inputmask-alias="datetime"
                                       data-inputmask-inputformat="dd/mm/yyyy" id="from-date" name="from-date"
                                       value="{{$history['fromDate'] ? $history['fromDate'] : ''}}" required>
                            </div>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-md-3">
                            <label>To Date:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="date" class="form-control" data-inputmask-alias="datetime"
                                       data-inputmask-inputformat="dd/mm/yyyy" id="to-date" name="to-date"
                                       value="{{$history['toDate'] ? $history['toDate'] : ''}}" required>
                            </div>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-md-3">
                            <label>Select Theatre</label>
                            <select class="form-control select2" name="theatreSelect" id="theatreSelect">
                                <option value="0" selected="selected">Choose Theatre</option>
                                @foreach($theatres as $theatre)
                                    <option
                                        value="{{$theatre->id}}" {{$history['theatreId'] == $theatre->id?'selected':''}}>{{$theatre->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="export" name="export" value="1">
                                <label class="form-check-label">Export Report</label>
                            </div>
                            <div class="mt-md-2">
                                <button href="javascript:" class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>

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
                                        style="width: 170px;">Movie
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Browser: activate to sort column ascending" style="width: 220px;">
                                        Theatre Halls
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Platform(s): activate to sort column ascending"
                                        style="width: 194px;">Total Tickets
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 144px;">Used Tickets
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 144px;">Start Time
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 144px;">Date
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 144px;">Day
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 144px;">Percentage
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($records)
                                    @foreach($records as $record)
                                        @foreach($record->occupancyPercs as $occupancy)
                                        <tr role="row" class="odd">
                                            <td class="sorting_1">{{$record->movieName}}</td>
                                            <td>{{$occupancy->theartreHalls}}</td>
                                            <td>{{$occupancy->totalTickets}}</td>
                                            <td>{{$occupancy->usedTickets}}</td>
                                            <td>{{$occupancy->startTime}}</td>
                                            <td>{{$occupancy->date}}</td>
                                            <td>{{$occupancy->day}}</td>
                                            <td>{{number_format((float)$occupancy->percentage, 2, '.', '')}}</td>
                                        </tr>
                                    @endforeach
                                    @endforeach
                                @endif
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


