<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function showRegisterForm()
    {
        return view('customer.register');
    }

    public function showLoginForm()
    {
        return view('customer.login');
    }

    public function showLoginFailed()
    {
        return view('customer.failed_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        $user = User::where('username', $request->username)->first();

        if ($user) {
            $customer = Customer::where('user_id', $user->id)->first();

            if ($customer->status === 'Deactivated') {
                return response()->json(['message' => 'Login Failed: Account is deactivated.'], 403);
            }

            if (Auth::attempt($credentials)) {
                return response()->json(['message' => 'Login successful!', 'redirect' => route('home')]);
            } else {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
    
    

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/home');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = 'default_photo.png';

        if ($request->hasFile('image')) {
            $customerImage = $request->file('image');
            $imageName = $customerImage->getClientOriginalName();
            $customerImage->move(public_path('Images/Customers'), $imageName);
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        $customer = Customer::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            // 'phone' => $request->phone,
            // 'address' => $request->address,
            'image' => $imageName,
            'status' => 'Activated',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('message', 'Signed up successfully');
    }
}
