<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Interfaces\UserRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class UserController extends Controller {

    public function __construct(private UserRepo $repo) {}

    function show(Request $request) {
        $lista = $this->repo->all();
        // Hämta inloggad användare
        $me = $request->user();
        return View::make('user', ['lista' => $lista, 'me' => $me]);
    }

    public function showUser(Request $request) {
        $id = $request->route('id');
        $user = $this->repo->get($id);

        return View::make('user', ['user' => $user]);
    }

    function add(Request $request) {
        // Bara admin ska kunna lägga till användare
        $me = $request->user();
        if (!$me->admin) {
            return View::make('ajabaja');
        }

        $user = User::factory()->make($request->request->all());
        $this->repo->add($user);
        return redirect('/anvandare');
    }

    public function modifyUser(Request $request) {
        // Hämta inloggad användare
        $me = $request->user();

        $id = $request->route('id');

        // Bara admin får radera men inte sig själv
        if ($request->request->has('delete') && ($id == $me->id || !$me->admin)) {
            return View::make('ajabaja');
        }

        // Kontrollera för uppdateringsrättigheter
        if ($id != $request->request->get('id')) {
            return View::make('ajabaja');
        }

        // Allt ok - utför radering eller uppdatering
        if ($request->request->has('delete')) {
            $this->repo->delete($id);
        }
        else {
            $user = $this->repo->get($id);
            $user->fill($request->request->all());
            if (!$me->admin) {
                $user->admin = 0;
            }
            $this->repo->update($user);
        }
        return redirect('/anvandare');
    }
}
