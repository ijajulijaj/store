@extends('layouts.app')

@section('contents')
<div class="row">
  <div class="container-fluid">
    @php
        // Use session order if available (after update), fallback to $order passed from controller
        $order = session('order') ?? ($order ?? null);
        $orderId = $order['order_id'] ?? null;
        
        // Get temporary products from session for this order
        $tempProducts = $orderId ? session('temp_products_' . $orderId, []) : [];
        
        // Get original order products
        $originalProducts = $order['products'] ?? [];
        
        // Create a map of temporary products by prod_id
        $tempProductsMap = [];
        foreach ($tempProducts as $tempProduct) {
            $tempProductsMap[$tempProduct['prod_id']] = $tempProduct;
        }
        
        // Merge products: replace original with temp versions
        $allProducts = [];
        foreach ($originalProducts as $product) {
            $prodId = $product['prod_id'];
            if (isset($tempProductsMap[$prodId])) {
                $allProducts[] = $tempProductsMap[$prodId];
                unset($tempProductsMap[$prodId]);
            } else {
                $allProducts[] = $product;
            }
        }
        
        // Add any remaining temporary products (newly added)
        foreach ($tempProductsMap as $tempProduct) {
            $allProducts[] = $tempProduct;
        }
        
        // Get deletion states from session
        $deleteStates = $orderId ? session('delete_states_' . $orderId, []) : [];
    @endphp

    @if(!isset($order) || !isset($order['order_id']))
      <div class="alert alert-danger">
        Order not found or failed to load data. 
        <a href="{{ route('admin.orders.pending.index') }}">Back to orders</a>
      </div>
    @else
      <!-- Success and error messages -->
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <!-- Invoice Header -->
      <div class="invoice-header">
        <h1>Modify Order</h1>
        <p>Order ID: <span class="highlight">#{{ $order['order_id'] }}</span></p>
      </div>
      
      <!-- Customer Information -->
      <div class="info-section">
        <h3 class="section-title">Customer Information</h3>
        <div class="info-grid">
          <div class="info-item">
            <span class="info-label">Name:</span> {{ $order['user_name'] ?? 'N/A' }}
          </div>
          <div class="info-item">
            <span class="info-label">Phone:</span> {{ $order['phone'] ?? 'N/A' }}
          </div>
          <div class="info-item">
            <span class="info-label">Delivery Location:</span>
            {{ $order['address'] ?? 'N/A' }}, {{ $order['city'] ?? 'N/A' }}, {{ $order['state'] ?? 'N/A' }} - {{ $order['postal_code'] ?? 'N/A' }}
          </div>
          <div class="info-item">
            <span class="info-label">Outlet Address:</span> {{ $order['delivery_location'] ?? 'N/A' }}
          </div>
        </div>
      </div>
      
      <!-- Product Search Section -->
      <div class="card mb-4">
        <div class="card-header bg-info ">
          <h5 class="section-title mb-0 text-white">Add Products</h5>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.orders.pending.searchProduct') }}">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order['order_id'] }}">
            <input type="hidden" name="parent_code" value="{{ $order['parent_code'] }}">
            <div class="input-group">
              <input type="text" class="form-control" name="keyword" 
                     value="{{ session('searchKeyword') }}" 
                     placeholder="Search products..." required>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Search
              </button>
            </div>
          </form>
          
          @if(session('searchResults'))
            <div class="mt-4">
              <h4>Search Results</h4>
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead class="table-light">
                    <tr>
                      <th>#</th>
                      <th>Product Name</th>
                      <th>Details</th>
                      <th>Price</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach(session('searchResults') as $index => $product)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product['product_name'] }}</td>
                        <td>{{ $product['product_detail'] ?? 'N/A' }}</td>
                        <td>{{ number_format($product['cart_price'], 2) }} BDT</td>
                        <td>
                          <button type="button" class="btn btn-sm btn-success add-to-order"
                                  data-prod-id="{{ $product['prod_id'] }}"
                                  data-product-name="{{ $product['product_name'] }}"
                                  data-product-detail="{{ $product['product_detail'] ?? '' }}"
                                  data-price="{{ $product['cart_price'] }}">
                            <i class="bi bi-plus-circle"></i> Add
                          </button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endif
        </div>
      </div>
      
      <!-- Order Products Section -->
      <div class="card">
        <div class="card-header bg-success ">
          <h5 class="section-title mb-0 text-white">Order Products</h5>
          <div class="info-item mb-3 mt-2 text-white">
            <span class="info-label text-white">Order Date:</span> {{ \Carbon\Carbon::parse($order['created_date'])->format('d M Y, h:i A') }}
          </div>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.orders.pending.manageProducts') }}" id="productsForm">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order['order_id'] }}">
            <input type="hidden" name="parent_code" value="{{ $order['parent_code'] }}">
            
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead class="table-light">
                  <tr>
                    <th>S/N</th>
                    <th>Prod ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="productsTableBody">
                  @foreach($allProducts as $index => $product)
                  @php
                    $isDeleted = isset($deleteStates[$product['prod_id']]);
                    $isTemp = $product['is_temp'] ?? false;
                  @endphp
                  <tr class="{{ $isTemp ? 'new-product' : '' }} {{ $isDeleted ? 'marked-for-delete' : '' }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product['prod_id'] }}</td>
                    <td>{{ $product['product_detail'] }}</td>
                    <td>
                      <input type="number" 
                             class="form-control qty-input"
                             value="{{ $isDeleted ? 0 : $product['qty'] }}" 
                             min="0" 
                             max="100" 
                             data-prod-id="{{ $product['prod_id'] }}"
                             data-original-qty="{{ $isTemp ? 0 : $product['qty'] }}"
                             {{ $isDeleted ? 'readonly' : '' }}>
                    </td>
                    <td>{{ number_format($product['cart_price'], 2) }} BDT</td>
                    <td class="total-price">{{ number_format($isDeleted ? 0 : ($product['qty'] * $product['cart_price']), 2) }} BDT</td>
                    <td>
                      <button type="button" class="btn btn-danger btn-sm delete-product" 
                              data-prod-id="{{ $product['prod_id'] }}"
                              {{ $isDeleted ? 'disabled' : '' }}>
                        <i class="bi bi-trash"></i> {{ $isDeleted ? 'Deleted' : 'Delete' }}
                      </button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            
            <!-- Hidden inputs for modified products -->
            <div id="modified-products-container"></div>
            
            <div class="invoice-actions mt-3">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Update Products
              </button>
            </div>
          </form>
        </div>
      </div>
      
      <!-- Order Summary Section -->
      <div class="card mt-4 mb-4">
        <div class="card-header bg-warning">
          <h5 class="section-title mb-0 text-white">Order Summary</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="info-item">
                <span class="info-label">Order Date:</span>
                {{ \Carbon\Carbon::parse($order['created_date'] ?? now())->format('d M Y, h:i A') }}
              </div>
              <div class="info-item">
                <span class="info-label">Payment Type:</span>
                {{ $order['payment_type'] ?? 'N/A' }}
              </div>
              <div class="info-item">
                <span class="info-label">Payment Status:</span>
                <span class="badge bg-{{ ($order['payment_status'] ?? '') == 'Paid' ? 'success' : 'warning' }}">
                  {{ $order['payment_status'] ?? 'Pending' }}
                </span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="totals-section">
                @if(!empty($order['promo_code_id']))
                <div class="total-row">
                  <span>Subtotal:</span>
                  <span>{{ number_format($order['total_price'] ?? 0, 2) }} BDT</span>
                </div>
                <div class="total-row">
                  <span>Discount ({{ $order['promo_code_id'] }}):</span>
                  <span>-{{ number_format($order['discount_price'] ?? 0, 2) }} BDT</span>
                </div>
                @endif
                <div class="total-row">
                  <span>Delivery Fee:</span>
                  <span>{{ number_format($order['deliver_price'] ?? 0, 2) }} BDT</span>
                </div>
                <div class="total-row">
                  <span>VAT:</span>
                  <span>{{ number_format($order['total_vat'] ?? 0, 2) }} BDT</span>
                </div>
                <div class="total-row total-amount">
                  <span>Payable Amount:</span>
                  <span>{{ number_format($order['user_pay_price'] ?? 0, 2) }} BDT</span>
                </div>
              </div>
            </div>
          </div>
          
          @if(!empty($order['cancel_decline_reason']))
          <div class="alert alert-warning mt-3">
            <strong>Cancellation Reason:</strong> {{ $order['cancel_decline_reason'] }}
          </div>
          @endif
        </div>

        <!-- Action Buttons -->
        <div class="invoice-actions mb-4 ml-3 mr-3 ">
          <form method="POST" action="{{ route('admin.orders.pending.saveChanges') }}">
              @csrf
              <input type="hidden" name="order_id" value="{{ $order['order_id'] }}">
              <a href="{{ route('admin.orders.pending.view', $order['order_id']) }}" class="btn btn-back">
                <i class="bi bi-arrow-left"></i> Back to Order
              </a>
              <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Update Order
              </button>
            </form>
        </div>
        
      </div>
    @endif
  </div>
</div>

<script src="{{ asset('public/admin_assets/js/order-modify.js') }}"></script>
@endsection