<?php

namespace skyline\tests\mocks;

/**
 *
 */
class Metadata
{
    public function __get($name)
    {
        return null;
    }

    public function __set($name, $value)
    {
        return $this->$name = $value;
    }

    public function hasAttribute($name): bool
    {
        $attributes = array_keys(\get_object_vars($this));
        return in_array($name, $attributes);
    }
}
