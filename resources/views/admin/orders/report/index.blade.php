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
            <h2>Orders Report</h2>
        </div>
        <!-- Filter Form -->
        <form action="{{ route('admin.report.download') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">From Date</label>
                    <input type="date" class="form-control" name="start_date" id="start_date" required>
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">To Date</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-file-excel"></i> Download Report
                    </button> 
                </div>
            </div>
        </form>

        <!-- Header -->
        <div class="cancel-header">
            <h2>Pending Orders Report</h2>
        </div>
        <!-- Filter Form -->
        <form action="{{ route('admin.export_pending_orders') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">From Date</label>
                    <input type="date" class="form-control" name="start_date" id="start_date" required>
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">To Date</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-file-excel"></i> Download Report
                    </button> 
                </div>
            </div>
        </form>

        <!-- Header -->
        <div class="cancel-header">
            <h2>Complete Orders Report</h2>
        </div>
        <!-- Filter Form -->
        <form action="{{ route('admin.export_complete_orders') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">From Date</label>
                    <input type="date" class="form-control" name="start_date" id="start_date" required>
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">To Date</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-file-excel"></i> Download Report
                    </button> 
                </div>
            </div>
        </form>

        <!-- Header -->
        <div class="cancel-header">
            <h2>Cancel Orders Report</h2>
        </div>
        <!-- Filter Form -->
        <form action="{{ route('admin.export_cancel_orders') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">From Date</label>
                    <input type="date" class="form-control" name="start_date" id="start_date" required>
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">To Date</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-file-excel"></i> Download Report
                    </button> 
                </div>
            </div>
        </form>
        
        @if(session('user_type') != 2)
        <!-- Header -->
        <div class="cancel-header">
            <h2>Online Transaction Report</h2>
        </div>
        <!-- Filter Form -->
        <form action="{{ route('admin.export_online_transaction') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">From Date</label>
                    <input type="date" class="form-control" name="start_date" id="start_date" required>
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">To Date</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-file-excel"></i> Download Report
                    </button> 
                </div>
            </div>
        </form>
        @endif
        
        @if(session('user_type') != 2)
        <!-- Header -->
        <div class="cancel-header">
            <h2>All Customer</h2>
        </div>
        <!-- Filter Form -->
        <form action="{{ route('admin.export_all_customers') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">From Date</label>
                    <input type="date" class="form-control" name="start_date" id="start_date" required>
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">To Date</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-file-excel"></i> Download Report
                    </button> 
                </div>
            </div>
        </form>
        @endif

    </div>
  </div>

@endsection