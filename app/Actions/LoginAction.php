<?php
namespace EChat\Actions;
use \EChat\Helpers\SessionHandler;

class LoginAction extends Action{

    public function run()
    {
        if ( SessionHandler::checkSession('user') ) {
            $this->redirect( $this->UrlBuilder()->doAction('index') );
        } else if (isset($this->params['init_user']) && $this->params['init_user'] ) {
          $this->initUser();
        } else {
            $this->loadTemplate('login');
        }
    }

    public function initUser() {
        if ( isset($_POST['nickname']) && isset($_POST['email']) ) {
            $nickname  = $this->getPost('nickname');
            $email     = $this->getPost('email');

            if ( ! filter_var($email, FILTER_VALIDATE_EMAIL) ) {
                $this->redirect($this->UrlBuilder()->doAction('login', ['error' => 'Email inválido']));
            }

            if ( strlen($nickname) <= 3 ) {
                $this->redirect($this->UrlBuilder()->doAction('login', ['error' => 'Nickname muito curto']));
            }

            $sql = "SELECT * FROM Users WHERE email = :email AND status = 'on' ";
            if ( $this->Db()->fetchRow($sql, [':email' => $email]) ) {
                $this->redirect($this->UrlBuilder()->doAction('login', ['error' => 'E-mail já está em uso']));
            }

            $user_hash = sha1($nickname . time());

            $curr_date = date('Y-m-d H:i:s');
            $data = [
                'user_hash' => $user_hash,
                'name'     => $nickname,
                'email' => $email,
                'gravatar' => $this->Gravatar()->avatar($email),
                'lastactivity' => $curr_date,
                'entered_at' => $curr_date,
                'status' => 'on'
            ];

            if ( $this->Db()->insert('Users', $data) ) {
                SessionHandler::createSession('user', $data);
                $this->redirect($this->UrlBuilder()->doAction('index'));
            }


        }
    }
}