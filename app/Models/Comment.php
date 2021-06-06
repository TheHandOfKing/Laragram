<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    use HasFactory;

    use LikableTrait;
    protected $fillable = [
        'body',
        'user_id'
    ];

    protected $appends = ['created_date', 'body_html', 'is_pinned', 'is_liked'];

    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getBodyHtmlAttribute() 
    {
        return filter_var(\Parsedown::instance()->text($this->body), FILTER_SANITIZE_STRING);
    }

    public function getCreatedDateAttribute() 
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute() 
    {
        return $this->isPinned() ? 'comment-pinned' : '';
    }

    public function getIsPinnedAttribute() {
        return $this->isPinned();
    }

    public function isPinned() {
        return $this->id == $this->post->pinned_comment_id;
    }

    public function isLiked()
    {
        $likes = DB::table('likables')->where('user_id', auth()->id())->where('likable_id', $this->id)->where('likable_type', 'App\Models\Comment')->where('like', '1')->count() > 0;
        return $likes;
  
        // return $this->favorites->where('user_id', Auth::id())->count() > 0;
    }
  
    public function getIsLikedAttribute() 
    {
        return $this->isLiked();
    }

    public static function boot() 
    {
        parent::boot();

        static::created(function($comment) {
            $comment->post->increment('comments_count');
        });

        static::deleted(function($comment){
            $comment->post->decrement('comments_count');
        });
    }
}
