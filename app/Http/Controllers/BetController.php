<?php

namespace App\Http\Controllers;

use App\Http\Requests\BetCreateRequest;
use App\Http\Requests\BetUpdateRequest;
use App\Models\Bet;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class BetController extends Controller
{
    public function index(Request $request): View
    {
        $isAdmin = $request->user()->isAdmin();
        $isManager = $request->user()->isManager();

        if($isAdmin || $isManager){
            $bets = Bet::has('user')
                ->with('user')
                ->orderByDesc('created_at')
                ->paginate(30);
        }else{
            $bets = Bet::has('user')
                ->with('user')
                ->where('user_id', $request->user()->id)
                ->orderByDesc('created_at')
                ->paginate(30);
        }

        return view('bets.index', [
            'bets' => $bets
        ]);
    }

    public function new(): View
    {
        return view('bets.new');
    }

    public function edit(Request $request, int $id): View
    {
        $isAdmin = $request->user()->isAdmin();
        if(!$isAdmin)
            return view('errors.401');

        $bet = Bet::find($id);
        if(!$bet)
            return view('errors.404');

        return view('bets.edit', [
            'bet' => $bet,
        ]);
    }

    public function create(BetCreateRequest $request): RedirectResponse
    {
        if($request->input('value') >= 5){
            $lastUserBetsWithHighValue = Bet::where('user_id', $request->user()->id)
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->where('value', '>=', 5)
                ->count();

            if($lastUserBetsWithHighValue >= 2)
                return Redirect::route('bets.index')->with('error', 'bet-not-created-too-many-high-from-user');
        }

        $bet = new Bet();
        $bet->url = $request->input('url');
        $bet->value = $request->input('value');
        $bet->rate_control = $request->has('rate_control');
        $bet->rate_control_value = $request->has('rate_control') ? (int)$request->input('rate_control_value') : null;
        $bet->user()->associate($request->user());
        $bet->save();

        return Redirect::route('bets.index')->with('status', 'bet-created');
    }

    public function update(BetUpdateRequest $request): RedirectResponse | View
    {
        $isAdmin = $request->user()->isAdmin();
        if(!$isAdmin)
            return view('errors.401');

        //dd($request->has('rate_control'), $request->has('rate_control') ? (int)$request->input('rate_control_value') : null);

        $bet = Bet::find($request->input('id'));
        if($bet instanceof Bet){
            $bet->url = $request->input('url');
            $bet->value = $request->input('value');
            $bet->rate_control = $request->has('rate_control');
            $bet->rate_control_value = $request->has('rate_control') ? (int)$request->input('rate_control_value') : null;
            $bet->save();
        }

        return Redirect::route('bets.edit', ['id' => $request->input('id')])->with('status', 'bet-updated');
    }

    public function destroy(Request $request): RedirectResponse | View
    {
        $isAdmin = $request->user()->isAdmin();
        if(!$isAdmin)
            return view('errors.401');

        $request->validate([
            'id' => 'required',
        ]);

        Bet::find($request->post('id'))->delete();
        return Redirect::route('bets.index')->with('status', 'bet-deleted');
    }
}
