<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UserLogin;
use App\Models\Announcement;
use App\Models\FolderName;
use App\Models\LogoutLog;
use App\Models\LoginLog;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Str;


class RoleController extends Controller
{
    //view login form
    public function showLoginForm()
    {
        return view('login'); 
    }

    //login post
    public function login(Request $request)
        {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        
            $user = UserLogin::where('email', $request->email)->first();
        
            if (!$user || !Hash::check($request->password, $user->password)) {
                return redirect()->back()->with('error', 'Invalid email or password.');
            }
        
            Auth::login($user);
        
            if (in_array($user->role, ['faculty', 'faculty-coordinator'])) {
                \App\Models\LoginLog::create([
                    'user_login_id' => $user->user_login_id,
                    'login_time' => now(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                    'login_message' => $user->first_name . ' ' . $user->surname . ' has logged in',
                ]);
            }
        
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.admin-dashboard');
               
                case 'director':
                    return redirect()->route('director.director-dashboard');
                default:
                    return redirect()->back()->with('error', 'Invalid role.');
            }
        }
    
    //landing page
   public function showLandingPage()
    {
        if (Auth::check()) {
            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.admin-dashboard');
                case 'faculty':
                    return redirect()->route('faculty.faculty-dashboard');
                case 'faculty-coordinator':
                    return redirect()->route('faculty.faculty-dashboard'); 
                case 'director':
                    return redirect()->route('director.director-dashboard');
                default:
                    return redirect()->back()->with('error', 'Invalid role.');
            }
        }
    

        $state = Str::random(40);
        session(['oauth_state' => $state]);
    
        $announcement = Announcement::where('type_of_recepient', 'All Faculty')
                                    ->where('published', 1)
                                    ->orderBy('created_at', 'desc')
                                    ->first();
        
          $oauthUrl = 'https://pup-hris.site/auth/oauth?response_type=code&client_id=' . env('FARMS_CLIENT_ID') .
                    '&redirect_uri=' . urlencode(env('FARMS_REDIRECT_URI')) .
                    '&state=' . $state;
    
        return view('welcome', [
            'announcement' => Announcement::latest()->first(),
            'oauthUrl' => $oauthUrl,
        ]);
    }

    //API - HRIS
    public function handleProviderCallback(Request $request)
    {
        try {
            if ($request->state !== session('oauth_state')) {
                throw new Exception('Invalid state parameter');
            }
    
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->post('https://pup-hris.site/api/oauth/token', [
                'grant_type' => 'authorization_code',
                'code' => $request->code,
                'redirect_uri' => env('FARMS_REDIRECT_URI'),
                'client_id' => env('FARMS_CLIENT_ID'),
                'client_secret' => env('FARMS_CLIENT_SECRET')
            ]);
    
            if (!$response->successful()) {
                throw new Exception('Failed to get access token: ' . $response->status());
            }
    
            $tokenData = $response->json();
            
            if (!isset($tokenData['faculty_data'])) {
                throw new Exception('Faculty data not found in response');
            }
    
            $facultyData = $tokenData['faculty_data'];
    
            \Log::info('Faculty data before database insert:', [
                'faculty_code' => $facultyData['faculty_code'] ?? null,
                'faculty_id' => $facultyData['UserID'] ?? null, 
            ]);
    
            \DB::beginTransaction();
            try {
                $user = UserLogin::updateOrCreate(
                    ['email' => $facultyData['Email']],
                    [
                        'user_id' => $facultyData['UserID'],
                        'faculty_id' => $facultyData['UserID'], 
                        'faculty_code' => $facultyData['faculty_code'] ?? null, 
                        'first_name' => $facultyData['first_name'],
                        'middle_name' => $facultyData['middle_name'],
                        'surname' => $facultyData['last_name'],
                        'name_extension' => $facultyData['name_extension'],
                        'employment_type' => $facultyData['faculty_type'] ?? null,
                        'is_active' => $facultyData['status'] === 'Active',
                        'role' => 'faculty',
                        'password' => Hash::make(Str::random(16))
                    ]
                );
    
                \Log::info('User after database operation:', [
                    'user_id' => $user->user_login_id,
                    'faculty_code' => $user->faculty_code, 
                    'faculty_id' => $user->faculty_id,
                ]);
    
                $sessionData = [
                    'access_token' => $tokenData['access_token'],
                    'expires_in' => $tokenData['expires_in'],
                    'token_type' => $tokenData['token_type'] ?? 'Bearer',
                    'faculty_data' => $facultyData,
                    'user_id' => $user->user_login_id,
                    'user_role' => 'faculty'
                ];
    
                \DB::commit();
    
                $request->session()->regenerate(true);
                Auth::login($user, true);
                session($sessionData);
                session()->save();
    
                \Log::info('Authentication successful', [
                    'user_id' => $user->user_login_id,
                    'faculty_id' => $facultyData['UserID'],
                    'faculty_code' => $user->faculty_code, 
                    'session_id' => session()->getId(),
                    'is_authenticated' => Auth::check(),
                    'response_status' => $response->status()
                ]);
    
                return redirect()
                    ->route('faculty.faculty-dashboard')
                    ->withHeaders([
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0'
                    ]);
            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }
        } catch (Exception $e) {
            \Log::error('OAuth callback error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'code' => $request->code,
                'state' => $request->state,
                'response' => isset($response) ? $response->json() : null
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Authentication failed. Please try again.');
        }
    }
}
