<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PatientProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:8|confirmed',
            'age'           => 'required|integer|min:18',
            'gender'        => 'required|in:male,female,other',
            'blood_group'   => 'nullable|string|max:5',
            'phone'         => 'required|string|max:20', // 1. Validate phone input
        ]);

        $user = DB::transaction(function () use ($validated) {
            // Create Base User
            $user = User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'uid'       => 'pat_' . Str::lower(Str::random(12)),
                'password'  => Hash::make($validated['password']),
                'is_active' => true,
            ]);

            // Assign Spatie RBAC Role
            $user->assignRole('patient');

            // 2. Map phone value to emergency_contact field
            PatientProfile::create([
                'user_id'           => $user->id,
                'age'               => $validated['age'],
                'gender'            => $validated['gender'],
                'blood_group'       => $validated['blood_group'],
                'emergency_contact' => $validated['phone'], // <-- Fixed here
                'medical_history'   => [],
            ]);

            return $user;
        });

        Auth::login($user);

        return redirect()->route('patient.dashboard')->with('success', 'Account established successfully!');
    }
}