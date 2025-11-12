<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AllOffersController extends Controller
{
    public function index()
    {
        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])->post(env('NODE_API_BASE_URL') . '/api/admin/offer_list');

        // Check for errors
        if ($response->successful()) {
            $json = $response->json();
            $offers = $json['data'] ?? []; // ✅ get only the actual list
        } else {
            $offers = [];
        }

        return view('admin.offers.index', compact('offers'));
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
                    ->route('admin.offers.index')
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
                ->post(env('NODE_API_BASE_URL') . '/api/admin/import-offers');

            if ($response->successful()) {
                return redirect()
                    ->route('admin.offers.index')
                    ->with('success', 'File imported successfully!');
            }

            return redirect()
                ->route('admin.offers.index')
                ->with('error', 'Import failed: ' . $response->body());

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.offers.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

}
