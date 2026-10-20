<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EventDocument extends Model
{
    protected $fillable = ['cultural_event_id','name','file_path'];
    public function event() { return $this->belongsTo(CulturalEvent::class, 'cultural_event_id'); }
}
