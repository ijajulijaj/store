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
            <h2>Order Modify</h2>
        </div>
        <!-- order modification -->
        <div class="card shadow mb-4">
            <div class="card-header bg-success">
              <h5 class="section-title mb-0 text-white">Pending | Complete Order Modification</h5>
            </div>
            <div class="card-header py-3">
                <!-- acttion form -->
                <form method="POST" action="{{ route('admin.orders.modify.overrideOrder') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="order_id" class="form-label">Order ID</label>
                            <input type="text" name="order_id" placeholder="Enter order id"
                                id="order_id" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="new_status" class="form-label">Order Status (New)</label>
                            <select name="new_status" id="new_status" class="form-control" required>
                                <option value="">-- Select Status --</option>
                                <option value="1">Pending</option>
                                <option value="3">Complete</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Update Status</button>
                </form>
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header bg-warning">
              <h5 class="section-title mb-0 text-white">Cancel Bulk Order</h5>
            </div>
            <div class="card-header py-3">
                <!-- import file -->
                <form action="{{ route('admin.orders.modify.cancelOrder') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="file" class="custom-file-input" id="importFile" required accept=".xlsx,.xls,.csv">
                            <label class="custom-file-label" for="importFile">Choose file (XLSX, XLS, CSV)</label>
                        </div>
                        <div class="input-group-append ms-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i> Import
                            </button>
                        </div>
                    </div>
                    <small class="form-text text-muted mt-2">
                        Maximum file size: 5MB. Supported formats: .xlsx, .xls, .csv
                    </small>
                </form>
            </div>
        </div>
        
    </div>
  </div>

@endsection