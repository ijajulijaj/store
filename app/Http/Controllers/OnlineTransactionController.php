<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OnlineTransactionController extends Controller
{
    public function index()
    {
        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])->post(env('NODE_API_BASE_URL') . '/api/admin/online_transaction_list');

        if ($response->successful()) {
            $json = $response->json();
            $online_transaction = $json['data'] ?? [];
        } else {
            $online_transaction = [];
        }

        return view('admin.transaction.index', compact('online_transaction'));
    }

    // PendingOrdersController.php
    public function view($order_id)
    {
        try {
            $response = Http::post(env('NODE_API_BASE_URL') . '/api/admin/order_products', [
                'order_id' => $order_id
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === '1' && !empty($data['payload'])) {
                    $order = $data['payload'][0];
                    return view('admin.transaction.view', compact('order'));
                }
            }
            
            // If we reach here, there was an error
            return redirect()->route('admin.transaction.index')
                ->withErrors('Failed to load order details');
            
        } catch (\Exception $e) {
            return redirect()->route('admin.transaction.index')
                ->withErrors('Error: ' . $e->getMessage());
        }
    }

}
