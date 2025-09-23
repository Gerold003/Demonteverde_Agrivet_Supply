<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_CASHIER = 'cashier';
    const ROLE_HELPER = 'helper';
    const ROLE_INVENTORY = 'inventory';

    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class, 'helper_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'cashier_id');
    }

    public function stockIns()
    {
        return $this->hasMany(StockIn::class, 'inventory_staff_id');
    }

    public function stockOuts()
    {
        return $this->hasMany(StockOut::class, 'inventory_staff_id');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isCashier()
    {
        return $this->role === self::ROLE_CASHIER;
    }

    public function isHelper()
    {
        return $this->role === self::ROLE_HELPER;
    }

    public function isInventoryStaff()
    {
        return $this->role === self::ROLE_INVENTORY;
    }
}