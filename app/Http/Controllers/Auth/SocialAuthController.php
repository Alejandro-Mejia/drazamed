<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Support\Facades\Log;
use App\User;
use Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SocialAuthController extends Controller
{
    // Metodo encargado de la redireccion a Facebook
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    // Metodo encargado de obtener la información del usuario
    public function handleProviderCallback($provider)
    {
        // Obtenemos los datos del usuario
        Log::info('provider ' . $provider);
        // $social_user = Socialite::driver($provider)->stateless()->user();
        try {
            $social_user = Socialite::driver($provider)->stateless()->user();
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::info('error ' , $e);
        }
        // Log::info('Social_User ' , $social_user.ToArray());
        // Comprobamos si el usuario ya existe
        if ($user = User::where('email', $social_user->email)->first()) {
            return $this->authAndRedirect($user); // Login y redirección
        } else {
            // En caso de que no exista creamos un nuevo usuario con sus datos.
            $user = User::create([
                'name' => $social_user->name,
                'email' => $social_user->email,
                'avatar' => $social_user->avatar,
            ]);
            // Log::info('Login facebook', $user);
            return $this->authAndRedirect($user); // Login y redirección
        }
    }
    // Login y redirección
    public function authAndRedirect($user)
    {
        Auth::login($user);
        return redirect()->to('/home#');
    }
}
