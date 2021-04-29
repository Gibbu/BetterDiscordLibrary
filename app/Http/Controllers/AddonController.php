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

			$addons = Addon::where('type', rtrim($type, 's'))->with('user')->withCount('likes')->orderBy($query[0], $query[1])->get();
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
			$comments = $addon->comments()
				->with(['user',	'addon', 'replies' => fn($q) => $q->with('user')])
				->whereNull('reply_id')
				->orderBy('pinned', 'desc')
				->orderBy('created_at', 'desc')
				->get();

			if (!session('_'.$addon->name)) {
				$addon->view_count = $addon->view_count + 1;
				$addon->save();
				session(['_'.$addon->name => 'viewed']);
			}

			$isLiked = Auth::check() ? Auth::user()->likes->where('addon_id', $addon->id)->first() : false;
			return Inertia::render('Addons/Show', compact('addon', 'isLiked', 'comments'));
		}
	}

	public function update($type, $name) {
		if ($type == 'themes' || $type == 'plugins') {
			$addon = Addon::where(['type' => rtrim($type, 's'), 'name' => $name])->with('user')->firstOrFail();
			if (Auth::id() == $addon->user->id || in_array('admin', Auth::user()->getRoles())) {
				$this->save($addon, true);

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

	public function download($id) {
		$addon = Addon::findOrFail($id);

		if (!session()->get('dl_'.$id)) {
			session(['dl_'.$id => true]);

			$addon->download_count = $addon->download_count + 1;
			$addon->save();
		}

		$fileName = $addon->name.'.'.($addon->type == 'theme' ? 'theme.css' : 'plugin.js');
		$file = tempnam(sys_get_temp_dir(), $fileName);
		copy($addon->download, $file);

		return response()->download($file, $fileName);
	}

	// Private functions
	private function save($addon, $editing = false) {
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
		$addon->release = request()->release;
		$addon->source = request()->source;
		if (!$editing) {
			$addon->user_id = Auth::id();
		}

		$addon->save();
	}

	private function getQuery() {
		if (request()->query()) {
			foreach (request()->query() as $query => $order) {
				if (in_array($query, ['name', 'view_count', 'download_count', 'release'])) {
					return [$query, $order];
				}
			}
		}
		return ['name', 'asc'];
	}
}