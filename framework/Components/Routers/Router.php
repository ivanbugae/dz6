<?php

namespace framework\Components\Routers;

use framework\Components\Interfaces\RouteInterface;


class Router implements RouteInterface
{
    protected $url;
    protected $controllerName;
    protected $method;


    /**
     * @throws \Exception
     */
    public function route(): callable
    {
        $this->getControllerParams();
        $method = $this->method;
        $controller = new $this->controllerName();
        return [$controller, $method];
    }

    private function getControllerParams()
    {
        $this->url = parse_url($_SERVER['REQUEST_URI']);
        $this->url = explode('/', $this->url['path']);
        $controller = ucfirst($this->url[1]);

        if(file_exists(__DIR__ . "/ControllersConfig.php")){
            $config = include __DIR__ . '/ControllersConfig.php';
            if(array_key_exists($controller, $config)) {
                $this->setController($config[$controller]['controller'], $config[$controller]['method']);
            } else {
                $this->setController($config['NotFound']['controller'], $config['NotFound']['method']);
            }
        } else{
             $controllersDir = scandir($_SERVER['DOCUMENT_ROOT']."/../app/Controllers");
             if($controller == ''){
                 $controller = 'index';
                 $this->url[2] = 'main';
             }
             if(in_array($controller.'.php', $controllersDir) && !empty($this->url[2])){
                 $this->setController($controller, $this->url[2]);
             } else{
                 throw new \Exception("Not Found");
             }
        }
    }
    private function setController(string $controller, $method)
    {
        $this->controllerName = $controller;
        $this->method = $method;
    }
}