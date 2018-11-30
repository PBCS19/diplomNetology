<?php

namespace Engine\Core\Router;

class Router {
    
    public function run()
    {
        $controller = 'Main';
        $action = 'index';
        
        $routes = explode('/', $_SERVER['REQUEST_URI']);
        
        if (!empty($routes[1]))
        {
            $controller = $routes[1];
        }
        
        if (!empty($routes[2]))
        {
            $action = $routes[2];
        }
        
        $controllerName = ucfirst(strtolower($controller)) . 'Controller';
        $actionName = $action;
        
        $controllerPath = '../engine/Controller/' . $controllerName . '.php';
        if (file_exists($controllerPath))
        {
            session_start();
            $controller = $this->getController($controllerName);
            $controller->$action();
        } else {
            //404
        }
    }
    
    private function getController($controllerName)
    {
        $controllerName = 'Engine\Controller\\' . $controllerName;
        $controller = new $controllerName();
        return $controller;
    }
    
}
