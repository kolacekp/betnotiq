<?php

namespace App\Livewire;

use App\Models\Bet;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class LiveBet extends Component
{

    public Bet|null $bet = null; // initial value

    public function render() : View
    {
        if(empty($this->bet)){
            $this->bet = Bet::has('user')
                ->with('user')
                ->where('created_at', '>=', Carbon::now('Europe/Prague')->subSeconds(20))
                ->orderBy('created_at')
                ->orderBy('id')
                ->first();
        }else{
            $newBet = Bet::has('user')
                ->with('user')
                ->where('id', '>', $this->bet->id)
                ->orderBy('id')
                ->first();
            if($newBet instanceof Bet)
                $this->bet = $newBet;
        }

        // fallback for still empty bet - take the last
        if(empty($this->bet))
            $this->bet = Bet::has('user')
                ->with('user')
                ->orderByDesc('id')
                ->first();

        return view('livewire.live-bet', [
            'bet' => $this->bet
        ]);
    }
}
