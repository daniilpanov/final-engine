<?php


namespace kernel;


use kernel\CurrentControl\ControllerControl;

class Kernel
{
    private static $controllers_control = null;

    public static function getControllersControl()
    {
        return (self::$controllers_control === null
            ? (self::$controllers_control = new ControllerControl())
            : self::$controllers_control
        );
    }
}