@extends('layouts.app')

@section('contents')
<div class="row">
  <div class="container-fluid">

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
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

    <div class="cancel-header mb-4">
      <h2>Update Announcement</h2>
    </div>

    <form method="POST" action="{{ route('admin.announcement.update', $announcement['id']) }}">
      @csrf
      @method('PUT')

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="type" class="form-label">Announcement Type</label>
          <input type="text" name="type" class="form-control" value="{{ $announcement['type'] }}" required>
        </div>
        <div class="col-md-6">
          <label for="title" class="form-label">Announcement Title</label>
          <input type="text" name="title" class="form-control" value="{{ $announcement['title'] }}" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-12">
          <label for="description" class="form-label">Announcement Description</label>
          <textarea name="description" rows="4" class="form-control" required>{{ $announcement['description'] }}</textarea>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="start_date" class="form-label">Start Date</label>
          <input type="datetime-local" name="start_date" class="form-control"
                 value="{{ \Carbon\Carbon::parse($announcement['start_date'])->format('Y-m-d\TH:i') }}" required>
        </div>
        <div class="col-md-6">
          <label for="end_date" class="form-label">End Date</label>
          <input type="datetime-local" name="end_date" class="form-control"
                 value="{{ \Carbon\Carbon::parse($announcement['end_date'])->format('Y-m-d\TH:i') }}" required>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 text-end">
          <button type="submit" class="btn btn-success">Update Announcement</button>
        </div>
      </div>
    </form>

  </div>
</div>
@endsection
