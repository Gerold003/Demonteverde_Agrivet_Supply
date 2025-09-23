<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'inventory_staff_id',
        'quantity',
        'unit',
        'reason',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inventoryStaff()
    {
        return $this->belongsTo(User::class, 'inventory_staff_id');
    }
}