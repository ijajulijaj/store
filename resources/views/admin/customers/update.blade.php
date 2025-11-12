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

        @error('password')
            <div class="alert alert-danger">
                {{ $message }}
            </div>
        @enderror
       <!-- Header -->
        <div class="cancel-header mb-4">
            <h2>Update User Password</h2>
        </div>
        <!-- Transfer Form Card -->
        <div class="order-card">
            <h3 class="section-title">Would you like to update the user password?</h3>
            <form method="POST" enctype="multipart/form-data" action="{{ route('admin.customers.update-password', ['user_id' => $user_id ?? request()->user_id]) }}">
                @csrf
                    <!-- New & Confirm Password -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="type" class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Please Enter New Password">
                    </div>
                    <div class="col-md-6">
                        <label for="title" class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Please Enter Confirm Password">
                    </div>
                </div>
                    <!-- Submit -->
                <div class="row">
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-success">Update Password</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Information Note -->
        <div class="alert alert-info">
            <h5><i class="bi bi-info-circle"></i> About Password Update</h5>
            <p class="mb-0">
                When an admin updates a user's password, the user will need to use the new password to log in. 
                This action overrides the previous password and cannot be undone. 
                It’s recommended to inform the user immediately after the change.
            </p>
        </div>
    </div>
</div>
@endsection