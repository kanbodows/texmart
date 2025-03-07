<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Filter extends Model
{
    protected $table = 'filters';

    protected $fillable = [
        'name',
        'filter_key'
    ];

    public $timestamps = false;

    public $need_updated_by = false;
    public $need_deleted_by = false;
    public $need_created_by = false;
}
