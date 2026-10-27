<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public const CATEGORIES = [
        'open_space' => 'Ruang Terbuka',
        'building' => 'Gedung',
        'natural_tourism' => 'Wisata Alam',
        'heritage' => 'Cagar Budaya',
        'venue' => 'Tempat Acara',
        'tourism' => 'Wisata',
    ];

    protected $fillable = ['name', 'type', 'address', 'latitude', 'longitude', 'status'];

    public function events()
    {
        return $this->hasMany(CulturalEvent::class);
    }
}
