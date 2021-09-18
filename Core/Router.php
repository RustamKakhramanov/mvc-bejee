<?php

namespace Core;

/**
 * @method post($route, $params)
 * @method get($route, $params)
 * @method put($route, $params)
 * @method path($route, $params)
 */
class Router
{
    const REQUEST_METHODS = [
        'post',
        'get',
        'put',
        'path',
        'delete'
    ];

    protected $routes = [];

    protected $params = [];

    public function checkRequestMethod($controller) {
     // $_SERVER['REQUEST_METHOD']
    }

    public function add($route, $params = [])
    {
        $route = preg_replace('/\//', '\\/', $route);

        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    /**
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    public function matchRequest() {

    }

    public function match($url)
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                // Get named capture group values
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;
                return true;
            }
        }

        return false;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function dispatch($url)
    {
        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url)) {
            $controller = $this->params['controller'];

            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller;

            if (class_exists($controller)) {
                $controller_object = new $controller($this->params);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);



                if (preg_match('/action$/i', $action) == 0) {

                    if (isset($this->params['method']) && strtoupper($this->params['method']) !== $_SERVER['REQUEST_METHOD']) {
                        throw new \Exception("Method $action is not supported method ".$_SERVER['REQUEST_METHOD']);
                    }

                    $controller_object->$action();
                } else {
                    throw new \Exception("Method $action in controller $controller cannot be called directly - remove the Action suffix to call this method");
                }
            } else {
                throw new \Exception("Controller class $controller not found");
            }
        } else {
            throw new \Exception('No route matched.', 404);
        }
    }
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    protected function removeQueryStringVariables($url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }

    protected function getNamespace()
    {
        $namespace = 'App\Controllers\\';

        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }

        return $namespace;
    }

    public function __call($name, $arguments)
    {

        if (!in_array($name, self::REQUEST_METHODS)) {
            throw new \Exception("Method $name not found");
        }

        $arguments[1]['method'] = $name;
        $this->add($arguments[0], $arguments[1]);
    }
}
