<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Agency extends Model
{
    protected $fillable = ['name','type','email','phone','address','logo','status'];
    public function users() { return $this->hasMany(User::class); }
    public function events() { return $this->hasMany(CulturalEvent::class); }
}
