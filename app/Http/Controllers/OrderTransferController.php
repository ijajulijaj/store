<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderTransferController extends Controller
{
    public function showTransferForm($order_id)
    {
        try {
            // Debug session and token
        logger('Access Token: '. session('access_token'));
        logger('Order ID: '. $order_id);
        
        $accessToken = session('access_token');
        
        if (!$accessToken) {
            logger('No access token in session');
            throw new \Exception('Authentication required');
        }
            
            // Fetch transfer locations from API
            $response = Http::withHeaders([
                'access_token' => $accessToken
            ])->get(env('NODE_API_BASE_URL') . '/api/admin/transfer_locations');
            
            if (!$response->successful()) {
                throw new \Exception('Failed to fetch transfer locations');
            }
            
            $data = $response->json();
            
            if ($data['status'] !== '1') {
                throw new \Exception($data['message'] ?? 'Error fetching locations');
            }
            
            return view('admin.orders.transfer.orderTransfer', [
                'order_id' => $order_id,
                'locations' => $data['payload'] ?? []
            ]);
            
        } catch (\Exception $e) {
            Log::error('Transfer locations error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load transfer locations');
        }
    }

    public function transferOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'outlet_code' => 'required|string'
        ]);
        
        try {
            // Get access token from session
            $accessToken = session('access_token');
            
            // Call transfer API
            $response = Http::withHeaders([
                'access_token' => $accessToken
            ])->post(env('NODE_API_BASE_URL') . '/api/admin/transfer_order', [
                'order_id' => $request->order_id,
                'outlet_code' => $request->outlet_code
            ]);
            
            if (!$response->successful()) {
                throw new \Exception('Transfer API request failed');
            }
            
            $data = $response->json();
            
            if ($data['status'] !== '1') {
                throw new \Exception($data['message'] ?? 'Transfer failed');
            }
            
            return redirect()
                ->route('admin.orders.pending.index', $request->order_id)
                ->with('success', $data['message'] ?? 'Order transferred successfully');
            
        } catch (\Exception $e) {
            Log::error('Transfer order error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Order transfer failed: ' . $e->getMessage());
        }
    }
}