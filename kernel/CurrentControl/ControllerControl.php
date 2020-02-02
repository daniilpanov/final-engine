<?php


namespace kernel\CurrentControl;


use kernel\base\BaseObj;

class ControllerControl extends BaseObj
{
    private $controllers;

    public function __construct()
    {
        $this->controllers = [];
    }

    public function getController($controller)
    {
        if (!isset($this->controllers[$controller]))
        {
            $namespace = "app\\controllers\\" . $controller;
            $this->controllers[$controller] = new $namespace();
        }

        return $this->controllers[$controller];
    }

    public function makeControllerScalable($controller)
    {
        return $this->controllers[$controller] = $this->getController($controller)->makeScalable();
    }
}