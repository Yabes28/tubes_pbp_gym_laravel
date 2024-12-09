<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Register new user
    public function register(Request $request)
    {
        // Validate input
        $request->validate([
            'username' => 'required|string|max:255', // Username wajib untuk registrasi
            'email' => 'required|string|email|max:255|unique:users', // Validasi email
            'password' => 'required|string|min:8|confirmed', // Pastikan ada password_confirmation di form
            'berat' => 'required|integer', // Validasi berat
            'tinggi' => 'required|integer', // Validasi tinggi
            'gender' => 'required|in:laki-laki,perempuan' // Validasi gender untuk hanya menerima "laki-laki" atau "perempuan"
        ]);
    
        // Create user
        $user = User::create([
            'username' => $request->username, // Simpan username
            'email' => $request->email, // Simpan email
            'password' => Hash::make($request->password), // Hash password
            'berat' => $request->berat, // Simpan berat
            'tinggi' => $request->tinggi, // Simpan tinggi
            'gender' => $request->gender, // Simpan gender
        ]);
    
        return response()->json([
            'user' => $user,
            'message' => 'User registered successfully'
        ], 201);
    }
    


    // Login user
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|string|email', // Hanya email
            'password' => 'required|string', // Hanya password
        ]);

        // Authenticate user
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Create Personal Access Token
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    // Logout user
    public function logout(Request $request)
    {
        // Delete the user's tokens on logout
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    // Show all users
    public function index()
    {
        $allUser = User::all();
        return response()->json($allUser);
    }

    // Update user details
    public function update(Request $request, string $id)
    {
        // Validate input
        $validatedData = $request->validate([
            'username' => 'required',
            'berat' => 'required',
            'tinggi' => 'required',
        ]);

        // Find the user by ID
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => "User not found"], 404);
        }

        // Update user details
        $user->username = $request->username;
        $user->berat = $request->berat; 
        $user->tinggi = $request->tinggi;
        $user->save();

        return response()->json($user);
    }

    // Delete user
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => "User not found"], 404);
        }

        // Delete the user
        $user->delete();

        return response()->json(['message' => "User deleted successfully"]);
    }
}
