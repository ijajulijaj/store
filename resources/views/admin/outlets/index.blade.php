@extends('layouts.app')
  
@section('contents')
  <div class="row">
    <div class="container-fluid">
        <!-- Add this at the top of your view -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <!-- Header -->
        <div class="outlet-header">
            <h2>All Outlets</h2>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Outlet Name</th>
                            <th>Outlet Code</th>
                            <th>Outlet Address</th>
                            <th>Outlet Number</th>
                            <th>Parent Address</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>S/N</th>
                            <th>Outlet Name</th>
                            <th>Outlet Code</th>
                            <th>Outlet Address</th>
                            <th>Outlet Number</th>
                            <th>Parent Address</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($outlets as $index => $outlet)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $outlet['outlet_name'] ?? '-' }}</td>
                            <td>{{ $outlet['outlet_code'] ?? '-' }}</td>
                            <td>{{ $outlet['outlet_address'] ?? '-' }}</td>
                            <td>{{ $outlet['outlet_number'] ?? '-' }}</td>
                            <td>{{ $outlet['parent_address'] ?? '-' }}</td>
                            <td>{{ $outlet['latitude'] ?? '-' }}</td>
                            <td>{{ $outlet['longitude'] ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
  </div>

@endsection