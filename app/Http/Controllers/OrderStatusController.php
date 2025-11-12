<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderStatusController extends Controller
{
    public function accept(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'order_id' => 'required|integer',
            'order_status' => 'required|in:3' // Only status 3 (accepted) is allowed
        ]);

        try {
            $apiUrl = env('NODE_API_BASE_URL') . '/api/admin/order_status_change';
            $token = $this->getAdminAccessToken();

            // Add null for cancel_decline_reason to match API expectations
            $postData = array_merge($validated, ['cancel_decline_reason' => null]);

            $response = Http::withHeaders([
                'access_token' => $token,
                'Accept' => 'application/json',
            ])->post($apiUrl, $postData);

            // Check if the request was successful
            if ($response->successful() && $response->json('status') === "1") {
                return redirect()->route('admin.orders.complete.index')
                                ->with('success', 'Order delivered successfully');
            }

            // Handle API errors
            $error = $response->json('message') ?? 'Unknown error from API';
            Log::error('Order acceptance failed', [
                'response' => $response->json(),
                'request' => $validated
            ]);

            return back()->with('error', 'Order acceptance failed: ' . $error);

        } catch (\Exception $e) {
            Log::error('Order acceptance exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function cancel(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'order_id' => 'required|integer',
            'cancel_decline_reason' => 'required|string|max:255',
            'order_status' => 'required|in:4,5'
        ]);

        try {
            // Get the Node.js API URL from your .env
            $apiUrl = (env('NODE_API_BASE_URL') . '/api/admin/order_status_change');
            
            // Get the admin access token (you need to implement this)
            $token = $this->getAdminAccessToken();
            
            // Make the API request
            $response = Http::withHeaders([
                'access_token' => $token,  // Changed from 'Authorization'
                'Accept' => 'application/json',
            ])->post($apiUrl, $validated);

            
            // Check if the request was successful
            if ($response->successful() && $response->json('status') === "1") {
                return redirect()->route('admin.orders.pending.index')->with('success', 'Order canceled successfully');
            }
            
            // Handle API errors
            $error = $response->json('message') ?? 'Unknown error from API';
            Log::error('Order cancellation failed', [
                'response' => $response->json(),
                'request' => $validated
            ]);
            
            return back()->with('error', 'Order cancellation failed: ' . $error);
            
        } catch (\Exception $e) {
            Log::error('Order cancellation exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    /**
     * Get the admin access token from session or database
     * You need to implement this based on your authentication
     */
    // OrderStatusController.php
    private function getAdminAccessToken()
    {
        // Use the same token as in PendingOrdersController
        if (session()->has('access_token')) {
            return session('access_token');
        }
        
        throw new \Exception('Admin access token not found');
    }
}