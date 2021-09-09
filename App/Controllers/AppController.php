<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;
use stdClass;

class AppController extends Action {

    public function timeline() {
        $this->validateLogin();

        $tweet = Container::getModel('Tweet');
        $users = Container::getModel('Usuario');

        $users->__set('id', $_SESSION['id']);
        $tweet->__set('id_user', $_SESSION['id']);

        $this->view->name = $users->nameUser();
        $this->view->number_tweets = $users->numberTweets();
        $this->view->number_following = $users->numberFollowing();
        $this->view->number_followers = $users->numberFollowers();

        $this->view->tweets = $tweet->getAll();
        $this->render('timeline');  
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /');
    }

    public function tweet() {
        $this->validateLogin();
        $tweet = Container::getModel('Tweet');
        $tweet->__set('tweet', $_POST['tweet']);
        $tweet->__set('id_user', $_SESSION['id']);

        $tweet->saveTweet();

        header('Location: /timeline');
    }

    public function validateLogin() {
        session_start();
        if (isset($_SESSION['id']) ||isset($_SESSION['name'])) {
            if (empty($_SESSION['id']) || empty($_SESSION['name'])) {
                header('Location: /?login=erro');
            }else {
                return true;
            }
        }else {
            header('Location: /?login=erro');
        }
    }

    public function quemSeguir() {
        $this->validateLogin();
        $tweet = Container::getModel('Tweet');
        $users = Container::getModel('Usuario');
        $users->__set('id', $_SESSION['id']);
        $tweet->__set('id_user', $_SESSION['id']);

        $this->view->name = $users->nameUser();
        $this->view->number_tweets = $users->numberTweets();
        $this->view->number_following = $users->numberFollowing();
        $this->view->number_followers = $users->numberFollowers();
        $users_search = new stdClass();
        $quem_seguir = isset($_GET['pesquisarpor']) ? $_GET['pesquisarpor'] : '';

        if ( $quem_seguir != '') {
            $users = Container::getModel('Usuario');
            $users->__set('name', $quem_seguir);
            $users->__set('id', $_SESSION['id']);
            $users_search = $users->getAll();
        }

        $this->view->users = $users_search;

        if (isset($_GET['procurarpor'])) {
            return true;
        } else {
            $this->render('quemseguir');
        }
        
    }

    public function action() {
        $this->validateLogin();
        $action = isset($_GET['acao']) ? $_GET['acao'] : '';
        $id_user_follow = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';
        $seguidores = Container::getModel('Seguidores');
        $seguidores->__set('id_usuario', $_SESSION['id']);

        switch ($action) {
            case 'seguir':
                $seguidores->seguir($id_user_follow);
                break;
            case 'deixar_de_seguir':
                $seguidores->deixarDeSeguir($id_user_follow);
                break;
        }

        header('Location: /quemseguir');
    }

    public function deleteTweet() {
        $this->validateLogin();
        $id_tweet = isset($_GET['id_tweet']) ? $_GET['id_tweet'] : '';

        $tweet = Container::getModel('Tweet');
        $tweet->__set('id', $id_tweet);

        $tweet->deleteTweet();

        header('Location: /timeline');
    }
}