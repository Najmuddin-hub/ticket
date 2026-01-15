<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    public function store(Request $request)
{
    $request->validate([
        'password' => ['required', 'string'], // staff IC
    ]);

    $user = $request->user();

    // staff_id is stored in users.email (as you designed)
    $staffId = $user->email;
    $staffIc = trim($request->input('password'));

    try {
        $resp = \Illuminate\Support\Facades\Http::timeout(5)
            ->withHeaders([
                'X-API-KEY' => config('services.hr.key'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post(config('services.hr.url'), [
                'staff_id' => $staffId,
                'staff_ic' => $staffIc,
            ]);
    } catch (\Throwable $e) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'password' => 'Sistem pengesahan HR tidak dapat dicapai.',
        ]);
    }

    if (! $resp->ok()) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'password' => 'Pengesahan HR gagal.',
        ]);
    }

    $json = $resp->json();

    if (!($json['valid'] ?? false)) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'password' => 'IC tidak sah.',
        ]);
    }

    // Mark password as confirmed (Laravel requirement)
    $request->session()->put('auth.password_confirmed_at', time());

    return redirect()->intended();
}

    /**
     * Confirm the user's password.
     */
    //public function store(Request $request): RedirectResponse
   // {
    //    $request->validate([
      //  'password' => ['required', 'string'], // user types staff_ic again
        //]);

        // staff_id is stored in users.email
    //    $staffId = $request->user()->email;
    //    $staffIc = trim($request->password);

    //    $resp = Http::timeout(5)
    //        ->withHeaders([
    //            'X-API-KEY' => env('HR_API_KEY'),
    //            'Accept' => 'application/json',
    //            'Content-Type' => 'application/json',
    //     ])
    //        ->post(env('HR_API_URL'), [
    //            'staff_id' => $staffId,
    //            'staff_ic' => $staffIc,
    //        ]);
    //    if (! $resp->ok() || !($resp->json('valid') ?? false)) {
    //        throw ValidationException::withMessages([
    //            'password' => $resp->json('message') ?? __('auth.password'),
    //        ]);
    //    }

    //    $request->session()->put('auth.password_confirmed_at', time());

    //    return redirect()->intended(route('dashboard', absolute: false));
        /** (! Auth::guard('web')->validate([
        *   'email' => $request->user()->email,
        *   'password' => $request->password,
       *])) {
       *    throw ValidationException::withMessages([
        *       'password' => __('auth.password'),
         *  ]);
        *

        *request->session()->put('auth.password_confirmed_at', time());

        *rturn redirect()->intended(route('dashboard', absolute: false)); */
   // }
}
