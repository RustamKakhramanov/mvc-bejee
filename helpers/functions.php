<?php

if (!function_exists('config')) {
    function config($name) {
        $file = dirname(__DIR__)."/configs/{$name}.php";

        return file_exists($file) ? include_once($file): [];
    }
}

if (!function_exists('env')) {
    function env($name, $default = null) {
        $env_file = dirname(__DIR__).'/.env';
        $env = file_exists($env_file) ? file_get_contents(dirname(__DIR__).'/.env') : false;

        if (!$env) {
            return $default;
        }

        $env_arr = explode(PHP_EOL, $env);
        $env_result = '';

        array_walk($env_arr, function ($item) use (&$env_result, $name) {
            $arr = explode('=', $item);
            if ($arr[0] === $name) {
                $env_result = trim($arr[1]);
            }
        });

        return $env_result ? : $default;
    }
}