<?php
/**
 * This class is base.
 * It's just have some helpful methods and props.
 */


namespace kernel\base;


class BaseObj
{
    public function makeScalable()
    {
        return new ScalableObj($this);
    }
}