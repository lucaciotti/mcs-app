<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Warehouse extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function ubications(): HasMany
    {
        return $this->hasMany(Ubication::class,'warehouse_id', 'id');
    }

    public function inventoryMeasurement(): HasMany
    {
        return $this->hasMany(InventoryMeasurement::class, 'warehouse_id', 'id');
    }

    public function inventorySessionWarehouse(): HasMany
    {
        return $this->hasMany(InventorySessionWarehouse::class, 'warehouse_id', 'id');
    }
}
