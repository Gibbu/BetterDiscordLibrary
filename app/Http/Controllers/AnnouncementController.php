<?php

namespace App\Http\Controllers;

use App\Models\Announcement;

class AnnouncementController extends Controller {
	public function store() {
		request()->validate([
			'title' => ['required'],
			'message' => ['required']
		]);

		Announcement::create([
			'title' => request()->title,
			'message' => request()->message,
		]);

		return redirect()->back()->with('flash', ['type' => 'success', 'message' => 'Announcement posted']);
	}

	public function update($id) {
		request()->validate([
			'title' => ['required'],
			'message' => ['required']
		]);

		$announcement = Announcement::find($id);
		$announcement->title = request()->title;
		$announcement->message = request()->message;

		$announcement->save();

		return redirect()->back()->with('flash', ['type' => 'success', 'message' => 'Announcement update']);
	}
	
	public function destroy($id) {
		$addon = Announcement::find($id);
		$addon->delete();
		
		return redirect()->back()->with('flash', ['type' => 'success', 'message' => 'Announcement deleted']);
	}
}