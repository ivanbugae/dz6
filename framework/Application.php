<?php

namespace framework;

use app\Controllers\Page;
use Exception;

class Application
{
    protected static $instance;
    protected array $components = [];
    protected $parameters;
    /**
     * @param array|null $components array of objects, must include external components,
     *                               must be associated array (Ex. 'router'=>$router),
     *                               overwrite exists components
     * @throws Exception
     */
    protected function __construct(array $components = null)
    {
        $this->componentsLoader($components);
        $page = $this->components['router']->route();
        $this->getParams();
        $this->route($page);
    }

    /**
     * @throws \ReflectionException
     */
    protected function route(callable $action)
    {
        $route = new \ReflectionMethod($action[0],$action[1]);
        $arguments = [];
        foreach ($route->getParameters() as $parameter){
            $name = $parameter->getName();
            if(array_key_exists($name,$this->parameters)){
                $arguments[$name] = $this->parameters[$name];
            }
        }
        $route->invokeArgs($action[0],$arguments);
    }
    /**
     * Singelton tempalte test
     * @param array|null $components array of objects, must include external components,
     *                               must be associated array (Ex. 'router'=>$router),
     *                               overwrite exists components
     * @return object
     * @throws Exception
     */
    public static function run(array $components = null): object
    {
        if (self::$instance === null){
            self::$instance = new self($components);
        }
        return self::$instance;
    }
    /**
     * @throws Exception array must include objects and be assoc (Ex. 'router'=>$router)
     */
    protected function componentsLoader($components = null)
    {
        $configArray = require_once __DIR__ . '/Components/ComponentsConfig.php';
        foreach ($configArray as $key=>$configClass) {
            $this->components[$key] = new $configClass();
        }
        if(!empty($components)){
            foreach ($components as $key=>$component){
                if(is_object($component) && is_string($key)) {
                    $this->components[$key] = $component;
                } else {
                    throw new Exception("Not object provided, instance of array must be an object or not assoc array");
                }
            }
        }
    }
    protected function getParams()
    {
        $explode = explode('/', $_SERVER['REQUEST_URI']);
        $parametersArray = array_splice($explode,2);
        $key = [];
        $parameters = [];
        $switch = 0;
        foreach ($parametersArray as $item){
            if(!$switch){
                $switch = 1;
                $key[] = $item;
            }else{
                $switch = 0;
                $parameters[] = $item;
            }
        }
        $this->parameters = array_combine($key,$parameters);
    }
}