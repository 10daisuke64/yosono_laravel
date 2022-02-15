<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
    
    public static function getAllOrderByCreated_at()
    {
        return self::orderBy('created_at', 'desc')->get();
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
    
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
