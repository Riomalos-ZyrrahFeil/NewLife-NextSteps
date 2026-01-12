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
          $q->where('first_name', 'like', $searchTerm)
            ->orWhere('last_name', 'like', $searchTerm)
            ->orWhere('email', 'like', $searchTerm);
      });
    }

    if ($request->filled('filter_status')) {
      $query->where('status', $request->input('filter_status'));
    }

    $users = $query
            ->orderByRaw("CASE WHEN role = 'admin' THEN 0 ELSE 1 END ASC")
            ->orderBy('last_name', 'asc')
            ->get();

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
      'first_name' => ['required', 'string', 'max:100'],
      'last_name' => ['required', 'string', 'max:100'],
      'email' => ['required', 'string', 'email',
                'max:100', 'unique:tbl_user,email'],
      'password' => ['required', 'string', 'min:8', 'confirmed'],
      'role' => ['required', Rule::in(['admin', 'volunteer'])],
    ]);

    User::create([
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'email' => $request->email,
      'password_hash' => Hash::make($request->password),
      'role' => $request->role,
      'status' => 'active',
    ]);

    return redirect()->route('admin.users.index')
      ->with('success', 'User created!');
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
      'first_name' => ['required', 'string', 'max:100'],
      'last_name' => ['required', 'string', 'max:100'],
      'email' => [
        'required', 
        'string', 
        'email', 
        'max:100', 
        Rule::unique('tbl_user')->ignore($user->user_id, 'user_id')
      ],
      'role' => ['required', Rule::in(['admin', 'volunteer'])],
      'status' => ['required', Rule::in(['active', 'inactive'])],
      'password' => ['nullable', 'string', 'min:8', 'confirmed'],
    ]);

    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;
    $user->email = $request->email;
    $user->role = $request->role;
    $user->status = $request->status;

    if ($request->filled('password')) {
      $user->password_hash = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('admin.users.index')
      ->with('success', 'Account updated successfully!');
  }

  public function destroy(User $user)
  {
      $user->delete();

      return redirect()->route('admin.users.index')
          ->with('success', 'User deleted successfully.');
  }
}