<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\Rule;

class AuthController extends Controller {
	public function login() {
		return Socialite::driver('discord')->redirect();
	}

	public function callback() {
		$discordUser = Socialite::driver('discord')->user();

		if (User::where('provider_id', $discordUser->getId())->whereNotNull('banned')->first()) {
			return redirect('/')->with('flash', ['type' => 'error', 'message' => 'That account has been banned']);
		}

		$user = User::where('provider_id', $discordUser->getId())->first();
		if ($user) {
			$user->avatar = $discordUser->getAvatar();
		} else {
			$user = new User();
			$user->provider_id = $discordUser->getId();
			$user->name = $discordUser->getName();
			$user->avatar = $discordUser->getAvatar();
			$user->slug = $discordUser->getName().$discordUser->user['discriminator'];
		}
		$user->save();

		Auth::login($user, true);
		return redirect('/');
	}

	public function update() {
		request()->validate([
			'name' => ['required'],
			'slug' => ['required', Rule::unique('users')->ignore(Auth::id())]
		]);

		$user = User::find(Auth::id());

		if (in_array('dev', $user->getRoles())) {
			$user->links = [
				'github' => request()->github,
				'paypal' => request()->paypal,
				'patreon' => request()->patreon,
				'support' => request()->support,
			];
		}
		$user->name = request()->name;
		$user->slug = request()->slug;
		$user->save();

		return redirect()->back()->with('flash', ['type' => 'success', 'message' => 'Settings updated']);
	}
	
	public function logout() {
		Auth::logout();
		session()->flush();
		return redirect('/');
	}

	public function destroy() {
		$user = User::find(Auth::id());
		$user->delete();

		Auth::logout();

		return redirect('/')->with('flash', ['type' => 'success', 'message' => 'Account deleted']);
	}
}