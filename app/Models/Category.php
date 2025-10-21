<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
 
    public $timestamps = false;
 
    protected $fillable = [
        'name'
    ];
 
    public function books()
    {
        return $this->hasMany(Book::class, 'category_id');
    }
 
    protected static function booted()
    {
        static::deleting(function ($category) {
            $category->books()->delete();
        });
    }}
