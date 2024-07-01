<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return response()->json(['message' => 'User role updated successfully.']);
    }

    public function deactivate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->active = false;  // Assuming you have an 'active' field in the users table
        $user->save();

        return response()->json(['message' => 'User deactivated successfully.']);
    }
}
