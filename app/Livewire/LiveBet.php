<?php

namespace App\Livewire;

use App\Models\Bet;
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
        $this->bet = $cached;

        $performCache = false;

        if(empty($this->bet)){
            $this->bet = Bet::has('user')
                ->with('user')
                ->where('created_at', '>=', Carbon::now('Europe/Prague')->subSeconds(20))
                ->orderBy('created_at')
                ->orderBy('id')
                ->first();
            if($this->bet instanceof Bet)
                $performCache = true;

        // load new bet only if the current bet is older than 20 s
        }else{
            if($this->bet->created_at < Carbon::now('Europe/Prague')->subSeconds(20)) {
                $bet =  Bet::has('user')
                    ->with('user')
                    ->where('id', '>', $this->bet->id)
                    ->orderBy('id')
                    ->first();
                // found new
                if($bet instanceof Bet){
                    $this->bet = $bet;
                    $performCache = true;
                }
            }
        }

        // fallback for still empty bet - take the last
        if(empty($this->bet)){
            $this->bet = Bet::has('user')
                ->with('user')
                ->orderByDesc('id')
                ->first();
            $performCache = false;
        }

        // save the actual to session (if different from the older)
        if($performCache)
            Cache::put('bet', $this->bet, 20);

        return view('livewire.live-bet', [
            'bet' => $this->bet
        ]);
    }
}
