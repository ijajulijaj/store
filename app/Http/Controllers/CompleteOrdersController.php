<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CompleteOrdersController extends Controller
{
    public function index()
    {
        $response = Http::post(env('NODE_API_BASE_URL') . '/api/admin/completed_orders_list', []);

        // Check for errors
        if ($response->successful()) {
            $json = $response->json();
            $complete_orders = $json['payload'] ?? []; // ✅ get only the actual list
        } else {
            $complete_orders = [];
        }

        return view('admin.orders.complete.index', compact('complete_orders'));
    }

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
                    return view('admin.orders.complete.view', compact('order'));
                }
            }
            
            // If we reach here, there was an error
            return redirect()->route('admin.orders.complete.index')
                ->withErrors('Failed to load order details');
            
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.complete.index')
                ->withErrors('Error: ' . $e->getMessage());
        }
    }
}
