<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use LikableTrait;
    use InteractsWithMedia;
    
    protected $fillable = [
        'name',
        'location',
        'description'
    ];

    protected $appends = ['trimmed_name', 'created_date', 'is_favorited', 'favorites_count', 'body_html', 'is_liked', 'images'];

    public function images() {
        $mediaItems = $this->getMedia();

        if(count($mediaItems) > 0) {
            $images = $mediaItems[0]->getUrl();
        } else {
            $images = env('APP_URL') . '/public/storage/notset.jpg';
        }

        return $images;
    }

    public function getImagesAttribute() {
        return $this->images();
    }


    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function setNameAttribute($value) 
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = $this->str_slug($value);
    }

    private function str_slug($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public function getUrlAdminAttribute() 
    {
        return route('posts.show', $this->slug);
    }

    public function getUrlAttribute() 
    {
        return route('posts.show', $this->slug);
    }

    public function getTrimmedNameAttribute() {
        $string = substr($this->name, 0, 25);

        return $string . '...';
    }

    public function getCreatedDateAttribute() 
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute() 
    {
        if ($this->comments_count > 0) 
        {
           if($this->pinned_comment_id) 
           {
                return "comment-pinned";
           }
        }
    }

    public function getBodyHtmlAttribute() 
    {
        return strip_tags($this->bodyHtml());
    }

    public function comments() {
        return $this->hasMany(Comment::class)->orderBy('likes_count', 'DESC');
    }

    public function pinComment(Comment $comment) {
        $this->pinned_comment_id = $comment->id;

        $this->save();
    }

    public function favorites() {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    //For more info kruks40 has how this works, fun to talk to him

    public function isFavorited()
    {
        $favorites = DB::table('favorites')->where('user_id', auth()->id())->where('post_id', $this->id)->count() > 0;
        return $favorites;

        // return $this->favorites->where('user_id', Auth::id())->count() > 0;
    }

    public function getIsFavoritedAttribute() 
    {
        return $this->isFavorited();
    }

    public function isLiked()
    {
        $likes = DB::table('likables')->where('user_id', auth()->id())->where('likable_id', $this->id)->where('likable_type', 'App\Models\Post')->where('like', '1')->count() > 0;
        return $likes;
  
        // return $this->favorites->where('user_id', Auth::id())->count() > 0;
    }
  
    public function getIsLikedAttribute() 
    {
        return $this->isLiked();
    }

    public function getFavoritesCountAttribute() 
    {
        return $this->favorites->count();
    }

    public function getExcerptAttribute()
    {
        return $this->excerpt(250);
    }

    public function excerpt($len)
    {
        return Str::limit(strip_tags($this->bodyHtml()), $len);
    }

    private function bodyHtml()
    {
        return \Parsedown::instance()->text($this->description);
    }
}
