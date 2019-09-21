<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
      "title", "summary", "content", "header_image_url"
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
