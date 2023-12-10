<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InventorySessionTicket extends Model
{
    use HasFactory;


    public function inventorySessionWarehouse(): HasOne
    {
        return $this->hasOne(InventorySessionWarehouse::class, 'id', 'inventory_session_warehouse_id');
    }

    public function inventoryMeasurement(): BelongsTo
    {
        return $this->belongsTo(InventoryMeasurement::class, 'inventory_ticket_id', 'id');
    }
}
