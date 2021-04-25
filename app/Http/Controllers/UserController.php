<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller {
	public function show($name, $discrim) {
		$user = User::where(['name' => $name, 'discrim' => $discrim])->firstOrFail();
		$user->roles = $user->getRoles();

		$likes = $user->likes()->with(['addon' => fn($q) => $q->withCount('likes')])->get();
		$themes = $user->addons()->where('type', 'theme')->withCount('likes')->get();
		$plugins = $user->addons()->where('type', 'plugin')->withCount('likes')->get();

		return Inertia::render('Users/Show', compact('user', 'likes', 'themes', 'plugins'));
	}

	public function devs() {
		$devs = User::whereHas('roles', fn($q) => $q->where('name', 'dev'))->get();
		return Inertia::render('Users/Devs', compact('devs'));
	}
}