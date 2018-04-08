<?php
namespace EChat\Router;
use EChat\Exceptions\RouteException;

class Route implements IRoute {
    private $actionsKey;
    private $actionClass;

    public function __construct(Array $params) {
        if ( empty($params) )
            throw new RouteException("Rota Inválida");

        $this->setRoute($params);
    }

    public function setRoute(Array $params)
    {
        if ( !isset($params['match']) || !isset($params['action']) ) {
            throw new RouteException("Faltando parâmetros para a rota");
        }

        if ( ! is_array($params['match']) ) {
            throw new RouteException("match deve ser um array");
        }

        $this->actionsKey = $params['match'];
        $className = '\\EChat\\Actions\\' . ucfirst($params['action']) . 'Action';

        if ( ! class_exists($className) ) {
            throw new RouteException("Classe $className não existe");
        }

        $this->actionClass = $className;
    }

    public function getActionClass()
    {
        return $this->actionClass;
    }

    public function getActionsKey() {
        return $this->actionsKey;
    }
}