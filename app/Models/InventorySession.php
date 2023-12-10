<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventorySession extends Model
{
    use HasFactory;


    public function inventoryMeasurements(): HasMany
    {
        return $this->hasMany(InventoryMeasurement::class, 'inventory_session_id', 'id');
    }

    public function inventorySessionWarehouses(): HasMany
    {
        return $this->hasMany(InventorySessionWarehouse::class, 'inventory_session_id', 'id');
    }
}
