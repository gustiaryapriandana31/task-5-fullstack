<?php

namespace App\Models;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $with = ['category', 'user'];

    public function scopeFilter($query, array $filters): void 
    {
        $query->when($filters['category'] ?? false, function($query, $category) {
            return $query->whereHas('category', function($query) use ($category){
                $query->where('id', $category);
            });
        });
        
        $query->when($filters['user'] ?? false, function($query, $user) {
            return $query->whereHas('user', function($query) use ($user){
                $query->where('name', $user);
            });
        });
    }
    
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => asset('/storage/posts/' . $image),
        );
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}