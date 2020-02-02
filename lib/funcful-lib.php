<?php

if (!defined('PHP_HOME'))
    die ("Constant 'PHP_HOME' is not defined!");

/*function echoRatingStars($number, $input_name, $selected = 1)
{
    echo "<div class='rating-stars'>";

    for ($i = 1; $i <= $number; $i++)
    {
        echo "<label class='star"
            . ($i == $selected ? " star-selected" : "")
            . "'><input type='radio' name='$input_name' value='$i'"
            . ($i == $selected ? " checked" : "")
            . "></label>";
    }

    echo "</div>";
}*/

function is_image($filename)
{
    $is = @getimagesize($filename);
    if (!$is)
        return false;
    elseif (!in_array($is[2], [1, 2, 3]))
        return false;
    else
        return true;
}

function is_assoc($array)
{
    foreach ($array as $key => $element)
    {
        if (is_numeric($key))
            return false;
    }

    return true;
}

function require_class($namespace)
{
    load_file(
        str_replace("engine", "kernel", path_convert($namespace, "\\")),
        "ro",
        "Requiring failed: class '$namespace' does not exist"
    );
}

function show_view($view, $type = "action-views")
{
    load_file(
        "views/$type/$view",
        "i",
        "Display failed: view '$view' with type '$type' does not exist"
    );
}

function system_config($file)
{
    load_file(
        "config/$file",
        "ro",
        "System config file init failed: config file '$file' does not exist"
    );
}

function include_lib($libname)
{
    load_file(
        "lib/$libname",
        "io",
        "Lib including failed: library '$libname' does not exist"
    );
}

function load_file($filepath, $loading_type, $exception_string, $type = "php", $timestamp = true)
{
    if ($timestamp)
    {
        $exception_string .= " (" . date("d\\.m\\.Y \\a\\t H\\:i\\:s") . ")";
    }

    $path = path_convert(PHP_HOME . "/$filepath.$type");

    if (!is_file($path))
    {
        file_put_contents(
            path_convert(PHP_HOME . "/files/dumps-and-logs/loading-logs.log"),
            "$exception_string\n",
            FILE_APPEND
        );
    }
    else
    {
        switch ($loading_type)
        {
            case "ro":
                require_once $path;
                break;

            case "r":
                require $path;
                break;

            case "io":
                include_once $path;
                break;

            case "i":
                include $path;
                break;

            default:
                file_put_contents(
                    path_convert(
                        PHP_HOME
                        . "/files/dumps-and-logs/loading-logs.log"
                    ),
                    "Loading error: unknown loading type - '$loading_type' ("
                        . date("d\\.m\\.Y \\a\\t h\\:i\\:s") . ")\n",
                    FILE_APPEND
                );
                break;
        }
    }
}

function path_convert($path, $delimiter = "/", $new_delimiter = DIRECTORY_SEPARATOR)
{
    return str_replace($delimiter, $new_delimiter, $path);
}

function is_v3()
{
    return true;
}