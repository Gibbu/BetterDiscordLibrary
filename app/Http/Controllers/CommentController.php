<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\Commented;
use App\Notifications\Replied;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller {
	public function store() {
		request()->validate([
			'message' => ['required', 'min:12']
		]);

		$addon = Addon::find(request()->addon_id);

		if (request()->reply_id) {
			$comment = Comment::find(request()->reply_id);
			$ids = [$comment->user_id];

			foreach ($comment->replies as $reply) {
				$ids[] = $reply->user_id;
			}
			$userIds = array_unique(array_diff($ids, [Auth::id()]));

			foreach ($userIds as $userId) {
				$user = User::find($userId);
				$user->notify(new Replied(Auth::id(), $addon->id));
			}
		} else if (!request()->reply_id && Auth::id() !== $addon->user_id) {
			$dev = User::find($addon->user_id);
			$dev->notify(new Commented(Auth::id(), $addon->id));
		}

		$comment = new Comment();
		$comment->message = request()->message;
		$comment->addon_id = request()->addon_id;
		$comment->user_id = Auth::id();
		if (request()->reply_id) {
			$comment->reply_id = request()->reply_id;
		}
		$comment->save();

		return redirect()->back()->with(['flash' => ['type' => 'success', 'message' => 'Comment posted']]);
	}

	public function update($id) {
		request()->validate([
			'message' => ['required', 'min:12']
		]);

		$comment = Comment::find($id);

		if ($comment->user_id == Auth::id()) {
			$comment->message = request()->message;
			$comment->save();

			return redirect()->back()->with(['flash' => ['type' => 'success', 'message' => 'Comment updated']]);
		}
		return abort(401);
	}

	public function destroy($id) {
		$comment = Comment::with('addon')->find($id);
		if ($comment->user_id == Auth::id() || (in_array('dev', Auth::user()->getRoles()) && $comment->addon->user_id == Auth::id()) || in_array('admin', Auth::user()->getRoles())) {
			$comment->delete();

			return redirect()->back()->with(['flash' => ['type' => 'success', 'message' => 'Comment deleted']]);
		}
		return abort(401);
	}

	public function pin($id) {
		$comment = Comment::find($id);
		if ($comment->reply_id == null) {
			$comment->pinned = !$comment->pinned;

			$comment->save();

			return redirect()->back()->with(['flash' => ['type' => 'success', 'message' => 'Comment '.($comment->pinned ? 'pinned' : 'unpinned')]]);
		}
		return redirect()->back();
	}
}