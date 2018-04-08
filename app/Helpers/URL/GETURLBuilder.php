<?php
namespace EChat\Helpers\URL;


class GETURLBuilder extends URLBuilder {

    public function doAction($action, Array $params = [])
    {
        $actionKey = $this->router->getGetVar();

        $urlaction = ROOT_URL . "?{$actionKey}={$action}";

        if ( ! empty($params) ) {
            $params_key_value = [];

            array_walk($params, function($value, $key) use(&$params_key_value) {
                $params_key_value[] = $key . '=' . urlencode($value);
            });

            $urlaction .= '&' . implode('&', $params_key_value);
        }

        return $urlaction;
    }
}