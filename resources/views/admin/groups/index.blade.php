@extends('layouts.app')

@section('contents')
<div class="row">
    <div class="container-fluid">

        {{-- Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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

        <div class="outlet-header">
            <h2>Category & Sub Category List</h2>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Category ID</th>
                                <th>Category Name</th>
                                <th>Sub Category ID</th>
                                <th>Sub Category Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($combined as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item['cat_id'] ?? '-' }}</td>
                                <td>{{ $item['cat_name'] ?? '-' }}</td>
                                <td>{{ $item['sub_cat_id'] ?? '-' }}</td>
                                <td>{{ $item['sub_cat_name'] ?? '-' }}</td>
                                <td class="text-center">
                                    @if(isset($item['status']))
                                        @if($item['status'] === 'Active')
                                            <span class="tag-on-time">Active</span>
                                        @elseif(strpos($item['status'], 'Inactive') !== false)
                                            <span class="tag-late">{{ $item['status'] }}</span>
                                        @else
                                            <span class="tag-default">{{ $item['status'] }}</span>
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