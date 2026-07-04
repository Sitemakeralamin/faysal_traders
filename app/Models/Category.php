<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function child()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('position', 'ASC');
    }

    public function menu_child()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('menu_position', 'ASC')->where('is_menu_active', 0);
    }

    // Category.php
    public function filterHeads()
    {
        return $this->belongsToMany(FilterHead::class, 'category_filter_head', 'category_id', 'filter_head_id');
    }



    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')
                    ->orderBy('menu_position', 'ASC');
    }
    
    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
