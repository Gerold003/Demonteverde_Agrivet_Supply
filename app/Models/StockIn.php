<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'supplier_id',
        'inventory_staff_id',
        'quantity',
        'unit',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function inventoryStaff()
    {
        return $this->belongsTo(User::class, 'inventory_staff_id');
    }
}