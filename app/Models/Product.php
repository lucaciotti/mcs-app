<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Product extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class, 'product_id', 'id');
    }

    public function stocks_archive(): HasMany
    {
        return $this->hasMany(ProductStockArchive::class, 'product_id', 'id');
    }

    public function inventoryMeasurement(): HasMany
    {
        return $this->hasMany(InventoryMeasurement::class, 'product_id', 'id');
    }
}
