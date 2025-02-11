<?php

namespace App\Models;

use App\Events\ChirpCreated;
use App\Events\ChirpEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chirp extends Model
{
    protected $fillable = [
        'message',
    ];

    protected static function booted()
    {
        static::saved(function () {
            ChirpEvent::dispatch();
        });
    }

    protected $dispatchesEvents = [
        'created' => ChirpCreated::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
