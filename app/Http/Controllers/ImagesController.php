<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImagesController extends Controller
{
    public function index()
    {
        // Show the add announcement form
        return view('admin.images.banner.index');
    }

    public function view()
    {
        try {
            $accessToken = session('access_token');
            if (!$accessToken) {
                throw new \Exception('Authentication required');
            }

            $response = Http::withHeaders([
                'access_token' => $accessToken
            ])->post(env('NODE_API_BASE_URL') . '/api/admin/slider_images');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] === '1') {
                    $sliders = $data['payload'];
                    return view('admin.images.banner.view', compact('sliders'));
                }
            }

            return redirect()->back()->withErrors('Failed to fetch slider images');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $accessToken = session('access_token');
            if (!$accessToken) {
                throw new \Exception('Authentication required');
            }

            // Prepare multipart data
            $response = Http::withHeaders([
                'access_token' => $accessToken
            ])->attach(
                'image',
                file_get_contents($request->file('image')->getRealPath()),
                $request->file('image')->getClientOriginalName()
            )->post(env('NODE_API_BASE_URL') . '/api/admin/slider_image_add', [
                'title'      => $request->input('title'),
                'start_date' => $request->input('start_date'),
                'end_date'   => $request->input('end_date'),
            ]);

            $data = $response->json();

            if ($response->successful() && $data['status'] === '1') {
                return redirect()->route('admin.images.banner.view')
                                 ->with('success', $data['message'] ?? 'Banner added successfully.');
            }

            return redirect()->back()->withErrors($data['message'] ?? 'Failed to add banner.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error: ' . $e->getMessage());
        }
    }
    
    public function delete($id)
    {
        try {
            $accessToken = session('access_token');
            if (!$accessToken) {
                throw new \Exception('Authentication required');
            }

            $response = Http::withHeaders([
                'access_token' => $accessToken
            ])->post(env('NODE_API_BASE_URL') . '/api/admin/slider_image_delete', [
                'slider_id' => $id
            ]);

            $data = $response->json();

            if ($response->successful() && $data['status'] === '1') {
                return redirect()->route('admin.images.banner.view')
                                ->with('success', $data['message'] ?? 'Banner deleted successfully.');
            }

            return redirect()->back()->withErrors($data['message'] ?? 'Failed to delete banner.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function productList()
    {
        try {
            $accessToken = session('access_token');
            if (!$accessToken) {
                throw new \Exception('Authentication required');
            }

            $response = Http::withHeaders([
                'access_token' => $accessToken
            ])->post(env('NODE_API_BASE_URL') . '/api/admin/product_images');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] === '1') {
                    $product_images = $data['payload'];
                    return view('admin.images.product_images.index', compact('product_images'));
                }
            }

            return redirect()->back()->withErrors('Failed to fetch product images');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error: ' . $e->getMessage());
        }
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
                    ->route('admin.images.product_images.index')
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
                ->post(env('NODE_API_BASE_URL') . '/api/admin/import-product-images');

            if ($response->successful()) {
                return redirect()
                    ->route('admin.images.product_images.index')
                    ->with('success', 'Product images file imported successfully!');
            }

            return redirect()
                ->route('admin.images.product_images.index')
                ->with('error', 'Import failed: ' . $response->body());

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.images.product_images.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

}