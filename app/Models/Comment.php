<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $primaryKey = 'comment_id';
    
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'post_id',
        'content',
        'parent_id', 
    ];

    public function user()
    {
        return $this->belongsTo(UserRegistration::class, 'user_id', 'user_id');
    }

    public function post()
    {
        return $this->belongsTo(UserPost::class, 'post_id', 'user_post_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    
}
