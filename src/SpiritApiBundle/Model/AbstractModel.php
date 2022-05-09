<?php

namespace Edcoms\SpiritApiBundle\Model;

/**
 * Abstract class which includes the magic methods of getting and setting class properties.
 * Beware, all properties that are intended to be set by the mapping functionality
 * must have the 'public' scope set against it.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class AbstractModel
{
    /**
     * Handles the getter and setter method calls of managing properties.
     */
    public function __call(string $name, array $arguments)
    {
        if (preg_match('/(g|s)et([A-Z][A-Za-z0-9_]+)/', $name, $matches) !== 1) {
            if (!method_exists($this, $name)) {
                throw new \Exception("The method '$name' does not exist.");
            }

            return call_user_func_array([$this, $name], $arguments);
        }

        $property = lcfirst($matches[2]);

        if (!property_exists($this, $property)) {
            throw new \Exception("The property '$property' does not exist.");
        }

        if ($matches[1] === 's') {
            $this->{$property} = $arguments[0];

            return $this;
        }

        return $this->{$property};
    }

    /**
     * @return  string[]  Collection of all public properties within the calling class.
     */
    public static function getPublicProperties()
    {
        return (new \ReflectionClass(get_called_class()))->getProperties(\ReflectionProperty::IS_PUBLIC);
    }
}
