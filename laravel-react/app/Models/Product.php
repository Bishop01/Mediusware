<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariantPrice;
use App\Models\ProductVariant;
use App\Models\ProductImage;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];

    public function productVariant()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function productVariantPrice()
    {
        return $this->hasMany(ProductVariantPrice::class);
    }
    public function productImages()
    {
        return $this->hasOne(ProductImage::class);
    }
}
