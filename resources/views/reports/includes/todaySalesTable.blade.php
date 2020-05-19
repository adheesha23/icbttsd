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
                            Theatre
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Platform(s): activate to sort column ascending"
                            style="width: 194px;">Total Sales
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                            aria-label="Engine version: activate to sort column ascending"
                            style="width: 144px;">Tax
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        <tr role="row" class="odd">
                            <td class="sorting_1">{{$record->movieName}}</td>
                            <td>{{$record->theatre}}</td>
                            <td>{{number_format((float)$record->totalSales, 2, '.', '')}}</td>
                            <td>{{number_format((float)$record->tax, 2, '.', '')}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
