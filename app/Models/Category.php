<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    public function announces() {
        return $this->hasMany(Announce::class);
    }

    public function childs() {
        return $this->hasMany(Category::class,'parent_id','id') ;
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    // public function productions()
    // {
    //     return $this->belongsToMany(Production::class);
    // }

    public function hasParent()
    {
        if ($this->parent->parent) {
            $this->parent->hasParent();
        }
        return $this->parent;
    }

    public function checkChildren($cat, $category)
    {
        if (count($cat->childs)) {
            foreach ($cat->childs as $child) {
                if ($child->id == $category->id) {
                    return $category->title;
                } else {
                    $child->checkChildren($child, $category);
                    return $category->title;
                }
            }
        }
    }
}
