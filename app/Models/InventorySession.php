<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class InventorySession extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function inventoryMeasurements(): HasMany
    {
        return $this->hasMany(InventoryMeasurement::class, 'inventory_session_id', 'id');
    }

    public function inventorySessionWarehouses(): HasMany
    {
        return $this->hasMany(InventorySessionWarehouse::class, 'inventory_session_id', 'id');
    }
}
