<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPriceAggregate extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'period', 'average_price', 'calculated_at'];
}
