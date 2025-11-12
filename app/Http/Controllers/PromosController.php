<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PromosController extends Controller
{
    public function index()
    {
        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])->post(env('NODE_API_BASE_URL') . '/api/admin/promo_code_list');

        $promo_codes = [];

        if ($response->successful()) {
            $json = $response->json();
            $promo_codes = $json['data'] ?? [];
        }

        // Calculate counts
        $total_count    = count($promo_codes);
        $active_count   = collect($promo_codes)->where('status', 'Active')->count();
        $inactive_count = collect($promo_codes)->where('status', 'Inactive')->count();

        return view('admin.promo.index', compact('promo_codes', 'total_count', 'active_count', 'inactive_count'));
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
                    ->route('admin.promo.index')
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
                ->post(env('NODE_API_BASE_URL') . '/api/admin/import-promo');

            if ($response->successful()) {
                return redirect()
                    ->route('admin.promo.index')
                    ->with('success', 'File imported successfully!');
            }

            return redirect()
                ->route('admin.promo.index')
                ->with('error', 'Import failed: ' . $response->body());

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.promo.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }
}

