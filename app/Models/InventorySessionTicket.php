<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

class InventorySessionTicket extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
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
