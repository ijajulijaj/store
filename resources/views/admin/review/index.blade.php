<!-- Add at top of index view -->
@if(empty($review))
    <div class="alert alert-info">
        No customer review found.
    </div>
@endif

@extends('layouts.app')
  
@section('contents')
  <div class="row">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <!-- Header -->
        <div class="cancel-header">
            <h2>Customer Review</h2>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Product Review</th>
                            <th>Product Rating</th>
                            <th>Outlet</th>
                            <th>Review Date</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>S/N</th>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Product Review</th>
                            <th>Product Rating</th>
                            <th>Outlet</th>
                            <th>Review Date</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($review as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order['order_id'] ?? '-' }}</td>
                            <td>{{ $order['user_name'] ?? '-' }}</td>
                            <td>{{ $order['prod_id'] ?? '-' }}</td>
                            <td>{{ $order['detail'] ?? '-' }}</td>
                            <td>{{ $order['review_message'] ?? '-' }}</td>
                            <td>{{ $order['rating'] ?? '-' }}</td>
                            <td>{{ $order['outlet_code'] ?? '-' }}</td>
                            <td>
                                {{ !empty($order['review_date']) 
                                    ? \Carbon\Carbon::parse($order['review_date'])->format('Y-m-d H:i:s') 
                                    : 'N/A' 
                                }}
                            </td>
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