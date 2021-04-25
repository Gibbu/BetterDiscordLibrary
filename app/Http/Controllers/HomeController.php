<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use Inertia\Inertia;

class HomeController extends Controller {
	public function index() {
		$themes = Addon::where('type', 'theme')->with('user')->withCount('likes')->orderBy('release', 'desc')->limit(6)->get();
		$plugins = Addon::where('type', 'plugin')->with('user')->withCount('likes')->orderBy('release', 'desc')->limit(6)->get();
		return Inertia::render('Home', compact('themes', 'plugins'));
	}

	public function about() {
		return Inertia::render('About');
	}
}