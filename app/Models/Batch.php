<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = ['recipe_id', 'code', 'brewed_at', 'status', 'volume', 'og', 'fg', 'temperature', 'packaged_at', 'rating', 'notes',
        'brewhouse_efficiency', 'pre_boil_volume', 'pre_boil_gravity', 'post_boil_volume', 'pitch_temperature', 'yeast', 'packaged_volume', 'carbonation'];
    protected $casts = ['brewed_at' => 'date', 'packaged_at' => 'date', 'og' => 'decimal:3', 'fg' => 'decimal:3'];

    public function recipe() { return $this->belongsTo(Recipe::class); }
    public function readings() { return $this->hasMany(Reading::class)->orderByDesc('measured_at'); }
    public function logs() { return $this->hasMany(BrewLog::class)->orderBy('occurred_at'); }
    public function getAbvAttribute(): ?float { return $this->og && $this->fg ? round(($this->og - $this->fg) * 131.25, 1) : null; }
}
