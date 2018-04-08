<?php
namespace EChat\Router;

interface IRoute {
    public function setRoute( Array $params );
    public function getActionClass();
}