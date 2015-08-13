<?php
namespace Laravolt\Mural;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable = ['body', 'room'];

    public function author()
    {
        return $this->belongsTo(config('auth.model'));
    }

    public function scopeLatest($query, $beforeId = null)
    {
        $query->orderBy('id', 'desc');

        if($beforeId) {
            $query->where('id', '<', $beforeId);
        }

        return $query;
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
}
