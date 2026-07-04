<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterHead extends Model
{
    use HasFactory;

    protected $fillable = ["name", "category_id"];

    public function options()
    {
        return $this->hasMany(FilterHeadOption::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    
}
