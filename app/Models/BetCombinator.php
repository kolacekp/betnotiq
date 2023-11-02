<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BetCombinator extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bets_combinators';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'aku',
        'value',
        'percent'
    ];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * User's bets
     */
    public function bet(): BelongsTo
    {
        return $this->belongsTo(Bet::class);
    }
}
