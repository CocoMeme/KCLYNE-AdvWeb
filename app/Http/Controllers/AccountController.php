<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            if ($customer && $customer->status === 'Deactivated') {
                return response()->json(['message' => 'Login Failed: Account is deactivated.'], 403);
            }
    
            if (Auth::attempt($credentials)) {
                $token = $user->createToken('Personal Access Token')->plainTextToken;
    
                Log::info('User logged in. Token:', ['token' => $token]);
    
                $redirectRoute = $user->role === 'admin' ? route('dashboard') : route('home');
    
                return response()->json([
                    'message' => 'Login successful!',
                    'redirect' => $redirectRoute,
                    'token' => $token,
                ]);
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

    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'username' => 'required|string|min:8|max:255|unique:users',
    //         'password' => 'required|string|min:8|confirmed',
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:customers',
    //         'phone' => 'nullable|string|max:255',
    //         'address' => 'nullable|string|max:255',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     ]);

    //     $imageName = 'default_photo.png';

    //     if ($request->hasFile('image')) {
    //         $customerImage = $request->file('image');
    //         $imageName = $customerImage->getClientOriginalName();
    //         $customerImage->move(public_path('Images/Customers'), $imageName);
    //     }

    //     $user = User::create([
    //         'username' => $request->username,
    //         'password' => Hash::make($request->password),
    //         'role' => 'customer',
    //     ]);

    //     $customer = Customer::create([
    //         'user_id' => $user->id,
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         // 'phone' => $request->phone,
    //         // 'address' => $request->address,
    //         'image' => $imageName,
    //         'status' => 'Activated',
    //     ]);

    //     Auth::login($user);

    //     return redirect()->route('home')->with('message', 'Signed up successfully');
    // }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|min:8|max:255|unique:users',
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
            'username' => $validatedData['username'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'customer',
        ]);

        $customer = Customer::create([
            'user_id' => $user->id,
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'] ?? null,
            'address' => $validatedData['address'] ?? null,
            'image' => $imageName,
            'status' => 'Activated',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('message', 'Signed up successfully');
    }


    public function profile()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->firstOrFail();

        return view('customer.customer-profile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|min:8|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|min:8|max:20',
            'address' => 'required|max:255',
            'image' => 'nullable|image|max:2048'
        ]);

        $customer = Auth::user()->customer;
        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->phone = $request->input('phone');
        $customer->address = $request->input('address');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('Images/Customers'), $filename);
            $customer->image = $filename;
        }

        $customer->save();

        return response()->json(['success' => true]);
    }

}
