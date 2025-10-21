<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
 
    public $timestamps = false;
 
    protected $fillable = [
        'name',
        'category_id',
        'price',
        'publication_date',
        'edition',
        'author_id',
        'isbn',
        'cover'
    ];
 
    function author()
    {
        return $this->belongsTo(Author::class);
    }
 
    function category() {
        return $this->belongsTo(Category::class);
    }
}
