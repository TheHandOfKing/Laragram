<?php
namespace App\Models;

trait LikableTrait 
{
  public function likes()
  {
      return $this->morphToMany(User::class, 'likable');
  }

  public function addLike() {
      return $this->likes()->wherePivot('like', 1);
  }

  public function unlike() {
      return $this->likes()->wherePivot('like', 0);
  }

}