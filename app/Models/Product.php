<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $primaryKey = 'product_id';

    public function productCategory()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function post()
    {
        return $this->hasMany(Post::class);
    }

    public function order_product()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
