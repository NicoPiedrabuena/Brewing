<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrewLog extends Model
{
    protected $fillable = ['batch_id', 'stage', 'occurred_at', 'title', 'value', 'unit', 'temperature', 'gravity', 'ph', 'duration', 'notes'];
    protected $casts = ['occurred_at' => 'datetime', 'temperature' => 'decimal:1', 'gravity' => 'decimal:3', 'ph' => 'decimal:2', 'value' => 'decimal:2'];
    public function batch() { return $this->belongsTo(Batch::class); }
}
