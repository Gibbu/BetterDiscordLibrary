<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller {
  public function index() {
		return Inertia::render('Settings/Index');
	}
}