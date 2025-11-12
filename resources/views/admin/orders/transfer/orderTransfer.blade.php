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

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <!-- Invoice Header -->
        <div class="invoice-header">
            <h1>Order Transfer</h1>
            <p class="transfer-subtitle">Order ID #{{ $order_id }}</p>
        </div>
        <!-- Transfer Form Card -->
        <div class="order-card">
            <h3 class="section-title">Would you like to confirm the transfer of this order?</h3>
            <form method="POST" action="{{ route('admin.orders.transfer.orderTransfer.submit') }}" class="transfer-form">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order_id }}"> 
                <div class="mb-3">
                    <label for="outlet_code" class="form-label d-block mb-2">Select Outlet Location</label>
                    <select class="form-select w-100" name="outlet_code" id="outlet_code" required>
                        <option value="" disabled selected>Select a location</option>
                        @foreach($locations as $location)
                        <option value="{{ $location['outlet_code'] }}">
                            {{ $location['outlet_location'] }} ({{ $location['outlet_code'] }})
                        </option>
                        @endforeach
                    </select>
                    @error('outlet_code')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="transfer-actions">
                    <a href="{{ route('admin.orders.pending.view', $order_id) }}" class="btn btn-cancel">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-accept">
                        <i class="bi bi-check-circle"></i> Confirm Transfer
                    </button>
                </div>
            </form>
        </div>

        <!-- Information Note -->
        <div class="alert alert-info">
            <h5><i class="bi bi-info-circle"></i> About Order Transfer</h5>
            <p class="mb-0">
                Transferring an order will assign it to a new outlet location. The delivery address remains the same, 
                but the order will be fulfilled from the selected outlet. This action cannot be undone.
            </p>
        </div>
    </div>
</div>
@endsection