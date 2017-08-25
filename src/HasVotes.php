<?php
namespace JrMiranda\Votable;

use App\Vote;

trait HasVotes {

  /**
  * Return users who voted
  *
  * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
  */
  public function voters() {
    return $this->morphToMany($this->voter, 'votable', 'votes');
  }

  /**
  * Check if a item has an upvoted from user
  *
  * @param \Illuminate\Database\Eloquent\Model $votable
  *
  * @return boolean
  */
  public function upVotedBy($user) {
    return $this->votedBy($user, 'upvote');
  }

  /**
  * Check if a item has an downvoted from user
  *
  * @param \Illuminate\Database\Eloquent\Model $votable
  *
  * @return boolean
  */
  public function downVotedBy($user) {
    return $this->votedBy($user, 'downvote');
  }

  /**
  * Check if a item has an voted from user
  *
  * @param \Illuminate\Database\Eloquent\Model $user
  * @param string $type
  *
  * @return boolean
  */
  public function votedBy($user, $type = null) {
    $vote = $this->voters();

    if(!is_null($type)) $vote->wherePivot('type', $type);

    return $vote->get()->contains($user);
  }
}
