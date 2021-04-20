<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller {
	public function login() {
		return Socialite::driver('discord')->redirect();
	}

	public function callback() {
		$discordUser = Socialite::driver('discord')->user();

		if (User::where('provider_id', $discordUser->getId())->whereNotNull('banned')->first()) {
			flash('error', 'That account has been banned');
			return redirect('/');
		}

		$user = User::updateOrCreate([
			'provider_id' => $discordUser->getId()
		], [
			'name' => $discordUser->getName(),
			'avatar' => $discordUser->getAvatar(),
			'discrim' => $discordUser->user['discriminator']
		]);

		Auth::login($user, true);
		return redirect('/');
	}

	public function update() {
		$user = User::find(Auth::id());
		$user->links = request()->all();
		$user->save();

		flash('success', 'Settings updated');
		return redirect()->back();
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

		flash('success', 'Account deleted');
		return redirect('/');
	}
}