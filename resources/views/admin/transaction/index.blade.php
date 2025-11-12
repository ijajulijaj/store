<!-- Add at top of index view -->
@if(empty($online_transaction))
    <div class="alert alert-info">
        No online transaction found.
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
            <h2>Online Transaction</h2>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Transaction ID</th>
                            <th>Order ID</th>
                            <th>User Name</th>
                            <th>Outlet Code</th>
                            <th>Pay Price</th>
                            <th>Customer Transaction</th>
                            <th>Status</th>
                            <th>Order Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>S/N</th>
                            <th>Transaction ID</th>
                            <th>Order ID</th>
                            <th>User Name</th>
                            <th>Outlet Code</th>
                            <th>Pay Price</th>
                            <th>Customer Transaction</th>
                            <th>Status</th>
                            <th>Order Date</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($online_transaction as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order['payment_transaction_id'] ?? '-' }}</td>
                            <td>{{ $order['order_id'] ?? '-' }}</td>
                            <td>{{ $order['name'] ?? '-' }}</td>
                            <td>{{ $order['outlet_code'] ?? '-' }}</td>
                            <td>{{ $order['user_pay_price'] ?? '-' }}</td>
                            <td>{{ $order['customer_transaction'] ?? '-' }}</td>
                            <td>{{ $order['status'] ?? '-' }}</td>
                            <td>
                                {{ !empty($order['created_date']) 
                                    ? \Carbon\Carbon::parse($order['created_date'])->format('Y-m-d H:i:s') 
                                    : 'N/A' 
                                }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.transaction.view', ['order_id' => $order['order_id']]) }}" 
                                class="btn-view" title="View">
                                    <i class="bi bi-eye me-1"></i> View
                                </a>
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
