<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class AdminController extends Controller {
	public function index() {
		$users = User::where('name', 'like', '%'.request()->query('search').'%')->with('roles')->limit(10)->get();
		$query = request()->query('search');

		return Inertia::render('Admin/Index', compact('users', 'query'));
	}

	public function update() {
		$user = User::find(request()->id);
		$isDev = $user->roles()->where('role_id', 1)->first();

		if (request()->isDev && !$isDev) {
			$user->roles()->attach(1);
		} else {
			$user->roles()->detach(1);
		}
		$user->banned = request()->banned;
		$user->save();
		
		return redirect()->back()->with('flash', ['type' => 'success', 'message' => $user->name.'#'.$user->discrim.' updated']);
	}
}