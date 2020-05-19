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
                <h3 class="card-title">Movie's Daily Collection By Theatre</h3>
            </div>
            <!-- /.card-header -->


            <form role="form" method="POST" action="{{ route('reports.dailycollection') }}">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>Date:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                {{--                                <input type="text" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" im-insert="false">--}}
                                <input type="date" class="form-control" data-inputmask-alias="datetime"
                                       data-inputmask-inputformat="dd/mm/yyyy" id="date" name="date"
                                       value="{{$history['date'] ? $history['date'] : ''}}" required>
                            </div>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-md-3">
                            <label>Select Movie</label>
                            <select class="form-control select2" name="movieId" id="movieId">
                                <option value="0" selected="selected">Choose Movie</option>
                                @foreach($movies as $movie)
                                    <option
                                        value="{{$movie->id}}" {{$history['movieId'] == $movie->id?'selected':''}}>{{$movie->name}}</option>
                                @endforeach
                            </select>
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
                        <div class="form-group col-md-3 mt-md-4">
                            <label></label>
                            <div class="container">
                                <button href="javascript:" class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Movie Details</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <tbody>
                        @if($records)
                        <tr>
                            <td>{{'Movie Name'}}</td>
                            <td>{{$records->movieName}}</td>
                        </tr>
                        <tr>
                            <td>{{'Theatre'}}</td>
                            <td>{{$records->theatre}}</td>
                        </tr>
                        <tr>
                            <td>{{'Date'}}</td>
                            <td>{{$records->date}}</td>
                        </tr>
                        <tr>
                            <td>{{'Total Sales'}}</td>
                            <td>{{$records->totalSales}}</td>
                        </tr>
                        <tr>
                            <td>{{'Tax'}}</td>
                            <td>{{$records->tax}}</td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>








            <div>

            </div>

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
                                        style="width: 170px;">Session Start
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Browser: activate to sort column ascending" style="width: 220px;">
                                        Class
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Platform(s): activate to sort column ascending"
                                        style="width: 194px;">Ticket Price
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 144px;">Sold
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 144px;">Amount
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 144px;">Tax
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($records && $records->sales)
                                    @foreach($records->sales as $sale)
                                        <tr role="row" class="odd">
                                            <td class="sorting_1">{{$sale->sessionStart}}</td>
                                            <td>{{$sale->className}}</td>
                                            <td>{{number_format((float)$sale->rate, 2, '.', '')}}</td>
                                            <td>{{$sale->sold}}</td>
                                            <td>{{number_format((float)$sale->amount, 2, '.', '')}}</td>
                                            <td>{{number_format((float)$sale->taxAmount, 2, '.', '')}}</td>
                                        </tr>
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


