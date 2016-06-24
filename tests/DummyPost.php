<?php
namespace Laravolt\Mural\Test;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Mural\CommentableTrait;

class DummyPost extends Model
{
	use CommentableTrait;

	protected $fillable = ['content'];
}