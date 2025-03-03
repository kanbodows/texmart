<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announce extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'content', 'user_id', 'phone', 'code', 'email', 'locate', 'category_id', 'price', 'currency', 'date', 'images'];
    protected $casts = [
        'images' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function like_users()
    {
        return $this->belongsToMany(User::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class)->withTrashed();
    }

    public static function getProductionViews($id)
    {
        if (Session::has('productionsViewed')) {
            if (in_array($id, Session::get('productionsViewed'))) {
                return true;
            } else {
                $announces = Arr::prepend(Session::get('productionsViewed'), $id);
                Session::put('productionsViewed', $announces);
                return false;
            }
        }
        Session::put('productionsViewed', [$id]);

        return false;
    }
}
