<?php
namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;


class PerfilController extends Action {
    public function index() {
        $this->validateLogin();
        $users = Container::getModel('Usuario');
        $users->__set('id', $_SESSION['id']);
        $this->view->user = $users->getUser();
        $this->render('index');
    }

    public function alterarPerfil() {
        $this->validateLogin();
        $user = Container::getModel('Usuario');
        $user->__set('id', $_SESSION['id']);
        $user_pre = $user->getUser();
        $img_atual = $user_pre->image;
        if ($img_atual != 'vazio.jpg') {
            unlink('img_perfil/'.$img_atual);
        }
        $senha_atual = $user_pre->password;
        
        $user->__set('name', $_POST['name']);
        $user->__set('email', $_POST['email']);
        if (empty($_POST['password'])) {
            $user->__set('password', $senha_atual);
        } else {
            $user->__set('password', md5($_POST['password']));
        }
        
        $name_img = $this->nameImg();
        $user->__set('image', $name_img);
        $user->updatePerfil();

        $this->view->user = $user->getUser();

        $this->render('index');
    }

    public function nameImg() {
        if (!empty($_FILES['img']['name'])) {
            $extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
            $name = pathinfo($_FILES['img']['name'], PATHINFO_FILENAME);
            $new_name = time().'.'.$extension;
            $directory = "img_perfil/";
            move_uploaded_file($_FILES['img']['tmp_name'], $directory.$new_name);
        }else {
            $new_name = 'vazio.jpg';
        }

        return $new_name;
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
}