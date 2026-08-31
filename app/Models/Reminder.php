<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Reminder extends Model {
    protected $fillable=['batch_id','title','due_at','completed','notes'];
    protected $casts=['due_at'=>'datetime','completed'=>'boolean'];
    public function batch(){ return $this->belongsTo(Batch::class); }
}
