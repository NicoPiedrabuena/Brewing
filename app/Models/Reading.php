<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
    protected $fillable = ['batch_id', 'measured_at', 'gravity', 'temperature', 'ph', 'notes'];
    protected $casts = ['measured_at' => 'datetime', 'gravity' => 'decimal:3', 'ph' => 'decimal:2'];
    public function batch() { return $this->belongsTo(Batch::class); }
}
