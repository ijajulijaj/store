<!-- Add at top of index view -->
@if(empty($pending_orders))
    <div class="alert alert-info">
        No pending orders found.
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
        <div class="pending-header">
            <h2>Pending Orders</h2>
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
                            <th>Phone</th>
                            <th>Delivery Address</th>
                            <th>Total Price</th>
                            <th>Payment Type</th>
                            <th>Delivery Type</th>
                            <th>Outlet</th>
                            <th>Order Date</th>
                            <th>Delivery Status</th>
                            <th>Platform</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>S/N</th>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Phone</th>
                            <th>Delivery Address</th>
                            <th>Total Price</th>
                            <th>Payment Type</th>
                            <th>Delivery Type</th>
                            <th>Outlet</th>
                            <th>Order Date</th>
                            <th>Delivery Status</th>
                            <th>Platform</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($pending_orders as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order['order_id'] ?? '-' }}</td>
                            <td>{{ $order['user_name'] ?? '-' }}</td>
                            <td>{{ $order['phone'] ?? '-' }}</td>
                            <td>{{ $order['address'] ?? '-' }}</td>
                            <td>{{ $order['user_pay_price'] ?? '-' }}</td>
                            <td>{{ $order['payment_type'] ?? '-' }}</td>
                            <td>{{ $order['deliver_type'] ?? '-' }}</td>
                            <td>{{ $order['outlet_code'] ?? '-' }}</td>
                            <td>
                                {{ !empty($order['created_date']) 
                                    ? \Carbon\Carbon::parse($order['created_date'])->format('Y-m-d H:i:s') 
                                    : 'N/A' 
                                }}
                            </td>
                            <td class="text-center">
                            @if(isset($order['delivery_status']))
                                @if($order['delivery_status'] === 'On Time')
                                <span class="tag-on-time">On Time</span>
                                @elseif(strpos($order['delivery_status'], 'Late') !== false)
                                <span class="tag-late">{{ $order['delivery_status'] }}</span>
                                @else
                                <span class="tag-default">{{ $order['delivery_status'] }}</span>
                                @endif
                            @else
                                <span class="tag-default">-</span>
                            @endif
                            </td>
                            <td>{{ $order['platform'] ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.pending.view', ['order_id' => $order['order_id']]) }}" 
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