<?php

namespace App\Livewire;

use App\Models\Bet;
use App\Models\BetCombinator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Livewire\Component;

class LiveBet extends Component
{

    public Bet|null $bet = null; // initial value

    public function render() : View
    {
        // load from cache
        $cached = Cache::get('bet');
        $cachedTime = Cache::get('time');

        if(!empty($cached)){
            $this->bet = $cached;

            if($cachedTime < Carbon::now()->subSeconds(20))
            {
                $bet = Bet::has('user')
                    ->with(['user', 'combinators'])
                    ->where('id', '>', $this->bet->id)
                    ->orderBy('id')
                    ->first();

                if($bet instanceof Bet){
                    $this->bet = $bet;
                    Cache::put('bet', $bet);
                    Cache::put('time', Carbon::now());
                }
            }
        }else{
            $bet = Bet::has('user')
                ->with(['user', 'combinators'])
                ->where('created_at', '>=', Carbon::now()->subSeconds(20))
                ->orderBy('created_at')
                ->orderBy('id')
                ->first();

            if($bet instanceof Bet){
                $this->bet = $bet;
                Cache::put('bet', $bet);
                Cache::put('time', Carbon::now());
            }

        }

        // fallback for still empty bet - take the last
        if(empty($this->bet)){
            $this->bet = Bet::has('user')
                ->with(['user', 'combinators'])
                ->orderByDesc('id')
                ->first();
        }

        // we will parse bet combinators here
        $combiFix = $combiPercent = '';

        if($this->bet instanceof Bet){
            /** @var BetCombinator $combinator */
            foreach($this->bet->combinators as $combinator){
                if(!is_null($combinator->value))
                    $combiFix .= $combinator->aku . ";" . $combinator->value . ";";
                if(!is_null($combinator->percent))
                    $combiPercent .= $combinator->aku . ";" . $combinator->percent . ";";
            }
        }

        return view('livewire.live-bet', [
            'bet' => $this->bet,
            'combiFix' => $combiFix,
            'combiPercent' => $combiPercent
        ]);
    }
}
