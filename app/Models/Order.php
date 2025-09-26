<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'helper_id',
        'status',
    ];

    public function helper()
    {
        return $this->belongsTo(User::class, 'helper_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function scopeSentToCashier($query)
    {
        return $query->where('status', 'sent_to_cashier');
    }

    public function scopePrepared($query)
    {
        return $query->where('status', 'prepared');
    }
}