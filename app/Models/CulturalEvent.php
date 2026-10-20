<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CulturalEvent extends Model
{
    protected $fillable = ['agency_id','category_id','title','slug','theme','description','starts_on','ends_on','starts_at','venue','address','province_code','regency_code','district_code','village_code','latitude','longitude','poster','status','review_note','reviewed_by','reviewed_at'];
    protected $casts = ['starts_on'=>'date','ends_on'=>'date','reviewed_at'=>'datetime'];
    public function agency() { return $this->belongsTo(Agency::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function documents() { return $this->hasMany(EventDocument::class); }
    public function galleryItems() { return $this->hasMany(GalleryItem::class); }
    public function scopePublished($query) { return $query->where('status', 'approved')->whereDate('ends_on', '>=', today()); }
}
