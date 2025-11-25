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
        <div class="justify-content-between align-items-center mb-4">
            <div class="outlet-header">
                <h2 class="mb-0">All Products</h2>
            </div>
            <form action="{{ route('admin.export_all_products') }}" method="GET">
                <div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Download All Product
                    </button> 
                </div>
            </form>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <!-- import file -->
                <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
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
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Product ID</th>
                            <th>Category Name</th>
                            <th>Sub Category Name</th>
                            <th>Product Name</th>
                            <th>Product Details</th>
                            <th>Max Quantity</th>
                            <th>Status</th>
                            <th>Last Update</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>S/N</th>
                            <th>Product ID</th>
                            <th>Category Name</th>
                            <th>Sub Category Name</th>
                            <th>Product Name</th>
                            <th>Product Details</th>
                            <th>Max Quantity</th>
                            <th>Status</th>
                            <th>Last Update</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product['prod_id'] ?? '-' }}</td>
                            <td>{{ $product['cat_name'] ?? '-' }}</td>
                            <td>{{ $product['sub_cat_name'] ?? '-' }}</td>
                            <td>{{ $product['name'] ?? '-' }}</td>
                            <td>{{ $product['detail'] ?? '-' }}</td>
                            <td>{{ $product['max_order_qty'] ?? '-' }}</td>
                            <td class="text-center">
                            @if(isset($product['status_text']))
                                @if($product['status_text'] === 'Active')
                                <span class="tag-on-time">Active</span>
                                @elseif(strpos($product['status_text'], 'Inactive') !== false)
                                <span class="tag-late">{{ $product['status_text'] }}</span>
                                @else
                                <span class="tag-default">{{ $product['status_text'] }}</span>
                                @endif
                            @else
                                <span class="tag-default">-</span>
                            @endif
                            </td>
                            <td>
                                {{ !empty($product['modify_date']) 
                                    ? \Carbon\Carbon::parse($product['modify_date'])->format('Y-m-d H:i:s') 
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

@section('scripts') 
<script src="{{ asset('public/admin_assets/js/dashboard.js') }}"></script> 
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