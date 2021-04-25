<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use Inertia\Inertia;

class HomeController extends Controller {
	public function index() {
		$themes = Addon::where('type', 'theme')->with('user')->orderBy('release', 'desc')->limit(4)->get();
		$plugins = Addon::where('type', 'plugin')->with('user')->orderBy('release', 'desc')->limit(4)->get();
		return Inertia::render('Home', compact('themes', 'plugins'));
	}

	public function support() {
		return Inertia::render('Support/Index');
	}
}