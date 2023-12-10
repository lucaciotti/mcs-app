<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class, 'warehouse_id', 'id');
    }

    public function stocks_archive(): HasMany
    {
        return $this->hasMany(ProductStockArchive::class, 'warehouse_id', 'id');
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
