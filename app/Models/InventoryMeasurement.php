<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

class InventoryMeasurement extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;


    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function warehouse(): HasOne
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    public function inventorySession(): HasOne
    {
        return $this->hasOne(InventorySession::class, 'id', 'inventory_session_id');
    }

    public function inventorySessionWarehouse(): HasOne
    {
        return $this->hasOne(InventorySessionWarehouse::class, 'id', 'inventory_session_warehouse_id');
    }

    public function inventorySessionTicket(): HasOne
    {
        return $this->hasOne(InventorySessionTicket::class, 'id', 'inventory_ticket_id');
    }

    // public function inventorySession(): HasOneThrough
    // {
    //     return $this->hasOneThrough(PlanType::class, PlanImportType::class, 'id', 'id', 'import_type_id', 'type_id');
    // }

    // public function planfiletemptasks(): HasMany
    // {
    //     return $this->hasMany(PlanFilesTempTask::class, 'import_file_id', 'id');
    // }
}
