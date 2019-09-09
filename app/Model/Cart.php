<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cart
 * @package App\Model
 */
class Cart extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['id', 'currency_iso_code'];
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var bool
     */
    public $incrementing = false;
    /**
     * @var string
     */
    protected $table = 'cart';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'cart_id');
    }
}
