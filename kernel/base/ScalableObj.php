<?php
/**
 * An object of this class can be providing by some providers objects.
 * When user calls any method, method '__call' calling.
 * In this method in cycle 'foreach' calls all providers and then calls the base object if it's accessible.
 */


namespace kernel\base;


class ScalableObj
{
    /** @var $obj BaseObj|null */
    private $obj;
    /** @var $providers Provider[] */
    private $providers;

    public function __construct(BaseObj $obj)
    {
        $this->obj = $obj;
    }

    public function __call($name, $arguments)
    {
        $access = true;

        foreach ($this->providers as $provider)
        {
            if ($provider->methods == "*" || in_array($name, $provider->methods))
            {
                if (!$provider->getMethodAccess($name))
                    $access = false;

                $provider->methodProvide($name, $arguments);
            }
        }

        return ($access ? $this->obj->$name(...$arguments) : $access);
    }

    public function __get($name)
    {
        foreach ($this->providers as $provider)
        {
            if ($provider->vars == "*" || in_array($name, $provider->vars))
            {
                if (!$provider->getVarGetAccess($name))
                    $access = false;

                $provider->varGetProvide($name);
            }
        }

        return $this->obj->$name;
    }

    public function __set($name, $value)
    {
        foreach ($this->providers as $provider)
        {
            if ($provider->vars == "*" || in_array($name, $provider->vars))
            {
                if (!$provider->getVarSetAccess($name))
                    $access = false;

                $provider->varSetProvide($name, $value);
            }
        }

        $this->obj->$name = $value;
    }

    public function __invoke(...$args)
    {
        return $this->__call("__invoke", $args);
    }

    public function addProvider(Provider $provider)
    {
        if (!isset($this->providers))
            $this->providers = [];

        $id = count($this->providers);
        $provider->setID($id);
        $this->providers[$id] = $provider;
        $provider->on(get_class($this->obj));

        return $id;
    }

    public function removeProvider($id)
    {
        if (!isset($this->providers[$id]))
            return false;

        $this->providers = array_merge(
            array_slice($this->providers, 0, $id),
            array_slice($this->providers, $id + 1)
        );

        return true;
    }
}