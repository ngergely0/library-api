<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Author extends Model
{
    use HasFactory;

    protected $table = 'authors';
    public $timestamps = false;
   
    protected $fillable = ['name', 'nationality', 'age', 'gender'];
 
    public function books()
    {
        return $this->hasMany(Book::class);
    }
 
     protected static function booted()
    {
        static::deleting(function ($author) {
            $author->books()->delete();
        });
    }}
