<?php
namespace Laravolt\Mural;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{

    use SoftDeletes;

    protected $with = ['author'];
    protected $fillable = ['author_id', 'commentable_id', 'commentable_type', 'body', 'room'];

    public function author()
    {
        return $this->belongsTo(config('mural.default_commentator'));
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('id', 'desc');
    }

    public function scopeBeforeId($query, $beforeId)
    {
        return $query->where('id', '<', $beforeId);
    }

    public function scopeRoom($query, $room)
    {
        if($room) {
            $query->whereRoom($room);
        } else {
            $query->whereNull('room');
        }

        return $query;
    }

    public function scopeSiblingsAndSelf($query)
    {
        return $query->room($this->room)->where('commentable_id', $this->commentable_id)->where('commentable_type', $this->commentable_type);
    }
}
