<?php

namespace App\Livewire;

use App\Models\Bet;
use Carbon\Carbon;
use Livewire\Component;

class LiveBet extends Component
{

    public Bet|null $bet = null; // initial value

    public function refreshBet()
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
    }

    public function render()
    {
        $this->refreshBet();
        return view('livewire.live-bet', [
            'bet' => $this->bet
        ]);
    }
}
