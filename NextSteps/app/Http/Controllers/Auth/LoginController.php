<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
	use AuthenticatesUsers;

	public function redirectTo()
	{
		$intendedUrl = session()->pull('url.intended');

		$user = auth()->user();

		if ($user->role === 'admin') {
			return '/admin/users';
		}

		if ($user->role === 'volunteer') {
			return '/dashboard';
		}

		return '/home'; 
	}

	public function __construct()
	{
		$this->middleware('guest')->except('logout');
	}

	public function showLoginForm()
	{
		return view('auth.login');
	}
}