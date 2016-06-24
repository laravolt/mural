<?php
namespace Laravolt\Mural\Test;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravolt\Mural\Contracts\Commentator;

class DummyUser extends Authenticatable implements Commentator
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getCommentatorNameAttribute()
    {
        return $this->name;
    }

    public function getCommentatorAvatarAttribute()
    {
        return null;
    }

    public function getCommentatorPermalinkAttribute()
    {
        return null;
    }

    public function canModerateComment()
    {
        return true;
    }
}
