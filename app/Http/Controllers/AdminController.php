<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Show all users
    public function dashboard()
    {
        $users = User::where('role', '!=', 'admin')->orderBy('created_at', 'desc')->get();
        return view('admin.dashboard', compact('users'));
    }

    // Edit user form
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed',
            ]);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'User updated successfully.');
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }
}