<?php

use Symfony\Component\HttpFoundation\Response as BaseResponse;

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

if (!function_exists('response_json')) {
    function response_json($data, $code = 200): BaseResponse
    {
        $data = json_encode(['data' => $data]);

        return new BaseResponse($data, 400 );
    }
}

if (!function_exists('get_content')) {
    function get_content($view, $viewPath = 'app/views') {
        $file = dirname(__DIR__).'/'.$viewPath.'/'.$view;
        if (file_exists($file)) {
            ob_start();
            include $file;
            return ob_get_clean();
        }
    }
}