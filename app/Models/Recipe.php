<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'style', 'batch_size', 'og', 'fg', 'abv', 'ibu', 'color', 'ingredients', 'process', 'notes',
        'efficiency', 'mash_ph', 'water_profile', 'mash_schedule', 'boil_plan', 'fermentation_plan', 'clarification_plan', 'packaging_plan'];

    protected $casts = ['batch_size' => 'decimal:1', 'og' => 'decimal:3', 'fg' => 'decimal:3', 'abv' => 'decimal:1'];

    public function batches() { return $this->hasMany(Batch::class); }
}
