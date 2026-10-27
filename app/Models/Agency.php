<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Agency extends Model
{
    protected $fillable = ['name','type','email','phone','address','province_code','regency_code','district_code','village_code','logo','status'];
    public function users() { return $this->hasMany(User::class); }
    public function events() { return $this->hasMany(CulturalEvent::class); }
}
