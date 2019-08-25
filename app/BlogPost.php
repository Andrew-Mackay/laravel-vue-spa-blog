<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
      "title", "summary", "content"
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
