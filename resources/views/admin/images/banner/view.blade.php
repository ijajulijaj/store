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
        <div class="outlet-header">
            <h2>Banner</h2>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Start Date</th>
                            <th>End date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>S/N</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Start Date</th>
                            <th>End date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($sliders as $index => $slider)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $slider['title'] ?? '-' }}</td>
                            <td class="text-center">
                                @if(!empty($slider['image']))
                                    <img src="{{ $slider['image'] }}" alt="{{ $slider['title'] }}" width="120" height="60">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                {{ !empty($slider['start_date']) 
                                    ? \Carbon\Carbon::parse($slider['start_date'])->format('Y-m-d H:i:s') 
                                    : 'N/A' 
                                }}
                            </td>
                            <td>
                                {{ !empty($slider['end_date']) 
                                    ? \Carbon\Carbon::parse($slider['end_date'])->format('Y-m-d H:i:s') 
                                    : 'N/A' 
                                }}
                            </td>
                            <td class="text-center">
                                @if(isset($slider['status']))
                                    @if($slider['status'] === 'Active')
                                    <span class="tag-on-time">Active</span>
                                    @elseif(strpos($slider['status'], 'Inactive') !== false)
                                    <span class="tag-late">{{ $slider['status'] }}</span>
                                    @else
                                    <span class="tag-default">{{ $slider['status'] }}</span>
                                    @endif
                                @else
                                    <span class="tag-default">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.images.banner.delete', $slider['slider_id']) }}" 
                                    method="POST" 
                                    style="display:inline-block;" 
                                    onsubmit="return confirm('Are you sure you want to delete this banner?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
                                </form>
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