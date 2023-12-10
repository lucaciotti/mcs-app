<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

class InventorySessionWarehouse extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function inventorySession(): HasOne
    {
        return $this->hasOne(InventorySession::class, 'id', 'inventory_session_id');
    }

    public function warehouse(): HasOne
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    public function inventorySessionTickets(): HasMany
    {
        return $this->hasMany(InventorySessionTicket::class, 'inventory_session_warehouse_id', 'id');
    }

    public function inventoryMeasurements(): HasMany
    {
        return $this->hasMany(InventoryMeasurement::class, 'inventory_session_warehouse_id', 'id');
    }
}
