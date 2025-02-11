<?php

namespace App\Models;

use App\Events\TaskCreate;
use App\Events\TaskDelete;
use App\Events\TaskUpdate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'status', 'user_id'];

    protected static function booted()
    {
        static::saved(function () {
            TaskCreate::dispatch();
        });
        static::updated(function () {
            TaskUpdate::dispatch();
        });
        static::deleted(function () {
            TaskDelete::dispatch();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
