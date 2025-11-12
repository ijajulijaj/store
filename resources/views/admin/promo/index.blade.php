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
            <h2>Promo Offers</h2>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <!-- import file -->
                <form action="{{ route('admin.promos.import') }}" method="POST" enctype="multipart/form-data">
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
            <div class="card-body">
                <div class="mb-4">
                    <div class="row text-center">
                        <div class="col-md-4 col-sm-12">
                            <div class="p-2 border rounded bg-light">
                                <strong>Total Promo Code:</strong> {{ $total_count }}
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="p-2 border rounded bg-light">
                                <strong>Active Promo Code:</strong> {{ $active_count }}
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="p-2 border rounded bg-light">
                                <strong>Inactive Promo Code:</strong> {{ $inactive_count }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Promo Code ID</th>
                            <th>Code</th>
                            <th>title</th>
                            <th>Description</th>
                            <th>Min Order Amount</th>
                            <th>Max Discount Amount</th>
                            <th>Offer Price</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>S/N</th>
                            <th>Promo Code ID</th>
                            <th>Code</th>
                            <th>title</th>
                            <th>Description</th>
                            <th>Min Order Amount</th>
                            <th>Max Discount Amount</th>
                            <th>Offer Price</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($promo_codes as $index => $promo)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $promo['promo_code_id'] ?? '-' }}</td>
                            <td>{{ $promo['code'] ?? '-' }}</td>
                            <td>{{ $promo['title'] ?? '-' }}</td>
                            <td>{{ $promo['description'] ?? '-' }}</td>
                            <td>{{ $promo['min_order_amount'] ?? '-' }}</td>
                            <td>{{ $promo['max_discount_amount'] ?? '-' }}</td>
                            <td>{{ $promo['offer_price'] ?? '-' }}</td>
                            <td>
                                {{ !empty($promo['start_date']) 
                                    ? \Carbon\Carbon::parse($promo['start_date'])->format('Y-m-d H:i:s') 
                                    : 'N/A' 
                                }}
                            </td>
                            <td>
                                {{ !empty($promo['end_date']) 
                                    ? \Carbon\Carbon::parse($promo['end_date'])->format('Y-m-d H:i:s') 
                                    : 'N/A' 
                                }}
                            </td>
                            <td class="text-center">
                            @if(isset($promo['status']))
                                @if($promo['status'] === 'Active')
                                <span class="tag-on-time">Active</span>
                                @elseif(strpos($promo['status'], 'Inactive') !== false)
                                <span class="tag-late">{{ $promo['status'] }}</span>
                                @else
                                <span class="tag-default">{{ $promo['status'] }}</span>
                                @endif
                            @else
                                <span class="tag-default">-</span>
                            @endif
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

@section('scripts') 
<script src="{{ asset('admin_assets/js/dashboard.js') }}"></script> 
<script>
    // File input label handling
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("importFile").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });

    // DataTable initialization (example)
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Table would be initialized here with DataTables if available');
            
            // Example of additional functionality
            const alertButtons = document.querySelectorAll('.alert .btn-close');
            alertButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.alert').style.opacity = 0;
                    setTimeout(() => {
                        this.closest('.alert').remove();
                    }, 300);
                });
            });
        });
</script>
@endsection