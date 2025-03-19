<?php

namespace App\Models;

use App\Enums\CallbackStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Callback extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'comment',
        'status',
        'updated_by'
    ];

    protected $casts = [
        'status' => CallbackStatus::class
    ];

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
