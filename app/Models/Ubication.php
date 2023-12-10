<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

class Ubication extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function warehouse(): HasOne
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class, 'ubic_id', 'id');
    }

    public function stocks_archive(): HasMany
    {
        return $this->hasMany(ProductStockArchive::class, 'ubic_id', 'id');
    }

    public function inventoryMeasurement(): HasMany
    {
        return $this->hasMany(InventoryMeasurement::class, 'ubic_id', 'id');
    }

}
