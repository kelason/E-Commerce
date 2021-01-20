<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $primaryKey = 'post_id';

    public function postProduct()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function postSeller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }
}
