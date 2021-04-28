<?php

namespace App\Http\Controllers;

use App\Models\Addon;

class SearchController extends Controller {
  public function index() {
		$query = request()->query('query');

		if (strlen($query) > 2) {
			$addons = Addon::where('name', 'like', '%'.$query.'%')
				->with('user')
				->limit(15)
				->get();
			return $addons;
		}
	}
}