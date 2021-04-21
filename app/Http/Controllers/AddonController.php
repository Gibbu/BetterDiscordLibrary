<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Rules\Url;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class AddonController extends Controller {
	public function index($type) {
		if ($type == 'themes' || $type == 'plugins') {
			$query = $this->getQuery();

			$addons = Addon::where('type', rtrim($type, 's'))->with('user')->orderBy($query[0], $query[1])->get();
			return Inertia::render('Addons/Index', compact('addons', 'type'));
		}
		return abort(404);
	}

	public function create() {
		return Inertia::render('Addons/Create');
	}

	public function store() {
		$addon = new Addon();
		$this->save($addon);

		return redirect($addon->type.'s/'.$addon->name)->with('flash', ['type' => 'success', 'message' => 'Addon successfully added']);
	}

	public function show($type, $name) {
		if ($type == 'themes' || $type == 'plugins') {
			$addon = Addon::where(['type' => rtrim($type, 's'), 'name' => $name])->with('user')->withCount('likes')->firstOrFail();

			if (!session('_'.$addon->name)) {
				$addon->view_count = $addon->view_count + 1;
				$addon->save();
				session(['_'.$addon->name => 'viewed']);
			}

			$isLiked = Auth::check() ? Auth::user()->likes->where('addon_id', $addon->id)->first() : false;
			return Inertia::render('Addons/Show', compact('addon', 'isLiked'));
		}
	}
	
	public function update($type, $name) {
		if ($type == 'themes' || $type == 'plugins') {
			$addon = Addon::where(['type' => rtrim($type, 's'), 'name' => $name])->with('user')->firstOrFail();
			if (Auth::id() == $addon->user->id) {
				$this->save($addon);

				return redirect()->back()->with('flash', ['type' => 'success', 'message' => $addon->name.' successfully updated']);
			}
			return abort(401);
		}
		return abort(404);
	}

	public function destroy($type, $name) {
		$addon =  Addon::where(['type' => rtrim($type, 's'), 'name' => $name])->firstOrFail();
		if ($addon->user_id === Auth::id()) {
			$addon->delete();
			
			return redirect('/')->with('flash', ['type' => 'success', 'message' => $name.' deleted']);
		}
		return abort(404);
	}

	// Ajax calls
	public function download($id) {
		if (!session()->get('dl_'.$id)) {
			session(['dl_'.$id => true]);

			$addon = Addon::findOrFail($id);
			$addon->download_count = $addon->download_count + 1;
			$addon->save();

			return ['message' => 'Download added to download counter'];
		}
		return ['message' => 'already clicked'];
	}


	// Private functions
	private function save($addon) {
		request()->validate([
			'type' => ['required'],
			'name' => ['required', 'string', 'max:24', Rule::unique('addons')->ignore(request()->id)],
			'description' => ['required', 'string'],
			'download' => ['required', 'string', new Url],
			'images.*' => ['nullable', 'string', new Url],
			'thumbnail' => ['nullable', 'string', new Url],
			'release' => ['required', 'date'],
			'source' => ['required', 'string', new Url]
		]);

		$addon->type = request()->type;
		$addon->name = request()->name;
		$addon->description = request()->description;
		$addon->download = request()->download;
		$addon->images = request()->images;
		$addon->contributors = request()->contributors;
		$addon->thumbnail = request()->thumbnail;
		$addon->release = request()->release;
		$addon->source = request()->source;
		$addon->user_id = Auth::id();

		$addon->save();
	}

	private function getQuery() {
		if (request()->query()) {
			foreach (request()->query() as $query => $order) {
				if (in_array($query, ['name', 'view_count', 'download_count', 'like_count'])) {
					return [$query, $order];
				}
			}
		}
		return ['name', 'asc'];
	}
}