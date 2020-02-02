<?php


namespace kernel\base;


class GroupModel extends Model
{
    public $id;
    public $models = [];
    public $strict_classname = null;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param string|null $strict_classname
     * @return bool
     */
    public function setStrictClassname($strict_classname)
    {
        if ($strict_classname)
        {
            $strict_class = "app\\models\\$strict_classname";

            foreach ($this->models as $model)
            {
                if (get_class($model) !== $strict_class)
                    return false;
            }
        }

        $this->strict_classname = $strict_classname;
        return true;
    }

    /*public static function createGroupFromDB($group, $modelname, $cols = "*", $params = [])
    {
        $group = new self($group);
        $group->strict_classname = $modelname;

        $model = "\\app\\models\\$modelname";
        /** @var $model TableModel !/
        $model = new $model();
        $methods = get_class_methods($model);

        //$sql = "SELECT $cols FROM " . $model->getTable();
        /** @var $sql SelectQueryBuilder !/
        $sql = Queries::select();
        $sql->selectString($cols)->from($model->getTable());

        if (in_array("where", $methods))
        {
            $p = [];

            if (isset($params['where']))
                $p = (is_array($params['where']) ? $params['where'] : [$params['where']]);

            $where = $model->where(...$p);

            if ($where)
            {
                $sql->where($where[0]['col'], $where[0]['val'], null, $where[0]['op']);
                array_shift($where);

                if ($where)
                {
                    foreach ($where as $item)
                    {
                        //$sql .= $item['col'] . $item['op'] . "'" . $item['val'] . "' AND ";
                        $sql->wand($item['col'], $item['val'], $item['op']);
                    }
                }
            }
        }

        if (in_array("order", $methods))
        {
            $p = [];

            if (isset($params['order']))
                $p = (is_array($params['order']) ? $params['order'] : [$params['order']]);

            $order = $model->order(...$p);

            if ($order)
            {
                //$sql .= " ORDER BY " . $order[0] . (isset($order[1]) ? " " . $order[1] : "");
                $sql->order($order[0], (isset($order[1]) ? $order[1] : "ASC"));
            }
        }

        if (in_array("limit", $methods))
        {
            $p = [];

            if (isset($params['limit']))
                $p = (is_array($params['limit']) ? $params['limit'] : [$params['limit']]);

            $limit = $model->limit(...$p);

            if ($limit)
            {
                //$sql .= " LIMIT " . $limit[0] . (isset($limit[1]) ? "," . $limit[1] : "");
                if (isset($limit[1]))
                    $sql->limit($limit[1], $limit[0]);
                else
                    $sql->limit($limit[0]);
            }
        }

        $SQLData = $sql->getSqlInfo();

        $data = DbCommands::query($SQLData['sql'], $SQLData['templates'])->fetchAll();

        if ($data)
        {
            foreach ($data as $datum)
            {
                $tmp_model = (clone $model);
                $tmp_model->setData($datum);

                if (!$group->add($modelname, $tmp_model, null, false))
                    return false;
            }
        }

        return $group;
    }*/

    public function add($key, $model, $id = null, $return_new_model = true)
    {
        if ($this->strict_classname !== null && get_class($model) !== "app\\models\\" . $this->strict_classname)
            return false;

        if ($id === null)
        {
            if (!@$model->id && !is_numeric(@$model->id))
                return false;
            else
                $id = $model->id;
        }

        if (isset($this->models[$key][$id]))
            return false;

        $this->models[$key][$id] = $model;
        return ($return_new_model ? $model : true);
    }

    public function remove($id, $key = null, $return_old_model = false)
    {
        if (!$key)
            $key = $this->strict_classname;

        if (!isset($this->models[$key][$id]))
            return false;

        $old_model = $this->models[$key][$id];
        unset($this->models[$key][$id]);
        return ($return_old_model ? $old_model : true);
    }

    public function model($id, $key, $new_model = null, $return_old_model = true)
    {
        if (!$key)
            $key = $this->strict_classname;

        if (!isset($this->models[$key][$id]))
            return null;

        if ($new_model === null)
        {
            return $this->models[$key][$id];
        }
        else
        {
            $old_model = $this->models[$key][$id];
            $this->models[$key][$id] = $new_model;

            return ($return_old_model ? $old_model : $new_model);
        }
    }

    public function search($params = [], $modelname = null, $only_one = false)
    {
        if (!$modelname)
            $modelname = $this->strict_classname;

        if (!isset($this->models[$modelname]))
            $this->models[$modelname] = [];

        $instances = @$this->models[$modelname];

        if (!$instances)
            return [];

        $inst = [];

        foreach ($instances as $id => $instance)
        {
            $props = get_object_vars($instance);
            $found = true;

            if (!empty($params))
            {
                foreach ($params as $property => $value)
                {
                    if (!isset($props[$property]) || @$instance->$property != $value)
                    {
                        if (($value === null && isset($instance->$property)) || ($value !== null))
                        {
                            $found = false;
                            break;
                        }
                    }
                }
            }

            if ($found)
            {
                if ($only_one)
                    return $instance;

                $inst[$id] = $instance;
            }
        }

        return $inst;
    }
}