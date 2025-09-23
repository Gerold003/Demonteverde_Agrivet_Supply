<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'description',
        'price_per_kilo',
        'price_per_sack',
        'price_per_piece',
        'current_stock_kilo',
        'current_stock_sack',
        'current_stock_piece',
        'critical_level_kilo',
        'critical_level_sack',
        'critical_level_piece',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function stockIns()
    {
        return $this->hasMany(StockIn::class);
    }

    public function stockOuts()
    {
        return $this->hasMany(StockOut::class);
    }

    // Check if product is below critical level
    public function isLowStock()
    {
        return $this->current_stock_sack <= $this->critical_level_sack;
    }
}