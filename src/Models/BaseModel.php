<?php

namespace App\Models;

class BaseModel
{
    /**
     * Convert db column names to model properties 
     */
    public function __set($name, $value) 
    {
        $camelName = lcfirst(str_replace('_', '', $name));

        if (property_exists($this, $camelName)) {
            $this->{$camelName} = $value;
        } else {
            $this->{$name} = $value;
        }
    }
}