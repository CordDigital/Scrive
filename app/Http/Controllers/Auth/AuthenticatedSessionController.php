<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    /**
     * Handle an incoming authentication request.
     */
  public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    if (Auth::user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($request->filled('redirect') && str_starts_with($request->input('redirect'), url('/'))) {
        return redirect($request->input('redirect'));
    }

    $locale = session('locale', 'ar');
    return redirect()->route($locale === 'en' ? 'en.home' : 'home');
}

    /**
     * Destroy an authenticated session.
     */
public function destroy(Request $request): RedirectResponse
{
    $locale = session('locale', 'ar');

    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    session(['locale' => $locale]);

    return redirect($locale === 'en' ? '/en' : '/');
}
}
