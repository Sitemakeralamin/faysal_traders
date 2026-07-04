<?php

namespace App\Models;

use Dompdf\Css\Color;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category:: class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand:: class);
    }

    // public function variation()
    // {
    //     return $this->hasMany(ProductVariation:: class);
    // }


    public function reviews()
    {
        return $this->hasMany(ProductsReviews:: class, 'product_id', 'id');
    }


    public function product_image()
    {
        return $this->hasMany(ProductImage:: class);
    }

    public function product_category()
    {
        return $this->hasMany(ProductWithCategory:: class, 'product_id', 'id');
    }

    
    public function variation_stock() {
        return $this->hasMany(ProductStocks::class);
    }

    
    public function single_stock()
    {
        return $this->belongsTo(ProductStocks:: class, 'id', 'product_id')->where('variant', '=', null)->where('color', '=', null);
    }

    // In Product.php
    public function options() {
        return $this->belongsToMany(FilterHeadOption::class, 'product_filter_head_option', 'product_id', 'filter_head_option_id')->with('head');
    }




}
