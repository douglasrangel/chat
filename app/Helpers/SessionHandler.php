<?php
namespace EChat\Helpers;

class SessionHandler {

    public static function createSession( $name, $value ) {
        $_SESSION[$name] = $value;
    }

    public static function addSessionVar( $topName, $name, $value ) {
        $_SESSION[$topName][$name] = $value;
    }

    public static function selectSession( $name ) {
        if ( self::checkSession($name) ) {
            return $_SESSION[$name];
        }

        return false;
    }

    public static function checkSession( $name ) {
        return isset( $_SESSION[$name] );
    }

    public static function deleteSession( $name ) {
        unset( $_SESSION[$name] );
    }
}