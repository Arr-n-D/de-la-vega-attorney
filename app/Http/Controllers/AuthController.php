<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('discord')->redirect();
    }

    public function callback()
    {
        /** @var \SocialiteProviders\Manager\OAuth2\User $discordUser */
        $discordUser = Socialite::driver('discord')->user();
        // dd($discordUser);
        
        $user = User::firstOrCreate([
            'discord_id' => $discordUser->getId()
        ], [
            'name' => $discordUser->name,
            'email' => $discordUser->email,
            'avatar' => $discordUser->avatar,
        ]);

        // check if user was recently created
        if ($user->wasRecentlyCreated) {
            // send welcome email
        }

        // generate token

        return $this->okResponse([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user
        ]);
    }
}
