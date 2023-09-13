<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $isAdmin = $request->user()->isAdmin();
        if(!$isAdmin)
            return view('errors.401');

        $users = User::orderByDesc('created_at')->paginate(30);

        return view('users.index', [
            'users' => $users
        ]);
    }

    public function new(Request $request): View
    {
        $isAdmin = $request->user()->isAdmin();
        if(!$isAdmin)
            return view('errors.401');

        return view('users.new');
    }

    public function edit(Request $request, int $id): View
    {
        $isAdmin = $request->user()->isAdmin();
        if(!$isAdmin)
            return view('errors.401');

        $user = User::find($id);
        if(!$user)
            return view('errors.404');

        return view('users.edit', [
            'user' => $user,
        ]);
    }

    public function create(UserCreateRequest $request): RedirectResponse | View
    {
        $isAdmin = $request->user()->isAdmin();
        if(!$isAdmin)
            return view('errors.401');

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');
        $user->password = Hash::make($request->input('password'));
        $user->setCreatedAt(Carbon::now('Europe/Prague'));
        $user->save();

        return Redirect::route('users.index')->with('status', 'user-created');
    }

    public function update(UserUpdateRequest $request): RedirectResponse | View
    {
        $isAdmin = $request->user()->isAdmin();
        if(!$isAdmin)
            return view('errors.401');

        $user = User::find($request->input('id'));
        if($user){
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->role = $request->input('role');
            $user->setUpdatedAt(Carbon::now('Europe/Prague'));

            if($request->has('password'))
                $user->password = Hash::make($request->input('password'));

            $user->save();
        }

        return Redirect::route('users.edit', ['id' => $request->input('id')])->with('status', 'user-updated');
    }

    public function destroy(Request $request): RedirectResponse | View
    {
        $isAdmin = $request->user()->isAdmin();
        if(!$isAdmin)
            return view('errors.401');

        $request->validate([
            'id' => 'required',
        ]);

        User::find($request->post('id'))->delete();
        return Redirect::route('users.index')->with('status', 'user-deleted');
    }
}
