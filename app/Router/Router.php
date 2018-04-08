<?php
namespace EChat\Router;
use EChat\Exceptions\RouterException;
use EChat\Helpers\URL\URLBuilder;

abstract class Router {
    protected $routes;
    protected $URLBuilder;

    public function __construct(URLBuilder $URLBuilder) {
        $this->routes = new \SplObjectStorage();
        $this->URLBuilder = $URLBuilder;
        $this->URLBuilder->setRouter($this);
    }

    public function addRoute(IRoute $route) {
        $this->routes->attach($route);
    }

    public function removeRoute(IRoute $route) {
        $this->routes->detach($route);
    }

    public function getUrlBuilder() {
        return $this->URLBuilder;
    }

    abstract public function checkRoute($action, $route);

    abstract public function fetchParams();

    abstract public function getAction();

    public function dispatch() {
        foreach($this->routes as $route) {
            if ( $this->checkRoute($this->getAction(), $route) ) {
                $params = $this->fetchParams();
                $class  = $route->getActionClass();
                $instance = new $class;
                $instance->setParams($params);
                $instance->run();
                return;
            }
        }

        throw new RouterException("Nenhuma rota definida");
    }
}