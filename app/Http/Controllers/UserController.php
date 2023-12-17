<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController
{
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'password' => 'required|string|max:15|min:5'
            ]);

            $sName = $request->input('name');
            $sEmail = $request->input('email');
            $sPassword = Hash::make($request->input('password'));

            $aResponseData = [
                'name' => $sName,
                'email' => $sEmail,
                'password' => $sPassword,
            ];

            User::create($aResponseData);

            return response()->json(['success' => true, 'data' => $aResponseData]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $credentials['email'])->first();

            if (!$user) {
                throw ValidationException::withMessages(['errors' => 'The user does not exist']);
            }

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();
                return response()->json(['success' => true, 'data' => $user]);
            } else {
                throw ValidationException::withMessages(['errors' => 'Invalid password']);
            }
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json(['success' => true, 'message' => 'User logged out successfully']);
    }
}
