@extends('layouts.app')
  
@section('contents')
<div class="row">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Header -->
        <div class="cancel-header mb-4">
            <h2>Create Banner</h2>
        </div>

        <form method="POST" enctype="multipart/form-data" action="{{ route('admin.images.banner.store') }}">
            @csrf
            <!-- Title & Image -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Banner Title</label>
                    <input type="text" name="title" placeholder="Enter banner title" id="title" 
                        class="form-control" value="{{ old('title') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="image" class="form-label">Banner Image</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image" name="image" accept="image/*" required>
                        <label class="custom-file-label" for="image">Choose file</label>
                    </div>
                </div>
            </div>

            <!-- Start Date & End Date -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}" required>
                </div>
            </div>

            <!-- Submit -->
            <div class="row">
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-success">Submit Banner</button>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection

@section('scripts') 
<script>
    // File input label handling
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("image").files[0].name;
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
