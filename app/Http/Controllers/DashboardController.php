<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingCount = $this->getOrderCount('/api/admin/new_orders_list');
        $completeCount = $this->getOrderCount('/api/admin/completed_orders_list');
        $cancelCount = $this->getOrderCount('/api/admin/cancel_decline_orders_list');
        $monthlySales = $this->getMonthlySales();
        $lastMonthSales = $this->getLastMonthlSales();
        $topOutlets = $this->getTopOutletSales();
    
        $growthData = $this->fetchOrderStatistics(7);
        $monthlyForecast = $this->getMonthlyForecast(6);
        $outletGrowthData = $this->fetchOutletWiseOrderStatistics(7);
        $androidOrders = $this->getAndroidOrders();
        $iosOrders = $this->getIOSOrders();
        $topOutletPerformance = $this->getTopOutletPerformance();
    
        return view('dashboard', compact(
            'pendingCount',
            'completeCount',
            'cancelCount',
            'monthlySales',
            'lastMonthSales',
            'growthData',
            'monthlyForecast',
            'outletGrowthData',
            'androidOrders',
            'iosOrders',
            'topOutlets',
            'topOutletPerformance'
        ));
    }

    // This handles AJAX requests from JavaScript
    public function getOrderStatistics(Request $request)
    {
        $days = $request->input('days', 7);
        $growthData = $this->fetchOrderStatistics($days); // Use the helper method
        
        return response()->json([
            'status' => '1',
            'payload' => $growthData,
            'message' => 'Order statistics fetched successfully'
        ]);
    }

    private function getOrderCount($endpoint)
    {
        $outlet_code = session('outlet_code');
        $user_type = session('user_type');
    
        $requestData = [];
        
        if ($user_type == 2) {
            $requestData['outlet_code'] = $outlet_code;
            $requestData['user_type'] = $user_type;
        }
    
        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])
        ->timeout(120)
        ->post(env('NODE_API_BASE_URL') . $endpoint, $requestData);
    
        $totalAmount = 0;
        $count = 0;
    
        if ($response->successful()) {
            $json = $response->json();
            $orders = $json['payload'] ?? [];
    
            $count = count($orders);
    
            foreach ($orders as $order) {
                // Choose which field to sum: total_price OR user_pay_price
                $totalAmount += $order['user_pay_price']; // or $order['total_price']
            }
        }
    
        return [
            'count' => $count,
            'amount' => $totalAmount
        ];
    }

    // Renamed this method to avoid conflict
    private function fetchOrderStatistics($days = 7)
    {
        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])->post(env('NODE_API_BASE_URL') . '/api/admin/order_statistics', [
            'days' => $days
        ]);
        
        if ($response->successful()) {
            return $response->json()['payload'] ?? [
                'labels' => [],
                'pending' => [],
                'completed' => [],
                'canceled' => []
            ];
        }

        return [
            'labels' => [],
            'pending' => [],
            'completed' => [],
            'canceled' => []
        ];
    }

    public function getOutletWiseOrderStatistics(Request $request)
    {
        $days = $request->input('days', 7);
        $growthData = $this->fetchOutletWiseOrderStatistics($days);

        return response()->json([
            'status' => '1',
            'payload' => $growthData,
            'message' => 'Outlet wise order statistics fetched successfully'
        ]);
    }
    
    private function getTopOutletSales()
    {
        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])->post(env('NODE_API_BASE_URL') . '/api/admin/top_outlet_sales', []);
    
        if ($response->successful()) {
            return $response->json()['payload']['top3_outlets'] ?? [];
        }
        return [];
    }
    
    private function getTopOutletPerformance()
    {
        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])->post(env('NODE_API_BASE_URL') . '/api/admin/top_outlet_performance', []);
    
        if ($response->successful()) {
            $payload = $response->json()['payload'] ?? [];
            return is_array($payload) ? $payload : [];
        }
    
        return [];
    }


    private function fetchOutletWiseOrderStatistics($days = 7)
    {
        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])->post(env('NODE_API_BASE_URL') . '/api/admin/outlet_wise_order_statistics', [
            'days' => $days
        ]);

        if ($response->successful()) {
            return $response->json()['payload'] ?? [
                'labels' => [],
                'outlets' => []
            ];
        }

        return [
            'labels' => [],
            'outlets' => []
        ];
    }

    private function getMonthlyForecast($months = 6)
    {
        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])->post(env('NODE_API_BASE_URL') . '/api/admin/monthly_order_forecast', [
            'months' => $months
        ]);

        if ($response->successful()) {
            return $response->json()['payload'] ?? [
                'labels' => [],
                'monthlyData' => [
                    'actual' => [],
                    'projected' => [],
                    'total' => [],
                    'pending' => [],
                    'completed' => [],
                    'canceled' => []
                ],
                'forecast_start_index' => 0
            ];
        }

        return [
            'labels' => [],
            'monthlyData' => [
                'actual' => [],
                'projected' => [],
                'total' => [],
                'pending' => [],
                'completed' => [],
                'canceled' => []
            ],
            'forecast_start_index' => 0
        ];
    }

    public function getMonthlyForecastApi(Request $request)
    {
        $months = $request->input('months', 6);
        $forecastData = $this->getMonthlyForecast($months);
        
        return response()->json([
            'status' => '1',
            'payload' => $forecastData,
            'message' => 'Monthly forecast fetched successfully'
        ]);
    }
    
    private function getMonthlySales()
    {
        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])->post(env('NODE_API_BASE_URL') . '/api/admin/monthly_sales', []);
    
        if ($response->successful()) {
            return [
                'amount' => $response->json()['payload']['total_sales'] ?? 0,
                'orders' => $response->json()['payload']['total_orders'] ?? 0,
            ];
        }
        return ['amount' => 0, 'orders' => 0];
    }

    private function getLastMonthlSales()
    {
        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])->post(env('NODE_API_BASE_URL') . '/api/admin/last_month_sales', []);
    
        if ($response->successful()) {
            return [
                'amount' => $response->json()['payload']['last_month_sales'] ?? 0,
                'orders' => $response->json()['payload']['last_month_orders'] ?? 0,
            ];
        }
    
        return ['amount' => 0, 'orders' => 0];
    }

    
    private function getAndroidOrders()
    {
        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])->post(env('NODE_API_BASE_URL') . '/api/admin/total_orders_android', []);
    
        if ($response->successful()) {
            return [
                'orders' => $response->json()['payload']['total_orders'] ?? 0,
                'amount' => $response->json()['payload']['total_amount'] ?? 0
            ];
        }
        return ['orders' => 0, 'amount' => 0];
    }

    private function getIOSOrders()
    {
        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])->post(env('NODE_API_BASE_URL') . '/api/admin/total_orders_ios', []);
    
        if ($response->successful()) {
            return [
                'orders' => $response->json()['payload']['total_orders'] ?? 0,
                'amount' => $response->json()['payload']['total_amount'] ?? 0
            ];
        }
        return ['orders' => 0, 'amount' => 0];
    }


}