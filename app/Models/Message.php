<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Message extends Model
{
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'message',
        'file',
        'file_name',
        'file_path',
        'file_type',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'file' => 'boolean'
    ];

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public static function getChats()
    {
        return static::select('messages.*')
            ->join(DB::raw('(
                SELECT
                    CASE
                        WHEN from_user_id < to_user_id THEN from_user_id
                        ELSE to_user_id
                    END as user1_id,
                    CASE
                        WHEN from_user_id < to_user_id THEN to_user_id
                        ELSE from_user_id
                    END as user2_id,
                    MAX(created_at) as latest_message
                FROM messages
                GROUP BY
                    CASE
                        WHEN from_user_id < to_user_id THEN from_user_id
                        ELSE to_user_id
                    END,
                    CASE
                        WHEN from_user_id < to_user_id THEN to_user_id
                        ELSE from_user_id
                    END
            ) as latest_messages'), function($join) {
                $join->on('messages.created_at', '=', 'latest_messages.latest_message')
                    ->where(function($query) {
                        $query->whereRaw('(
                            messages.from_user_id = latest_messages.user1_id AND
                            messages.to_user_id = latest_messages.user2_id
                        )')->orWhereRaw('(
                            messages.from_user_id = latest_messages.user2_id AND
                            messages.to_user_id = latest_messages.user1_id
                        )');
                    });
            })
            ->with(['fromUser', 'toUser'])
            ->orderBy('created_at', 'desc');
    }
}
