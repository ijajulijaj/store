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

        @if(!isset($order))
            <div class="alert alert-danger">
                Order not found or failed to load data. 
                <a href="{{ route('admin.orders.pending.index') }}">Back to orders</a>
            </div>
        @else
            <!-- Invoice Header -->
            <div class="invoice-header position-relative mb-4 text-center">
                <h1>Order Invoice</h1>
                <p>Order ID: <span class="highlight">#{{ $order['order_id'] }}</span></p>
            
                <img src="{{ asset('public/admin_assets/img/logo.png') }}" alt="Logo" style="height:40px; position:absolute; top:0; right:0;">
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
                        <span>Delivery Type:</span>
                        <span>{{ $order['deliver_type'] }} </span>
                    </div>
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
                <a href="{{ route('admin.orders.pending.index') }}" class="btn btn-back">
                    <i class="bi bi-arrow-left"></i> Back to Orders
                </a>
                <a href="#" class="btn btn-print" onclick="window.print()">
                    <i class="bi bi-printer"></i> Print Invoice
                </a>
                {{-- Replace the existing modify button with this --}}
                <a href="{{ route('admin.orders.pending.modify', $order['order_id']) }}" class="btn btn-modify">
                    <i class="bi bi-pencil"></i> Modify Invoice
                </a>
                <!-- Transfer Button: Only show for user_type = 1 -->
                 @if(session('user_type') == 1)
                    <a href="{{ route('admin.orders.transfer.orderTransfer', $order['order_id']) }}" class="btn btn-transfer">
                        <i class="bi bi-arrow-left-right"></i> Transfer Order
                    </a>
                @endif
                <a href="#" class="btn btn-accept" data-bs-toggle="modal" data-bs-target="#acceptOrderModal">
                    <i class="bi bi-check-circle"></i> Accept Order
                </a>
                <!-- Cancel Button: Only show for user_type = 1 -->
                @if(session('user_type') == 1)
                    <button type="button" class="btn btn-cancel" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                        <i class="bi bi-x-circle"></i> Cancel Order
                    </button>
                @endif
            </div>
            
            <!-- Footer -->
            <div class="invoice-footer">
                <p>
                    Thank you for your business! For any questions or concerns regarding this order, please contact our customer service & call 09612311211 (9 am to 5 pm).
                    Thank you for shopping with <strong>Agora Superstores</strong>.
                </p>
            </div>

            <!-- Accept Order Modal -->
            <div class="modal fade" id="acceptOrderModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <h5 class="modal-title text-white">Order Acceptance #{{ $order['order_id'] }}</h5>
                        </div>
                        <form method="POST" action="{{ route('admin.orders.accept.submit') }}">
                            @csrf
                            <div class="modal-body">
                                <p>Are you sure you want to delivered this order?</p>
                                <input type="hidden" name="order_id" value="{{ $order['order_id'] }}">
                                <input type="hidden" name="order_status" value="3"> <!-- Status 3 = Delivered -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Confirm Acceptance</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Cancel Order Modal -->
            <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title text-white">Cancel Order #{{ $order['order_id'] }}</h5>
                        </div>
                        <form method="POST" action="{{ route('admin.orders.cancel.submit') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="cancelReason" class="form-label">Reason for Cancellation <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="cancelReason" name="cancel_decline_reason" rows="3" required placeholder="Please specify the cancellation reason"></textarea>
                                </div>
                                <input type="hidden" name="order_id" value="{{ $order['order_id'] }}">
                                <input type="hidden" name="order_status" value="5"> <!-- Status 5 = Canceled -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
  </div>

@endsection