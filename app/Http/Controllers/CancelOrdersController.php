<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CancelOrdersController extends Controller
{
    public function index()
    {
        $response = Http::post(env('NODE_API_BASE_URL') . '/api/admin/cancel_decline_orders_list', []);

        // Check for errors
        if ($response->successful()) {
            $json = $response->json();
            $cancel_orders = $json['payload'] ?? []; // ✅ get only the actual list
        } else {
            $cancel_orders = [];
        }

        return view('admin.orders.cancel.index', compact('cancel_orders'));
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
                    return view('admin.orders.cancel.view', compact('order'));
                }
            }
            
            // If we reach here, there was an error
            return redirect()->route('admin.orders.cancel.index')
                ->withErrors('Failed to load order details');
            
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.cancel.index')
                ->withErrors('Error: ' . $e->getMessage());
        }
    }

}
