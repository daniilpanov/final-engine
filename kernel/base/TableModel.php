<?php


namespace kernel\base;



abstract class TableModel extends Model
{
    public function fromDB($id)
    {
        /*$this->setData(
            Queries::select()
                ->selectString("*")
                ->from($this->getTable())
                ->where("id", $id)
                ->query(true, false)
        );*/
    }

    abstract public function getTable();
}