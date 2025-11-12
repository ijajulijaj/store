<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CategorySubCatController extends Controller
{
    public function showCategoryWithSubCategory()
    {
        try {
            $accessToken = session('access_token');
            if (!$accessToken) {
                throw new \Exception('Authentication required');
            }
    
            // Fetch Category list
            $categoryResponse = Http::withHeaders([
                'access_token' => $accessToken
            ])->post(env('NODE_API_BASE_URL') . '/api/admin/product_category_list');
    
            $categories = $categoryResponse->json()['payload'] ?? [];
    
            // Fetch Sub-Category list
            $subCategoryResponse = Http::withHeaders([
                'access_token' => $accessToken
            ])->post(env('NODE_API_BASE_URL') . '/api/admin/product_sub_category_list');
    
            $subCategories = $subCategoryResponse->json()['payload'] ?? [];
    
            // Combine data
            $combined = [];
    
            foreach ($categories as $cat) {
                $filteredSubs = array_filter($subCategories, function ($sub) use ($cat) {
                    return isset($sub['cat_id']) && $sub['cat_id'] == $cat['cat_id'];
                });
    
                if (empty($filteredSubs)) {
                    $combined[] = [
                        'cat_id' => $cat['cat_id'],
                        'cat_name' => $cat['cat_name'],
                        'sub_cat_id' => '-',
                        'sub_cat_name' => '-',
                        'status' => $cat['status']
                    ];
                } else {
                    foreach ($filteredSubs as $sub) {
                        $combined[] = [
                            'cat_id' => $cat['cat_id'],
                            'cat_name' => $cat['cat_name'],
                            'sub_cat_id' => $sub['sub_cat_id'] ?? '-',
                            'sub_cat_name' => $sub['sub_cat_name'] ?? '-',
                            'status' => $sub['status'] ?? $cat['status']
                        ];
                    }
                }
            }
    
            return view('admin.groups.index', compact('combined'));
    
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error: ' . $e->getMessage());
        }
    }

}