<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Response extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'announce_id'];

    protected $dates = ['deleted_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function announce(): BelongsTo
    {
        return $this->belongsTo(Announce::class);
    }
}
