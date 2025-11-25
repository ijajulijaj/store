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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Header -->
        <div class="outlet-header">
            <h2>Product Images</h2>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <!-- import file -->
                <form action="{{ route('admin.images.product_images.import') }}" method="POST" enctype="multipart/form-data">
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
                            <th>Product Name</th>
                            <th>Image</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>S/N</th>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Image</th>
                            <th>Status</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($product_images as $index => $product_image)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product_image['prod_id'] ?? '-' }}</td>
                            <td>{{ $product_image['product_detail'] ?? '-' }}</td>
                            <td class="text-center">
                                @if(!empty($product_image['image']))
                                    <img src="{{ $product_image['image'] }}" alt="image" width="80" height="60">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="text-center">
                            @if(isset($product_image['status']))
                                @if($product_image['status'] === 'Active')
                                <span class="tag-on-time">Active</span>
                                @elseif(strpos($product_image['status'], 'Inactive') !== false)
                                <span class="tag-late">{{ $product_image['status'] }}</span>
                                @else
                                <span class="tag-default">{{ $product_image['status'] }}</span>
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