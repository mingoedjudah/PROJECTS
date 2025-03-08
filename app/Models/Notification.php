<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'user_id',
        'post_id',
        'liked_by',
        'comment_by', 
        'reply_by', 
        'type',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(UserRegistration::class, 'user_id', 'user_id');
    }

    public function post()
    {
        return $this->belongsTo(UserPost::class, 'post_id', 'user_post_id');
    }

    public function likedBy()
    {
        return $this->belongsTo(UserRegistration::class, 'liked_by', 'user_id');
    }

    public function commentBy()
    {
        return $this->belongsTo(UserRegistration::class, 'comment_by', 'user_id');
    }

    public function replyBy()
    {
        return $this->belongsTo(UserRegistration::class, 'reply_by', 'user_id');
    }
}
