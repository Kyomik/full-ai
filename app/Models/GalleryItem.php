<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class GalleryItem extends Model
{
    protected $fillable = ['cultural_event_id','uploaded_by_id','title','file_path','type','is_published'];
    protected $casts = ['is_published' => 'boolean'];
    public function event() { return $this->belongsTo(CulturalEvent::class, 'cultural_event_id'); }
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by_id'); }
}
