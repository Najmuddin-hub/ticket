<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
//use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
     /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        // email field = staff_id, password field = staff_ic
        $request->validate([
            'email' => ['required', 'string'],      // staff_id
            'password' => ['required', 'string'],   // staff_ic
        ]);

     $staffId = trim($request->input('email'));
     $staffIc = trim($request->input('password'));

        // Call HR API (Windows)
     try {
        $resp = Http::timeout(5)
            ->withHeaders([
                'X-API-KEY' => env('HR_API_KEY'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post(env('HR_API_URL'), [
                'staff_id' => $staffId,
                'staff_ic' => $staffIc,
            ]);
        } catch (\Throwable $e) {
            throw ValidationException::withMessages([
                'email' => 'Sistem pengesahan HR tidak dapat dicapai. Sila cuba lagi.',
            ]);
        }

    if (! $resp->ok()) {
        throw ValidationException::withMessages([
            'email' => 'Pengesahan HR gagal (HTTP ' . $resp->status() . ').',
        ]);
    }

    $json = $resp->json();

    if (!($json['valid'] ?? false)) {
        throw ValidationException::withMessages([
            'email' => $json['message'] ?? 'ID Staf / IC tidak sah.',
        ]);
    }

    $staff = $json['staff'] ?? [];
    $name = $staff['name'] ?? $staffId;

    //$adminIds = config('auth.admin_staff_ids');

    //$userType = in_array($staffId, $adminIds)
      //  ? 'admin'
      //  : 'user';

    // 🔐 Determine user role by staff ID
    $adminIds = config('auth.admin_staff_ids');
    $itIds    = config('auth.it_staff_ids');

    if (in_array($staffId, $adminIds)) {
        $userType = 'admin';
    } elseif (in_array($staffId, $itIds)) {
        $userType = 'it';
    } else {
        $userType = 'user';
    }

    $user = User::updateOrCreate(
        ['email'=> $staffId],
        [
            'name' => $name,
            'user_type' => $userType,
            'password' => Hash::make(str()->random(32)),
        ]
    );

    // Create/update local user. Store staff_id inside users.email (as you want)
    //$user = User::updateOrCreate(
      //  ['email' => $staffId],
       // [
        //    'name' => $name,
        //    'user_type' => 'user', // change if you decide roles later
        //    'password' => Hash::make(str()->random(32)), // local password not used
        //]
    //);

    Auth::login($user, $request->boolean('remember'));
    $request->session()->regenerate();

    // keep your redirect logic
    $user = Auth::user();
    if ($user->user_type === 'user') {
        return redirect()->route('tickets.mine');
    } elseif ($user->user_type === 'vendor') {
        return redirect()->route('tickets.tasks');
    } else {
        return redirect()->intended(route('dashboard'));
    }
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}