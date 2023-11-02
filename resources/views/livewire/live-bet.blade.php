<div>
    <div wire:poll.20s.keep-alive>
        @if(empty($bet))
            <pre class="no-bet">
               ŽÁDNÁ NOVÁ SÁZKA
            </pre>
        @else
            <pre class="bet-{{$bet->id}}">
                <span class="id">ID: {{$bet->id}}</span>
                <span class="url">URL: {{$bet->url}}</span>
                <span class="value">HODNOTA: {{is_null($bet->fixed_value) ? $bet->value : 0}}</span>
                <span class="rate_control">KONTROLA KURZU: {{$bet->rate_control ?? 0}}</span>
                <span class="rate_control">FIXNÍ ČÁSTKA: {{$bet->fixed_value ?? 0}}</span>
                <span class="combi_percent">KOMBI: procento;{{$combiPercent}}</span>
                <span class="combi_fix">KOMBI: fix;{{$combiFix}}</span>
                <span class="created">VYTVOŘENA: {{$bet->created_at}}</span>
                <span class="name">JMÉNO: {{$bet->user->name}}</span>
            </pre>
        @endif
    </div>
</div>
