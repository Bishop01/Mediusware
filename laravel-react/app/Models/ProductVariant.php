<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariantPrice;
use App\Models\Product;
use App\Models\Variant;

class ProductVariant extends Model
{
    public function product()
    {
        return $this->hasOne(Product::class);
    }
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}
