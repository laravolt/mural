<?php
namespace Laravolt\Mural;

class Factory
{
    public static function create($id, $class)
    {
        return with(new $class)->findOrFail($id);
    }

}
