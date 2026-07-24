<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'country_id',
        'title',
        'category',
        'published_date',
        'content',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}