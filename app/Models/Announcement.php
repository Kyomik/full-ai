<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Announcement extends Model
{
    protected $fillable = ['title','body','published_on','is_published'];
    protected $casts = ['published_on'=>'date','is_published'=>'boolean'];
}
