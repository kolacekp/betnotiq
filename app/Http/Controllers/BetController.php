<?php

namespace App\Http\Controllers;

use App\Http\Requests\BetCreateCashoutRequest;
use App\Http\Requests\BetCreateRequest;
use App\Http\Requests\BetUpdateRequest;
use App\Models\Bet;
use App\Models\BetCombinator;
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

    public function new(Request $request): View
    {
        $isAdmin = $request->user()->isAdmin();
        return view('bets.new', ['isAdmin' => $isAdmin]);
    }

    public function newCashout(Request $request): View
    {
        $isAdmin = $request->user()->isAdmin();
        $isManager = $request->user()->isManager();
        if(!($isAdmin || $isManager))
            return view('errors.401');
        return view('bets.newCashout');
    }

    public function edit(Request $request, int $id): View
    {
        $isAdmin = $request->user()->isAdmin();
        if(!$isAdmin)
            return view('errors.401');

        $bet = Bet::with('combinators')->find($id);
        if(!$bet)
            return view('errors.404');

        $akuIndexes = $akuTypes = $akuValuesValue = $akuValuesPercent = [];
        for ($i = 0; $i < 20; $i++) {
            $akuIndexes[] = false;
            $akuTypes[] = 0;
            $akuValuesValue[] = null;
            $akuValuesPercent[] = null;
        }

        /** @var BetCombinator $combinator */
        foreach ($bet->combinators as $combinator){
            $akuIndexes[$combinator->aku - 1] = true;

            if(!is_null($combinator->value)){
                $akuTypes[$combinator->aku - 1] = 0;
                $akuValuesValue[$combinator->aku - 1] = $combinator->value;
            }

            if(!is_null($combinator->percent)){
                $akuTypes[$combinator->aku - 1] = 1;
                $akuValuesPercent[$combinator->aku - 1] = $combinator->percent;
            }
        }

        $groups = [false, false, false, false, false, false, false, false];
        if(!empty($bet->groups)){
            foreach (json_decode($bet->groups) as $group){
                $groups[$group] = true;
            }
        }

        return view('bets.edit', [
            'bet' => $bet,
            'akuIndexes' => $akuIndexes,
            'akuTypes' => $akuTypes,
            'akuValuesValue' => $akuValuesValue,
            'akuValuesPercent' => $akuValuesPercent,
            'groups' => $groups
        ]);
    }

    public function create(BetCreateRequest $request): RedirectResponse
    {
        $isAdmin = $request->user()->isAdmin();

        if(!$isAdmin && $request->input('value') >= 5){
            $lastUserBetsWithHighValue = Bet::where('user_id', $request->user()->id)
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->where('value', '>=', 5)
                ->count();

            if($lastUserBetsWithHighValue >= 2)
                return Redirect::route('bets.index')->with('error', 'bet-not-created-too-many-high-from-user');
        }

        // bet itself
        $bet = new Bet();
        $bet->url = $request->input('url');
        $bet->value = $request->has('value') ? $request->input('value') : 0;
        $bet->rate_control = $request->has('rate_control') ? (int)$request->input('rate_control_value') : null;
        $bet->fixed_value = $request->has('fixed_value') ? (int)$request->input('fixed_value_value') : null;
        $bet->user()->associate($request->user());

        if($request->has('groups')){
            $groups = $request->get('groups');
            $groupsArray = [];
            foreach ($groups as $key => $value){
                $groupsArray[] = $key;
            }
            $bet->groups = json_encode($groupsArray);
        }

        $bet->save();

        // bet combinators
        if($request->has('aku_indexes')){
            $akuIndexes = $request->get('aku_indexes');
            $akuTypes = $request->get('aku_types');
            $akuValues = $request->get('aku_values');
            $akuPercents = $request->get('aku_percents');

            foreach($akuIndexes as $akuIndexKey => $akuIndexValue){
                if(!(isset($akuTypes[$akuIndexKey]) && (isset($akuValues[$akuIndexKey]) || isset($akuPercents[$akuIndexKey]))))
                    continue;

                $betCombinator = new BetCombinator();
                $betCombinator->bet()->associate($bet);
                $betCombinator->aku = $akuIndexValue;
                $betCombinator->value = (int)$akuTypes[$akuIndexKey] == 0 ? ((float)$akuValues[$akuIndexKey] ?? null) : null;
                $betCombinator->percent = (int)$akuTypes[$akuIndexKey] == 1 ? ((float)$akuPercents[$akuIndexKey] ?? null) : null;
                $betCombinator->save();
            }
        }

        return Redirect::route('bets.index')->with('status', 'bet-created');
    }

    public function createCashout(BetCreateCashoutRequest $request): RedirectResponse
    {
        $isAdmin = $request->user()->isAdmin();
        $isManager = $request->user()->isManager();
        if(!($isAdmin || $isManager))
            return Redirect::route('bets.index');

        $bet = new Bet();
        $bet->type = 'C';
        $bet->url = '';
        $bet->value = 0;
        $bet->cashout_ticket = $request->input('cashout_ticket');
        $bet->cashout_reason = $request->input('cashout_reason');
        $bet->user()->associate($request->user());
        $bet->save();

        return Redirect::route('bets.index')->with('status', 'bet-cashout-created');
    }

    public function update(BetUpdateRequest $request): RedirectResponse | View
    {
        $isAdmin = $request->user()->isAdmin();
        if(!$isAdmin)
            return view('errors.401');

        $bet = Bet::find($request->input('id'));
        if($bet instanceof Bet){
            $bet->url = $request->input('url');
            $bet->value = $request->has('value') ? $request->input('value') : 0;
            $bet->rate_control = $request->has('rate_control') ? (int)$request->input('rate_control_value') : null;
            $bet->fixed_value = $request->has('fixed_value') ? (int)$request->input('fixed_value_value') : null;

            if($request->has('groups')){
                $groups = $request->get('groups');
                $groupsArray = [];
                foreach ($groups as $key => $value){
                    $groupsArray[] = $key;
                }
                $bet->groups = json_encode($groupsArray);
            }

            $bet->save();

            // bet combinators
            BetCombinator::where('bet_id', $bet->id)->delete();
            if($request->has('aku_indexes')){
                $akuIndexes = $request->get('aku_indexes');
                $akuTypes = $request->get('aku_types');
                $akuValues = $request->get('aku_values');
                $akuPercents = $request->get('aku_percents');

                foreach($akuIndexes as $akuIndexKey => $akuIndexValue){
                    if(!(isset($akuTypes[$akuIndexKey]) && (isset($akuValues[$akuIndexKey]) || isset($akuPercents[$akuIndexKey]))))
                        continue;

                    $betCombinator = new BetCombinator();
                    $betCombinator->bet()->associate($bet);
                    $betCombinator->aku = $akuIndexValue;
                    $betCombinator->value = (int)$akuTypes[$akuIndexKey] == 0 ? ((float)$akuValues[$akuIndexKey] ?? null) : null;
                    $betCombinator->percent = (int)$akuTypes[$akuIndexKey] == 1 ? ((float)$akuPercents[$akuIndexKey] ?? null) : null;
                    $betCombinator->save();
                }
            }
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
