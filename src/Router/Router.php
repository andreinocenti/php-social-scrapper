<?php

namespace AndreInocenti\PhpSocialScrapper\Router;

class Router
{

    private $routeMap = [];

    function __construct($routeMap = [])
    {
        $this->routeMap = $routeMap;
    }

    public function set($route, $value)
    {
        $this->routeMap[$route] = $value;
    }

    private static function testMatch($route, $path)
    {
        $route = '#' . $route . '#';
        if (preg_match($route, $path, $matches)) {
            return $matches;
        }
        return false;
    }

    public function match($path)
    {
        foreach ($this->routeMap as $route => $value) {
            if ($matches = self::testMatch($route, $path)) {
                return ['value' => $value, 'matches' => $matches, 'path' => $path];
            }
        }
        return null;
    }
}
