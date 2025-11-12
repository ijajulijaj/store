<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AnnouncementController extends Controller
{
    public function index()
    {
        // Show the add announcement form
        return view('admin.announcement.index');
    }

    public function store(Request $request)
    {
        // Validate form inputs
        $request->validate([
            'type'        => 'required|string|max:255',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        try {
           
            $accessToken = session('access_token');
    
            if (!$accessToken) {
                logger('No access token in session');
                throw new \Exception('Authentication required');
            }

            $response = Http::withHeaders([
                'access_token' => $accessToken
            ])->post(env('NODE_API_BASE_URL') . '/api/admin/announcement_add', [
                'type'        => $request->type,
                'title'       => $request->title,
                'description' => $request->description,
                'start_date'  => $request->start_date,
                'end_date'    => $request->end_date,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] === '1') {
                    return redirect()->back()->with('success', 'Announcement added successfully');
                } else {
                    return redirect()->back()->withErrors('Failed: ' . ($data['message'] ?? 'Unknown error'));
                }
            }

            return redirect()->back()->withErrors('Failed to connect to API');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $accessToken = session('access_token');
            if (!$accessToken) {
                throw new \Exception('Authentication required');
            }

            // Get all announcements from Node
            $response = Http::withHeaders([
                'access_token' => $accessToken
            ])->post(env('NODE_API_BASE_URL') . '/api/admin/announcement_list');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] === '1') {
                    $announcements = collect($data['payload']);
                    $announcement = $announcements->firstWhere('id', $id);

                    if ($announcement) {
                        return view('admin.announcement.edit', compact('announcement'));
                    }
                }
            }

            return redirect()->route('admin.announcement.list')->withErrors('Announcement not found');
        } catch (\Exception $e) {
            return redirect()->route('admin.announcement.list')->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type'        => 'required|string|max:255',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $accessToken = session('access_token');
            if (!$accessToken) {
                throw new \Exception('Authentication required');
            }

            $response = Http::withHeaders([
                'access_token' => $accessToken
            ])->post(env('NODE_API_BASE_URL') . '/api/admin/announcement_update', [
                'id'          => $id,
                'type'        => $request->type,
                'title'       => $request->title,
                'description' => $request->description,
                'start_date'  => $request->start_date,
                'end_date'    => $request->end_date,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] === '1') {
                    return redirect()->route('admin.announcement.list')->with('success', 'Announcement updated successfully');
                } else {
                    return redirect()->back()->withErrors('Failed: ' . ($data['message'] ?? 'Unknown error'));
                }
            }

            return redirect()->back()->withErrors('Failed to connect to API');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function announcements()
    {
        try {
            $accessToken = session('access_token');
            if (!$accessToken) {
                throw new \Exception('Authentication required');
            }

            $response = Http::withHeaders([
                'access_token' => $accessToken
            ])->post(env('NODE_API_BASE_URL') . '/api/admin/announcement_list');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] === '1') {
                    $announcements = $data['payload'];
                    return view('admin.announcement.view', compact('announcements'));
                }
            }

            return redirect()->back()->withErrors('Failed to fetch announcements');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error: ' . $e->getMessage());
        }
    }

}
