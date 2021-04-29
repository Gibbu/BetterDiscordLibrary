<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller {
	public function index() {
		$notifications = [];
		foreach (Auth::user()->notifications as $notification) {
			$user = User::find($notification->data['user_id']);
			$addon = Addon::find($notification->data['addon_id']);

			$notifications[] = [
				'id' => $notification->id,
				'message' => $notification->data['message'],
				'created_at' => $notification->created_at,
				'read_at' => $notification->read_at,
				'user' => fn() => $user ? [
					'name' => $user->name,
					'avatar' => $user->avatar
				] : [
					'name' => 'Deleted user',
					'avatar' => '/images/favicon.png'
				],
				'addon' => [
					'type' => $addon->type,
					'name' => $addon->name
				]
			];
		}

		return Inertia::render('Notifications/Index', compact('notifications'));
	}

	public function read($id) {
		Auth::user()->notifications->find($id)->markAsRead();

		return redirect()->back();
	}

	public function readAll() {
		Auth::user()->unreadNotifications->markAsRead();
		
		return redirect()->back();
	}
}