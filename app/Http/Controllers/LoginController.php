<?php

namespace App\Http\Controllers;

use App\Models\Login;
use App\Models\User;
use App\Services\AuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class LoginController extends Controller {
    public function show() {
        return View::make('login');
    }

    public function login(Request $request, AuthenticationService $auth) {
        // Skapa login-objekt
        $login = Login::create($request->request->all());

        // Kolla inmatad data mot databasen
        $user = $auth->attemptLogin($login);

        // Returnera om inloggningen lyckades
        if ($user) {
            // returnera lyckad inloggning
            $request->session()->put('user_id', $user->id);
            $request->session()->save();
            return redirect("/{$user->namn}");
        }
        else {
            // Misslyckad inloggning
            return View::make('login', ['message' => 'Fel epost eller lösenord']);
        }
    }
}
