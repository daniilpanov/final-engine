<?php


namespace app\providers;


use kernel\base\Provider;

class User extends Provider
{
    private $class;

    public function on($class)
    {
        $this->class = $class;
    }

    public function getMethodAccess($name)
    {

    }

    public function getVarGetAccess($name)
    {

    }

    public function getVarSetAccess($name)
    {

    }

    public function methodProvide($name, &$arguments)
    {
    }

    public function varGetProvide($name)
    {
    }

    public function varSetProvide($name, &$value)
    {
    }
}