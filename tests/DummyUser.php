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
        'name', 'email', 'password', 'is_admin',
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
    }

    public function getCommentatorPermalinkAttribute()
    {
    }

    public function canModerateComment()
    {
        return $this->is_admin == 1 ? true : false;
    }
}
