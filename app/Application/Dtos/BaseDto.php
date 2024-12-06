<?php

namespace App\Application\Dtos;

abstract class BaseDto
{
    public function attachValues(array $values)
    {
        foreach ($values as $attribute => $value) {
            if (property_exists($this, $attribute)) {
                $this->$attribute = $value;
            }
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
