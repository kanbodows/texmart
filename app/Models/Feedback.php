<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use SoftDeletes;
    protected $table = 'feedbacks';

    protected $fillable = ['feedback', 'rating', 'user_id', 'manufacture_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(User::class, 'manufacture_user_id');
    }
}
