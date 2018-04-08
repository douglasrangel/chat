<?php
namespace EChat\Actions;
use EChat\Helpers\SessionHandler;

class UserAction extends Action{

    private $user_hash;

    public function run()
    {
        $this->user_hash = SessionHandler::selectSession('user')['user_hash'];

        if ( isset($this->params['update_users']) ) {
            $this->updateUsers();
        }


    }

    public function updateLastActivity() {
        $this->Db()->update('Users',['user_hash' => $this->user_hash], ['lastactivity' => date('Y-m-d H:i:s')] );
    }

    public function updateUsers() {
        $this->updateLastActivity();

        $users = $this->Db()->fetchRowMany(
            "SELECT * FROM Users WHERE status = 'on' AND user_hash != :hash ",
            [':hash' => $this->user_hash]
        );

        echo json_encode($users);
        die();
    }
}