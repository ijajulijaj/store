<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\PortalAccess;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    
    public function login()
    {
        return view('auth/login');
    }
  
    public function loginAction(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $response = Http::timeout(15)
                ->post(env('NODE_API_BASE_URL') . '/api/admin/portal_login', [
                    'username' => $request->username,
                    'password' => $request->password,
                    'device_token' => $request->device_token,
                ]);

            $apiResponse = $response->json();

            if (empty($apiResponse)) {
                throw new \Exception('Empty API response');
            }

            if ($apiResponse['status'] !== '1') {
                return back()->withErrors($apiResponse['message'] ?? 'Authentication failed');
            }

            $user = PortalAccess::find($apiResponse['payload']['user_id']);

            if (!$user) {
                return back()->withErrors(['User not found in local system']);
            }

            Auth::login($user);

            // Store critical session data
            session([
                'access_token' => $apiResponse['payload']['auth_token'],
                'outlet_code'  => $apiResponse['payload']['outlet_code'],  // Store outlet code
                'user_type'    => $apiResponse['payload']['user_type'],    // Store user type
                'user_name'    => $apiResponse['payload']['name']          // Optional but useful
            ]);

            // Add outlet code to user model if needed
            $user->outlet_code = $apiResponse['payload']['outlet_code'];
            $user->save();

            return redirect()->route('dashboard')
                ->with('success', $apiResponse['message']);

        } catch (\Exception $e) {
            Log::error("API Login Error: " . $e->getMessage());
            
            $errorMessage = $e->getCode() === 404 
                ? 'Authentication service not found' 
                : 'Authentication service unavailable';
                
            return back()->withErrors([$errorMessage]);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
  
        $request->session()->invalidate();
  
        return redirect('/');
    }
 
    public function profile()
    {
        return view('profile');
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        // Validate input
        Validator::make($request->all(), [
            'username' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'The password confirmation does not match.',
        ])->validate();

        // Update local fields (if needed)
        $user->update([
            'username' => $request->username ?? $user->username,
            'name' => $request->name ?? $user->name,
        ]);

        // If password is provided, call Node API to change it
        if ($request->filled('password')) {
            try {
                $response = Http::timeout(15)
                ->withHeaders([
                    'access_token' => session('access_token')
                ])
                ->post(env('NODE_API_BASE_URL') . '/api/admin/change_password', [
                    'username' => $user->username,
                    'new_password' => $request->password,
                    'confirm_password' => $request->password_confirmation,
                ]);
                $apiResponse = $response->json();

                if (empty($apiResponse)) {
                    throw new \Exception('Empty API response.');
                }

                if ($apiResponse['status'] !== '1') {
                    return back()->withErrors($apiResponse['message'] ?? 'Password change failed.');
                }

            } catch (\Exception $e) {
                Log::error("Password Change API Error: " . $e->getMessage());
                return back()->withErrors(['Password change service unavailable.']);
            }
        }

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }
    
    public function customerList(Request $request)
    {
        try {
            // Ensure the admin is logged in and token exists
            $accessToken = session('access_token');
            if (empty($accessToken)) {
                return redirect()->route('login')->withErrors('Access token missing. Please log in again.');
            }
    
            // Call Node API
            $response = Http::timeout(15)
                ->withHeaders([
                    'access_token' => $accessToken,
                ])
                ->post(env('NODE_API_BASE_URL') . '/api/admin/customer_list', []);
    
            $apiResponse = $response->json();
    
            if (empty($apiResponse)) {
                throw new \Exception('Empty API response from Node server.');
            }
    
            if ($apiResponse['status'] !== '1') {
                return back()->withErrors($apiResponse['message'] ?? 'Failed to fetch customer list.');
            }
    
            // Extract customer list data
            $customers = $apiResponse['payload'];
            $total = $apiResponse['total_customers'];
    
            // Return to Blade view
            return view('admin.customers.index', [
                'customers' => $customers,
                'total' => $total,
                'message' => $apiResponse['message'],
            ]);
    
        } catch (\Exception $e) {
            Log::error("Customer List API Error: " . $e->getMessage());
            return back()->withErrors(['Unable to fetch customer list at this time.']);
        }
    }
    
    public function changeUserPassword(Request $request, $user_id)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'The password confirmation does not match.',
        ]);
    
        try {
            $accessToken = session('access_token');
    
            if (empty($accessToken)) {
                return redirect()->route('login')->withErrors('Access token missing. Please log in again.');
            }
    
            $response = Http::timeout(15)
                ->withHeaders([
                    'access_token' => $accessToken,
                ])
                ->post(env('NODE_API_BASE_URL') . '/api/app/change_user_password', [
                    'user_id' => $user_id,
                    'new_password' => $request->password,
                    'confirm_password' => $request->password_confirmation,
                ]);
    
            $apiResponse = $response->json();
    
            if (empty($apiResponse)) {
                throw new \Exception('Empty API response from Node server.');
            }
    
            if ($apiResponse['status'] !== '1') {
                return back()->with('error', $apiResponse['message'] ?? 'Password change failed.');
            }
    
            return redirect()
                ->route('admin.customers.index') // You can adjust this route as needed
                ->with('success', 'Password changed successfully for the user.');
    
        } catch (\Exception $e) {
            Log::error("Change Password API Error: " . $e->getMessage());
            return back()->with('error', 'Unable to change password at this time.');
        }
    }



}