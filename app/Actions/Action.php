<?php
namespace EChat\Actions;
use \EChat\Helpers\SessionHandler;

abstract class Action {
    protected $params;
    private $db;
    private $urlbuilder;
    private $gravatar;
    protected $template;

    public function __construct() {
        $this->db = \EChat\Registry::get('appdb');
        $this->urlbuilder = \EChat\Registry::get('approuter')->getUrlBuilder();

        $this->template = new \stdClass();

        $this->gravatar = new \Gravatar\UrlBuilder();
        $this->gravatar->useHttps(true);

        $this->checkOnlineUsers();
        $this->checkActualUser();
    }

    public function Db() {
        return $this->db;
    }

    public function UrlBuilder() {
        return $this->urlbuilder;
    }

    public function Gravatar() {
        return $this->gravatar;
    }

    public function setParams($params = null) {
        $this->params = $params;
    }

    public function redirect($url) {
        header("Location: $url");
        die();
    }

    public function getPost($key) {
        if ( isset($_POST[$key]) )
            return filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);

        return null;
    }
    public function loadTemplate($template, $data = null) {
        $templatePath = TEMPLATES_DIR . '/' . $template . '.phtml';

        if ( file_exists($templatePath) ) {
            if ( $data && ! empty($data) ) {
                foreach($data as $key => $value){
                    $this->template->{$key} = $value;
                }
            }

            include($templatePath);

        }
    }

    public function loadHeader() {
        $this->loadTemplate('layout/header', null);
    }

    public function loadFooter() {
        $this->loadTemplate('layout/footer', null);
    }

    public function checkOnlineUsers() {
        $sql = "UPDATE Users SET status = 'off' WHERE TIME_TO_SEC(TIMEDIFF('" . date('Y-m-d H:i:s') . "', lastactivity)) >= '". IDLE_TIME_SECS . "'";
        $this->Db()->executeSql($sql);
    }

    public function checkActualUser() {
        $user = SessionHandler::selectSession('user');
        if ( $user ) {
            $status = $this->Db()->fetchColumn('SELECT status FROM Users WHERE user_hash = :hash', [':hash' => $user['user_hash']] );

            if ( $status == 'off' || ! $status ){
                SessionHandler::deleteSession('user');
                $this->redirect($this->UrlBuilder()->doAction('login'));
            }
        }
    }
    public abstract function run();
}