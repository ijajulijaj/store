<!-- Add at top of index view -->
@if(empty($customers))
    <div class="alert alert-info">
        No customer found.
    </div>
@endif

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
            <h2>Registered Customers</h2>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Customer Name</th>
                            <th>Gender</th>
                            <th>Customer Mobile</th>
                            <th>Customer Address</th>
                            <th>Status</th>
                            <th>Registration Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>S/N</th>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Customer Name</th>
                            <th>Gender</th>
                            <th>Customer Mobile</th>
                            <th>Customer Address</th>
                            <th>Status</th>
                            <th>Registration Date</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($customers as $index => $customer)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $customer['user_id'] ?? '-' }}</td>
                            <td>{{ $customer['username'] ?? '-' }}</td>
                            <td>{{ $customer['name'] ?? '-' }}</td>
                            <td>{{ $customer['gender'] ?? '-' }}</td>
                            <td>{{ $customer['mobile'] ?? '-' }}</td>
                            <td>{{ $customer['address'] ?? '-' }}</td>
                            <td class="text-center">
                            @if(isset($customer['status']))
                                @if($customer['status'] === 'Active')
                                <span class="tag-on-time">Active</span>
                                @elseif(strpos($customer['status'], 'Inactive') !== false)
                                <span class="tag-late">{{ $customer['status'] }}</span>
                                @else
                                <span class="tag-default">{{ $customer['status'] }}</span>
                                @endif
                            @else
                                <span class="tag-default">-</span>
                            @endif
                            </td>
                            <td>
                                {{ !empty($customer['created_date']) 
                                    ? \Carbon\Carbon::parse($customer['created_date'])->format('Y-m-d H:i:s') 
                                    : 'N/A' 
                                }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.customers.edit-password', ['user_id' => $customer['user_id']]) }}" 
                                class="btn-view" title="Update">
                                    <i class="bi bi-pencil-square me-1"></i> Update
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