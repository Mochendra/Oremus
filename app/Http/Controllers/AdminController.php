<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    // Display the user management view
    public function index()
    {
        // Get all users
        $users = User::all();
        $roles = Role::all(); // Fetch all roles
        return view('admin.users.index', compact('users', 'roles')); // Adjust the view path accordingly
    }

    // Assign role to user
    public function assignRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|exists:roles,name']);

        $user->syncRoles($request->role); // Assign the selected role to the user
        return redirect()->route('admin.users.index')->with('success', 'Role assigned successfully.');
    }
}
