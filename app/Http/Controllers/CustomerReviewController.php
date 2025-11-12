<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CustomerReviewController extends Controller
{
    public function index()
    {
        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])->post(env('NODE_API_BASE_URL') . '/api/admin/review_product');

        if ($response->successful()) {
            $json = $response->json();
            $review = $json['data'] ?? [];
        } else {
            $review = [];
        }

        return view('admin.review.index', compact('review'));
    }
}