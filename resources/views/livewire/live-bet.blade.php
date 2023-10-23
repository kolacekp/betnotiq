<div>
    <div wire:poll.20s.keep-alive>
        @if(empty($bet))
            <pre class="no-bet">
               Žádná nová sázka
            </pre>
        @else
            <pre class="bet-{{$bet->id}}">
                <span class="id">ID: {{$bet->id}}</span>
                <span class="url">URL: {{$bet->url}}</span>
                <span class="value">HODNOTA: {{$bet->value}}</span>
                <span class="rate_control">KONTROLA KURZU: {{$bet->rate_control? $bet->rate_control_value : 'NE'}}</span>
                <span class="created">VYTVORENA: {{$bet->created_at}}</span>
                <span class="name">JMENO: {{$bet->user->name}}</span>
            </pre>
        @endif
    </div>
</div>
