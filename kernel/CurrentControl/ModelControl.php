<?php


namespace kernel\CurrentControl;


use kernel\base\BaseObj;
use kernel\base\GroupModel;

class ModelControl extends BaseObj
{
    /** @var $groups GroupModel[]|null */
    private $groups;

    public function __construct()
    {
        $this->groups = [];
        $this->groups['default'] = new GroupModel("default");
    }

    public function createModel($model_name, $params = [], $register = true)
    {
        $model = "app\\models\\$model_name";
        $instance = new $model(...$params);

        return ($register
            ? $this->groups['default']
                ->add($model_name, $instance, count($this->groups['default']->models))
            : $instance);
    }

    public function createModelIfNotExists($model_name, $params = [], $group = 'default', $register = true)
    {
        if (!$model_obj = $this->searchModel($model_name, $params, $group, true))
            $model_obj = $this->createModel($model_name, $params, $register);

        return $model_obj;
    }

    public function searchModel($model_name, $params = [], $group = 'default', $only_one = false)
    {
        return (isset($this->groups[$group])
            ? $this->groups[$group]->search($params, $model_name, $only_one)
            : false);
    }

    public function getGroup($name = 'default')
    {
        return (isset($this->groups[$name]) ? $this->groups[$name] : null);
    }

    public function setGroup(GroupModel $group)
    {
        $this->groups[$group->id] = $group;
    }
}