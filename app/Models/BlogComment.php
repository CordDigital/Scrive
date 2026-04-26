<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    protected $fillable = ['blog_id', 'name', 'email', 'message', 'rating', 'is_approved'];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
