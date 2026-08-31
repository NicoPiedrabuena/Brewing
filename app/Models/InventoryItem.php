<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class InventoryItem extends Model {
    protected $fillable=['name','category','quantity','unit','lot','expires_at','minimum_stock','notes'];
    protected $casts=['quantity'=>'decimal:2','minimum_stock'=>'decimal:2','expires_at'=>'date'];
    public function getLowStockAttribute(): bool { return (float)$this->quantity <= (float)($this->minimum_stock ?? 0); }
}
