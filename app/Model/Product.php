<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $fillable = ['id', 'name', 'price', 'currency_iso_code', 'cart_id'];
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
}
