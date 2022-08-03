<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Users;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    public function redirectToProvider()
    {
        config()->set('services.google.redirect', "http://localhost:8000/auth/google/callback");
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('google')->scopes(['openid','email'])->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))->stateless()->user();
        // $user = Socialite::driver($provider)->stateless()->user();
        $authUser = $this->findOrCreateUser($user, 'google');
        Auth::login($authUser, true);

        Session::put('loginUser', Hash::make($user->name));
        Session::put('usernameuser', $user->name);
        Session::put('nameUser', $user->name);
        return redirect('/');
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user)
    {
        $authUser = Users::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        else{
            $data = Users::create([
                'nama_user'     => $user->name,
                'username' => $user->name,
                'password' => "password",
                'email'    => !empty($user->email)? $user->email : '' ,
                'no_hp' =>"no_hp",
                'alamat' =>"alamat",
                'jk' => "jk",
                'provider' => 'google',
                'provider_id' => $user->id
            ]);
            return $data;
        }
    }
}