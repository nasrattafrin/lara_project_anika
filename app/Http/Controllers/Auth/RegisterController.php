<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Register form dekhaw
     */
    public function showRegistrationForm()
    {
        return view('auth.login');
    }

    /**
     * Register process
     */
    public function register(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // 2. User create
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'customer',
            'status'   => 'active',
        ]);

        // 3. Spatie role assign (customer default)
        $user->assignRole('customer');

        // 4. Auto login
        Auth::login($user);

        // 5. Redirect to dashboard
        return redirect('/dashboard')->with('success', 'Account created successfully!');
    }
}