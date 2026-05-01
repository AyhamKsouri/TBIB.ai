<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:patient,doctor,admin',
            // Additional fields for patient/doctor
            'age' => 'required_if:role,patient|integer',
            'gender' => 'required_if:role,patient|string',
            'specialty' => 'required_if:role,doctor|string',
            'experience_years' => 'required_if:role,doctor|integer',
            'department_id' => 'required_if:role,doctor|exists:departments,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($user->role === 'patient') {
            Patient::create([
                'user_id' => $user->id,
                'age' => $request->age,
                'gender' => $request->gender,
            ]);
        } elseif ($user->role === 'doctor') {
            Doctor::create([
                'user_id' => $user->id,
                'specialty' => $request->specialty,
                'experience_years' => $request->experience_years,
                'department_id' => $request->department_id,
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->load(['patient', 'doctor']));
    }

    public function registerWeb(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:patient,doctor',
            'age' => 'required_if:role,patient|nullable|integer',
            'gender' => 'required_if:role,patient|nullable|string',
            'specialty' => 'required_if:role,doctor|nullable|string',
            'experience_years' => 'required_if:role,doctor|nullable|integer',
            'department_id' => 'required_if:role,doctor|nullable|exists:departments,id',
            // New medical fields
            'persistent_sickness' => 'nullable|string',
            'allergies' => 'nullable|string',
            'current_treatments' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($user->role === 'patient') {
            Patient::create([
                'user_id' => $user->id,
                'age' => $request->age,
                'gender' => $request->gender,
                'persistent_sickness' => $request->persistent_sickness,
                'allergies' => $request->allergies,
                'current_treatments' => $request->current_treatments,
            ]);
        } elseif ($user->role === 'doctor') {
            Doctor::create([
                'user_id' => $user->id,
                'specialty' => $request->specialty,
                'experience_years' => $request->experience_years,
                'department_id' => $request->department_id,
            ]);
        }

        Auth::login($user);

        return redirect('/dashboard');
    }
}
