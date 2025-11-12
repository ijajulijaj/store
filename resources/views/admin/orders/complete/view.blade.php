@extends('layouts.app')
  
@section('contents')
  <div class="row">
    <div class="container-fluid">
        @if(!isset($order))
            <div class="alert alert-danger">
                Order not found or failed to load data. 
                <a href="{{ route('admin.orders.complete.index') }}">Back to orders</a>
            </div>
        @else
            <!-- Invoice Header -->
            <div class="invoice-header position-relative mb-4 text-center">
                <h1>Order Invoice</h1>
                <p>Order ID: <span class="highlight">#{{ $order['order_id'] }}</span></p>
            
                <img src="{{ asset('admin_assets/img/logo.png') }}" alt="Logo" style="height:40px; position:absolute; top:0; right:0;">
            </div>
            
            <!-- Customer Information -->
            <div class="info-section">
                <h3 class="section-title">Customer Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Name:</span> {{ $order['user_name'] }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone:</span> {{ $order['phone'] }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Delivery Location:</span>
                        {{ $order['address'] }}, {{ $order['city'] }}, {{ $order['state'] }} - {{ $order['postal_code'] }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Outlet Address:</span> {{ $order['delivery_location'] ?? 'N/A' }}
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="order-summary">
                <h3 class="section-title">Order Summary</h3>
                <div class="info-item mb-3">
                    <span class="info-label">Order Date:</span>
                    {{ \Carbon\Carbon::parse($order['created_date'])->format('d M Y, h:i A') }}
                </div>
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Prod ID</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order['products'] as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product['prod_id'] }}</td>
                            <td>{{ $product['product_detail'] }}</td>
                            <td>{{ $product['qty'] }}</td>
                            <td>{{ number_format($product['cart_price'], 2) }} <span class="currency">BDT</span></td>
                            <td>{{ number_format($product['total_product_price'], 2) }} <span class="currency">BDT</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Order Totals -->
                <div class="totals-section">
                    @if($order['promo_code_id'])
                    <div class="total-row total-amount">
                        <span>Total Amount:</span>
                        <span>{{ number_format($order['total_price'], 2) }} <span class="currency">BDT</span></span>
                    </div>
                    <div class="total-row">
                        <span>Discount ({{ $order['promo_code_id'] }}):</span>
                        <span>-{{ number_format($order['discount_price'], 2) }} <span class="currency">BDT</span></span>
                    </div>
                    @endif
                    <div class="total-row">
                        <span>Delivery Fee:</span>
                        <span>{{ number_format($order['deliver_price'], 2) }} <span class="currency">BDT</span></span>
                    </div>
                    <div class="total-row">
                        <span>VAT:</span>
                        <span>{{ number_format($order['total_vat'], 2) }} <span class="currency">BDT</span></span>
                    </div>
                    <div class="total-row total-amount">
                        <span>Payable Amount:</span>
                        <span>{{ number_format($order['user_pay_price'], 2) }} <span class="currency">BDT</span></span>
                    </div>
                    <div class="total-row">
                        <span>Payment Type:</span>
                        <span>{{ $order['payment_type'] }}</span>
                    </div>
                    <div class="total-row">
                        <span>Payment Status:</span>
                        <span class="payment-status status-{{ strtolower($order['payment_status']) }}">
                            {{ $order['payment_status'] }}
                        </span>
                    </div>
                    @if($order['cancel_decline_reason'])
                    <div class="total-row">
                        <span>Cancellation Reason:</span>
                        <span>{{ $order['cancel_decline_reason'] }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="invoice-actions">
                <a href="{{ route('admin.orders.complete.index') }}" class="btn btn-back">
                    <i class="bi bi-arrow-left"></i> Back to Orders
                </a>
                <a href="#" class="btn btn-print" onclick="window.print()">
                    <i class="bi bi-printer"></i> Print Invoice
                </a>
            </div>
            
            <!-- Footer -->
            <div class="invoice-footer">
                <p>
                    Thank you for your business! For any questions or concerns regarding this order, please contact our customer service & call 09612311211 (9 am to 5 pm).
                    Thank you for shopping with <strong>Agora Superstores</strong>.
                </p>
            </div>
        @endif
    </div>
  </div>

@endsection