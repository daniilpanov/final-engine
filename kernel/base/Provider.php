<?php


namespace kernel\base;


abstract class Provider
{
    public $id;

    public $vars = "*", $methods = "*";

    public function setID(int $id)
    {
        $this->id = $id;
    }

    abstract public function on($class);

    abstract public function getMethodAccess($name);

    abstract public function getVarGetAccess($name);

    abstract public function getVarSetAccess($name);

    abstract public function methodProvide($name, &$arguments);

    abstract public function varGetProvide($name);

    abstract public function varSetProvide($name, &$value);
}