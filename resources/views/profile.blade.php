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

            <h3 class="mb-0 ml-2">Profile</h3>
            <hr />
            <form method="POST" enctype="multipart/form-data" id="profile_setup_frm" action="{{ route('profile.update') }}">
            @csrf
            <div class="row">
                <div class="col-md-12 border-right">
                    <div class="p-3 py-4">
                        <div class="row" id="res"></div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="labels">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Username" value="{{ auth()->user()->username }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="labels">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ auth()->user()->name }}" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="labels">New Password</label>
                                <input type="password" name="password" class="form-control" placeholder="New Password">
                            </div>
                            <div class="col-md-6">
                                <label class="labels">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
                            </div>
                        </div>
                        
                        <div class="mt-5 text-center d-flex justify-content-end"><button id="btn" class="btn btn-primary profile-button" type="submit">Update Profile</button></div>
                    </div>
                </div>
                
            </div>   
            </form>
        </div>
    </div>
@endsection