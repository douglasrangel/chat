<?php
namespace EChat\Helpers\URL;


class FriendURLBuilder extends URLBuilder {

    public function doAction($action, Array $params = [])
    {
        $urlaction = ROOT_URL . "{$action}/";

        if ( ! empty($params) ) {
            $params_key_value = [];

            array_walk($params, function($value, $key) use (&$params_key_value) {
                $params_key_value[] = $key;
                $params_key_value[] = urlencode($value);
            });

            $urlaction .= implode('/', $params_key_value);
        }

        return $urlaction;
    }
}