<?php
namespace JrMiranda\Votable;

trait CanVote {
  protected $votable;

  /**
  * Return items voted by user
  *
  * @param \Illuminate\Database\Eloquent\Model $votable
  *
  * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
  */
  public function votedItems($votable = null) {
    $this->setVotable(get_class($votable));
    return $this->morphedByMany($this->votable, 'votable', 'votes');
  }

  public function test($votable, $user) {
    echo config('votable.cache_columns.upvote');
  }

  /**
  * Upvote a item
  *
  * @param \Illuminate\Database\Eloquent\Model $votable
  *
  * @return boolean
  */
  public function upVote($votable) {
    return $this->vote($votable);
  }

  /**
  * Downvote a item
  *
  * @param \Illuminate\Database\Eloquent\Model $votable
  *
  * @return boolean
  */
  public function downVote($votable) {
    return $this->vote($votable, 'downvote');
  }

  /**
  * Vote a item
  *
  * @param \Illuminate\Database\Eloquent\Model $votable
  * @param string $type
  *
  * @return boolean
  */
  public function vote($votable, $type = 'upvote') {
    $this->unVote($votable);

    $item = [
      $votable->id => [
        'type' => $type
      ]
    ];

    $this->votedItems($votable)->attach($item);

    if (config('votable.use_cache')) {
      $this->cacheVote($votable, $type);
    }
  }

  /**
  * Unvote a item
  *
  * @param \Illuminate\Database\Eloquent\Model $votable
  *
  * @return boolean
  */
  public function unVote($votable) {
    $item = $this->votedItems($votable)
      ->withPivot('type')
      ->find($votable->id);

    if (!$item) {
      return false;
    }

    $type = $item->pivot->type;

    $this->votedItems($votable)->detach($votable);

    if (config('votable.use_cache')) {
      $this->cacheVote($votable, $type, true);
    }
  }

  /**
  * Check if a user has upvoted a item
  *
  * @param \Illuminate\Database\Eloquent\Model $votable
  *
  * @return boolean
  */
  public function hasUpVoted($votable) {
    return $this->hasVoted($votable, 'upvote');
  }

  /**
  * Check if a user has downvoted a item
  *
  * @param \Illuminate\Database\Eloquent\Model $votable
  *
  * @return boolean
  */
  public function hasDownVoted($votable) {
    return $this->hasVoted($votable, 'downvote');
  }

  /**
  * Check if a user has voted a item
  *
  * @param \Illuminate\Database\Eloquent\Model $votable
  * @param string $type
  *
  * @return boolean
  */
  public function hasVoted($votable, $type = null) {
    $items = $this->votedItems($votable);

    if(!is_null($type)) $items->wherePivot('type', $type);

    return $items->get()->contains($votable);
  }

  /**
  * Cache a vote in a item
  *
  * @param \Illuminate\Database\Eloquent\Model $votable
  * @param string $type
  * @param boolean $remove
  *
  * @return boolean
  */
  public function cacheVote($votable, $type = 'upvote', $remove = false) {
    $column = config('votable.cache_columns.' . $type);
    $votable->$column += $remove ? -1 : 1;

    return $votable->save();
  }

  /**
  * Set item class
  *
  * @param $class
  *
  * @return boolean
  */
  public function setVotable($class) {
    return $this->votable = $class;
  }
}
