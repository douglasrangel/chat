<?php
namespace EChat\Router;

use EChat\Exceptions\RouterException;
use EChat\Helpers\URL\URLBuilder;

class FriendRouter extends Router{

    private $url;
    private $getvar;
    private $url_parts;
    private $action;

    public function __construct($getvar, URLBuilder $URLBuilder) {
        parent::__construct($URLBuilder);

        $this->getvar = $getvar;
        $this->url = filter_input(INPUT_GET, $getvar);

        $this->action = '';
        $this->url_parts = [];

        if ( ! empty($this->url) ) {
            if ( $this->url[strlen($this->url)-1] == '/' ) {
                $this->url = substr($this->url, 0, -1);
            }

            $url_parts = explode('/', $this->url);
            $this->action = $url_parts[0];
            $this->url_parts = $url_parts;
        }
    }

    public function checkRoute($action, $route)
    {
        return in_array($action, $route->getActionsKey());
    }

    public function fetchParams()
    {
        $url_parts = $this->url_parts;

        $params = [];
        if ( ! empty($url_parts) ) {
            if ( (count($url_parts) - 1) % 2 != 0) {
                throw new RouterException("URL Mal Formada");
            }

            for($i = 1; $i < count($url_parts); $i+=2) {
                $params[$url_parts[$i]] = filter_var($url_parts[$i+1], FILTER_SANITIZE_STRING);
            }
        }

        return $params;
    }

    public function getAction()
    {
        return $this->action;
    }
}