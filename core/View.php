<?php

namespace Core;

class View
{

    public static function render($view, $args = [], $layout = 'base.php')
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/app/Views/$layout";

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("Layout not found");
        }
    }
}
