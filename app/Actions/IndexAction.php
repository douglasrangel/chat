<?php
namespace EChat\Actions;
use \EChat\Helpers\SessionHandler;

class IndexAction extends Action {

    public function run()
    {
        if ( SessionHandler::checkSession('user') ) {
            $this->loadChat();
        } else {
            $this->redirect( $this->UrlBuilder()->doAction('login') );
        }

    }

    public function loadChat() {
        $user_session = SessionHandler::selectSession('user');

        $users = $this->Db()->fetchRowMany(
                    "SELECT * FROM Users WHERE status='on' AND user_hash != :hash",
                    [':hash' => $user_session['user_hash']]
        );

        $sql = "(SELECT * FROM Users,Chat WHERE Users.user_hash = Chat.user_hash_from ORDER BY date DESC LIMIT 30) ORDER by date ASC";
        $messages = $this->Db()->fetchRowMany($sql);
        $template_data = [
            'user_session' => $user_session,
            'users' => $users,
            'messages'=> $messages
        ];

        $this->loadTemplate('index', $template_data);
    }
}