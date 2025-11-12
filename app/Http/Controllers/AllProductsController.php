<?php

namespace App\Http\Controllers;

use App\Exports\AllProductsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class AllProductsController extends Controller
{
    public function index()
    {
        $response = Http::post(env('NODE_API_BASE_URL') . '/api/admin/all_products', []);

        // Check for errors
        if ($response->successful()) {
            $json = $response->json();
            $products = $json['data'] ?? []; // ✅ get only the actual list
        } else {
            $products = [];
        }

        return view('admin.products.index', compact('products'));
    }
    
    public function import(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            $file = $request->file('file');
            
            if (!$file->isValid()) {
                return redirect()
                    ->route('admin.products.index')
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
                ->post(env('NODE_API_BASE_URL') . '/api/admin/import-products');

            if ($response->successful()) {
                return redirect()
                    ->route('admin.products.index')
                    ->with('success', 'File imported successfully!');
            }

            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Import failed: ' . $response->body());

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function exportAllProducts(Request $request)
    {
        return Excel::download(new AllProductsExport, 'all_products.xlsx');
    }
}