<?php

namespace Engine\Core\Router;

use Engine\Core\Response\Response;

class Router {
    
    public function run()
    {
        $controller = 'main';
        $action = 'index';
        
        $uri = strlen($_SERVER['REQUEST_URI']) > 1 ? 
                rtrim($_SERVER['REQUEST_URI'], '/') : $_SERVER['REQUEST_URI'];
        if ($uri !== $_SERVER['REQUEST_URI']) {
            Response::redirect($uri);
        }
        
        $routes = explode('/', $uri);
        
        if (!empty($routes[1])) {
            $controller = $routes[1];
        }
        
        if (!empty($routes[2])) {
            $action = $routes[2];
        }
        
        $controllerName = ucfirst(strtolower($controller)) . 'Controller';
        
        $controllerPath = '../engine/Controller/' . $controllerName . '.php';
        if (file_exists($controllerPath)) {
            session_start();
            $controller = $this->getController($controllerName);
            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {
                //404
                http_response_code(404);
                exit('error 404');
            }
        } else {
            //404
            http_response_code(404);
            exit('error 404');
        }
    }
    
    private function getController($controllerName)
    {
        $controllerName = 'Engine\Controller\\' . $controllerName;
        $controller = new $controllerName();
        return $controller;
    }
    
}
