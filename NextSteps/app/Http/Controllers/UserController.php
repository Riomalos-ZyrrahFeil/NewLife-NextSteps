<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
  public function index(Request $request)
  {
    $query = User::query();

    if ($request->filled('search')) {
      $searchTerm = '%' . $request->input('search') . '%';

      $query->where(function ($q) use ($searchTerm) {
          $q->where('name', 'like', $searchTerm)
            ->orWhere('email', 'like', $searchTerm);
      });
    }

    if ($request->filled('filter_status')) {
      $query->where('status', $request->input('filter_status'));
    }

    $users = $query->orderBy('name', 'asc')->get();
    return view('admin.users', compact('users'));
  }

  public function create()
  {
    $roles = ['admin', 'volunteer'];
    return view('admin.users.create', compact('roles'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'password' => ['required', 'string', 'min:8', 'confirmed'], 
      'role' => ['required', Rule::in(['admin', 'volunteer'])], 
    ]);

    User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password), 
      'role' => $request->role,
      'status' => 'active', 
    ]);

    return redirect()->route('admin.users.index')
      ->with('success', 'New account created successfully!');
  }

  public function edit(User $user)
  {
    $roles = ['admin', 'volunteer'];
    $statuses = ['active', 'inactive'];
    return view('admin.users.edit', compact('user', 'roles', 'statuses'));
  }

  public function update(Request $request, User $user)
  {
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
      'role' => ['required', Rule::in(['admin', 'volunteer'])],
      'status' => ['required', Rule::in(['active', 'inactive'])],
      'password' => ['nullable', 'string', 'min:8', 'confirmed'],
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role;
    $user->status = $request->status;

    if ($request->filled('password')) {
      $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('admin.users.index')
      ->with('success', 'Account updated successfully!');
  }

  public function destroy(User $user)
  {
    $user->delete();
    return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
  }
}