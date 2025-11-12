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
            <h2>Create Announcement</h2>
        </div>

        <form method="POST" enctype="multipart/form-data" action="{{ route('admin.announcement.store') }}">
            @csrf
            <!-- Type & Title -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="type" class="form-label">Announcement Type</label>
                    <input type="text" name="type" placeholder="Enter announcement type" id="type" class="form-control" value="{{ old('type') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="title" class="form-label">Announcement Title</label>
                    <input type="text" name="title" placeholder="Enter announcement title" id="title" class="form-control" value="{{ old('title') }}" required>
                </div>
            </div>
            <!-- Description -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="description" class="form-label">Announcement Description</label>
                    <textarea name="description" placeholder="Enter announcement description" id="description" rows="4" class="form-control" required>{{ old('description') }}</textarea>
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
                    <button type="submit" class="btn btn-success">Submit Announcement</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection