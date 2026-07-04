<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterHeadOption extends Model
{
    use HasFactory;

    protected $fillable = ["filter_head_id", "name"];

    public function head()
    {
        return $this->belongsTo(FilterHead::class, 'filter_head_id');
    }
}
