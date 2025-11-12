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
            <h2>All Announcement</h2>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Type</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Start date</th>
                            <th>End date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>S/N</th>
                            <th>Type</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Start date</th>
                            <th>End date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($announcements as $index => $announcement)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $announcement['type'] ?? '-' }}</td>
                            <td>{{ $announcement['title'] ?? '-' }}</td>
                            <td>{{ $announcement['description'] ?? '-' }}</td>
                            <td>
                                {{ !empty($announcement['start_date']) 
                                    ? \Carbon\Carbon::parse($announcement['start_date'])->format('Y-m-d H:i:s') 
                                    : 'N/A' 
                                }}
                            </td>
                            <td>
                                {{ !empty($announcement['end_date']) 
                                    ? \Carbon\Carbon::parse($announcement['end_date'])->format('Y-m-d H:i:s') 
                                    : 'N/A' 
                                }}
                            </td>
                            <td class="text-center">
                                @if(isset($announcement['status']))
                                    @if($announcement['status'] === 'Active')
                                    <span class="tag-on-time">Active</span>
                                    @elseif(strpos($announcement['status'], 'Inactive') !== false)
                                    <span class="tag-late">{{ $announcement['status'] }}</span>
                                    @else
                                    <span class="tag-default">{{ $announcement['status'] }}</span>
                                    @endif
                                @else
                                    <span class="tag-default">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.announcement.edit', $announcement['id']) }}" class="btn btn-modify" title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
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
