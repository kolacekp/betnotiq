<div>
    <div wire:poll.keep-alive.20s="refreshBet">
        @if(empty($bet))
            <pre class="no-bet">
               No new bet
            </pre>
        @else
            <pre class="bet-{{$bet->id}}">
                <span class="id">ID: {{$bet->id}}</span>
                <span class="id">URL: {{$bet->url}}</span>
                <span class="id">HODNOTA: {{$bet->value}}</span>
                <span class="id">VYTVORENA: {{$bet->created_at}}</span>
                <span class="id">JMENO: {{$bet->user->name}}</span>
            </pre>
        @endif
    </div>
</div>
