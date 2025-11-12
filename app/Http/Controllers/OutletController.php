<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OutletController extends Controller
{
    public function index()
    {
        $response = Http::post(env('NODE_API_BASE_URL') . '/api/admin/outlet_list', []);

        // Check for errors
        if ($response->successful()) {
            $json = $response->json();
            $outlets = $json['data'] ?? []; // ✅ get only the actual list
        } else {
            $outlets = [];
        }

        return view('admin.outlets.index', compact('outlets'));
    }
}
