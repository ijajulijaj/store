<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderModifyController extends Controller
{
    public function index()
    {
        return view('admin.orders.modify.index');
    }
    
    public function overrideOrder(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|numeric',
            'new_status' => 'required|in:1,2,3'
        ]);
    
        try {
            $response = Http::withHeaders([
                    'access_token' => session('access_token')
                ])
                ->timeout(60)
                ->post(env('NODE_API_BASE_URL') . '/api/admin/override-order-status', [
                    'order_id' => $request->order_id,
                    'new_status' => $request->new_status
                ]);
    
            if ($response->successful()) {
                return redirect()
                    ->route('admin.orders.modify.index')
                    ->with('success', 'Order status updated successfully!');
            }
    
            return redirect()
                ->route('admin.orders.modify.index')
                ->with('error', 'Update failed: ' . $response->body());
    
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.orders.modify.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function cancelBulkOrder(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            $file = $request->file('file');
            
            if (!$file->isValid()) {
                return redirect()
                    ->route('admin.orders.modify.index')
                    ->with('error', 'Invalid file upload');
            }

            $response = Http::withHeaders([
                    'access_token' => session('access_token')
                ])
                ->timeout(120)
                ->attach(
                    'file', 
                    file_get_contents($file->getRealPath()), 
                    $file->getClientOriginalName()
                )
                ->post(env('NODE_API_BASE_URL') . '/api/admin/bulk-cancel-orders');

            if ($response->successful()) {
                return redirect()
                    ->route('admin.orders.modify.index')
                    ->with('success', 'File imported successfully!');
            }

            return redirect()
                ->route('admin.orders.modify.index')
                ->with('error', 'Import failed: ' . $response->body());

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.orders.modify.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }
}