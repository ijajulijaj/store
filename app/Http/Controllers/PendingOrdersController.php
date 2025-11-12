<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PendingOrdersController extends Controller
{
    public function index()
    {
        // Get outlet code from session
        $outlet_code = session('outlet_code');
        $user_type = session('user_type');

        $requestData = [];
        
        // Add outlet code only for outlet incharges (user_type=2)
        if($user_type == 2) {
            $requestData['outlet_code'] = $outlet_code;
            $requestData['user_type'] = $user_type;
        }

        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])->post(env('NODE_API_BASE_URL') . '/api/admin/new_orders_list', $requestData);
        
        $pending_orders = [];
        
        if ($response->successful()) {
            $json = $response->json();
            $pending_orders = $json['payload'] ?? [];
        }

        return view('admin.orders.pending.index', compact('pending_orders'));
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
                    return view('admin.orders.pending.view', compact('order'));
                }
            }
            
            return redirect()->route('admin.orders.pending.index')
                ->withErrors('Failed to load order details');
            
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.pending.index')
                ->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function modify($order_id)
    {
        try {
            $response = Http::post(env('NODE_API_BASE_URL') . '/api/admin/order_products', [
                'order_id' => $order_id
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] === '1' && !empty($data['payload'])) {
                    $order = $data['payload'][0];
                    
                    // Store original order in session for later reference
                    session(['original_order_' . $order_id => $order]);
                    
                    // Get temporary products and deletion states from session
                    $tempProducts = session('temp_products_' . $order_id, []);
                    $deleteStates = session('delete_states_' . $order_id, []);
                    
                    return view('admin.orders.pending.modify', compact('order', 'tempProducts', 'deleteStates'));
                }
            }
            
            return redirect()->route('admin.orders.pending.index')
                ->with('error', 'Failed to load order for modification');
            
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.pending.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function searchProduct(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'keyword' => 'required',
            'parent_code' => 'required'
        ]);

        $response = Http::post(env('NODE_API_BASE_URL') . '/api/admin/order_products/manage', [
            'keyword' => $request->keyword,
            'parent_code' => $request->parent_code
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return back()
                ->with('searchResults', $data['payload'] ?? [])
                ->with('searchKeyword', $request->keyword);
        }

        return back()->with('error', 'Product search failed');
    }

    public function manageProducts(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'parent_code' => 'required',
            'modified_products' => 'array'
        ]);

        $orderId = $request->order_id;
        $tempProductsKey = 'temp_products_' . $orderId;
        $existingTempProducts = session($tempProductsKey, []);
        
        $apiProducts = [];
        $deletedProducts = [];
        $updatedTempProducts = [];
        $newTempProducts = [];

        // Get original order from session
        $originalOrder = session('original_order_' . $orderId, []);

        // Process modified products
        if ($request->has('modified_products')) {
            foreach ($request->modified_products as $prodId => $product) {
                // Prepare API payload
                $apiProducts[] = [
                    'prod_id' => $prodId,
                    'qty' => $product['qty'],
                    'action' => $product['action']
                ];
                
                // Track deleted products
                if ($product['action'] === 'delete') {
                    $deletedProducts[] = $prodId;
                }
                
                // Handle temporary products
                if ($product['action'] === 'add') {
                    // New product to be added - CAST TO NUMBERS
                    $newTempProducts[] = [
                        'prod_id' => $prodId,
                        'product_detail' => $product['detail'] ?? 'New Product',
                        'qty' => (int)$product['qty'],
                        'cart_price' => (float)($product['price'] ?? 0),
                        'total_product_price' => (float)($product['price'] ?? 0) * (int)$product['qty'],
                        'is_temp' => true
                    ];
                } else if ($product['action'] === 'update') {
                    // Existing product being updated
                    $foundInTemp = false;
                    
                    // Check if product exists in temporary products
                    foreach ($existingTempProducts as $index => $tempProduct) {
                        if ($tempProduct['prod_id'] == $prodId) {
                            // Update existing temporary product - CAST TO NUMBERS
                            $existingTempProducts[$index]['qty'] = (int)$product['qty'];
                            $existingTempProducts[$index]['total_product_price'] = 
                                (float)$existingTempProducts[$index]['cart_price'] * (int)$product['qty'];
                            $foundInTemp = true;
                            break;
                        }
                    }
                    
                    if (!$foundInTemp) {
                        // Product is from original order - create new temp version
                        $originalProduct = $this->findProductInOrder($originalOrder, $prodId);
                        
                        if ($originalProduct) {
                            // CAST VALUES TO NUMBERS
                            $newTempProduct = [
                                'prod_id' => $prodId,
                                'product_detail' => $originalProduct['product_detail'],
                                'qty' => (int)$product['qty'],
                                'cart_price' => (float)$originalProduct['cart_price'],
                                'total_product_price' => (float)$originalProduct['cart_price'] * (int)$product['qty'],
                                'is_temp' => true
                            ];
                            $newTempProducts[] = $newTempProduct;
                        }
                    }
                }
            }
        }

        // Update existing temporary products
        $updatedTempProducts = array_filter($existingTempProducts, function($product) use ($deletedProducts) {
            return !in_array($product['prod_id'], $deletedProducts);
        });

        // Combine updated and new temporary products
        $finalTempProducts = array_merge($updatedTempProducts, $newTempProducts);
        session([$tempProductsKey => $finalTempProducts]);

        // Save deletion states
        $deleteStates = [];
        if ($request->has('delete_states')) {
            $deleteStates = json_decode($request->delete_states, true) ?? [];
        }
        session(['delete_states_' . $orderId => $deleteStates]);

        // Send updates to API
        $response = Http::post(env('NODE_API_BASE_URL') . '/api/admin/order_products/manage', [
            'order_id' => $orderId,
            'products' => $apiProducts,
            'parent_code' => $request->parent_code
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if ($data['status'] === '1') {
                return back()
                    ->with('success', 'Products updated successfully')
                    ->with('searchResults', session('searchResults'))
                    ->with('searchKeyword', session('searchKeyword'));
            }
            return back()->with('error', $data['message'] ?? 'Operation failed');
        }

        return back()->with('error', 'Failed to update products');
    }

    private function findProductInOrder($order, $prodId)
    {
        if (!isset($order['products'])) return null;
        
        foreach ($order['products'] as $product) {
            if ($product['prod_id'] == $prodId) {
                return $product;
            }
        }
        return null;
    }

    public function saveChanges(Request $request)
    {
        $request->validate([
            'order_id' => 'required'
        ]);

        $response = Http::post(env('NODE_API_BASE_URL') . '/api/admin/update_order', [
            'order_id' => $request->order_id
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if ($data['status'] === '1') {
                // Clear all temporary data for this order
                $orderId = $request->order_id;
                session()->forget('temp_products_' . $orderId);
                session()->forget('delete_states_' . $orderId);
                session()->forget('original_order_' . $orderId);
                session()->forget('searchResults');
                session()->forget('searchKeyword');
                
                return redirect()->route('admin.orders.pending.view', $orderId)
                    ->with('success', 'Order updated successfully');
            }
            return back()->with('error', $data['message'] ?? 'Update failed');
        }

        return back()->with('error', 'Failed to save changes');
    }
}