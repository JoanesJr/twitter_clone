<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action {

    public function auth() {
        $user = Container::getModel('Usuario');
        $user->__set('email', $_POST['email']);
        $user->__set('password', md5($_POST['password']));

        $user->auth();

        if (!empty($user->__get('id')) && !empty($user->__get('email'))) {
            session_start();
            $_SESSION['id'] = $user->__get('id');
            $_SESSION['name'] = $user->__get('name');
            header('Location: /timeline');
        } else {
            header('Location: /?login=erro');
        }
    }
}

?>