<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable;

    use InteractsWithMedia;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $appends = [
        'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function getAvatarAttribute() 
    {
        return $this->avatar();
    }

    public function avatar() {
        $mediaItems = $this->getMedia('profileImages');

        if(count($mediaItems) > 0) {
            $url = $mediaItems[0]->getFullUrl();
        } else {
            $url = env('APP_URL') . '/public/storage/notsetProfile.png';
        }


        return $url;
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function comments() 
    {
        return $this->hasMany(Comment::class);
    }

    public function favorites() 
    {
        return $this->belongsToMany(Post::class, 'favorites')->withTimestamps(); //, 'user_id', 'question_id');
    }

    public function likePosts()
    {
        return $this->morphedByMany(Post::class, 'likable');
    }

    public function likeComments()
    {
        return $this->morphedByMany(Comment::class, 'likable');
    }

    public function likePost(Post $post, $like) 
    {
        $likePosts = $this->likePosts();

        return $this->_like($likePosts, $post, $like);
    }

    public function likeComment(Comment $comment, $like)
    {
        $likeComments = $this->likeComments();

        return $this->_like($likeComments, $comment, $like);
    }

    private function _like($relationship, $model, $like) 
    {
        if($relationship->where('likable_id', $model->id)->exists()) {
            $relationship->updateExistingPivot($model, ['like' => $like]);
        } else {
            $relationship->attach($model, ['like' => $like]);
        }

        $model->load('likes');
        $unlike = (int) $model->unlike()->sum('like');
        $addLike = (int) $model->addLike()->sum('like');

        $model->likes_count = $addLike + $unlike;
        $model->save();
        
        return $model->likes_count;
    }
}
