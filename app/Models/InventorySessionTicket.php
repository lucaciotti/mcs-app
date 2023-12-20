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

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $dates = ['date_printed'];


    public function inventorySessionWarehouse(): HasOne
    {
        return $this->hasOne(InventorySessionWarehouse::class, 'id', 'inventory_session_warehouse_id');
    }

    public function inventoryMeasurement(): HasOne
    {
        return $this->hasOne(InventoryMeasurement::class, 'inventory_ticket_id', 'id');
    }
}
