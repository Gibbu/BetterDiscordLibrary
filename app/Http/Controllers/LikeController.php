<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Like;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller {
    
	public function like($id) {
		$addon = Addon::findOrFail($id);
		$user = User::find(Auth::id());

		$like = Like::where(['addon_id' => $addon->id, 'user_id' => $user->id])->first();
		if ($like) {
			$like->delete();
			
			return ['message' => 'Addon unliked'];
		} else {
			Like::create([
				'addon_id' => $addon->id,
				'user_id' => $user->id
			]);
				
			return ['message' => 'Addon liked'];
		}
	}
}