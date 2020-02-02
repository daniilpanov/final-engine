<?php


namespace app\models;


use kernel\base\Model;

class Page extends Model
{
    public $id,
        $parent_id,
        $content,
        $language,
        $description,
        $keywords,
        $title,
        $on_other_lang,
        $visible;
}