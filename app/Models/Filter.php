<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Filter extends BaseModel
{
    public $timestamps = false;

    protected $guarded = [];

    public $need_updated_by = false;
    public $need_deleted_by = false;
    public $need_created_by = false;
}
