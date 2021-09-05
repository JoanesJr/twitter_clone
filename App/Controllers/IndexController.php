<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {

		$this->view->loginErro = isset($_GET['login']) ? $_GET['login'] : '';
		$this->render('index');
	}

	public function inscreverse() {
		$this->view->user = array(
			'name' => '',
			'email' => '',
			'password' => ''
		);
		$this->render('inscreverse');
	}

	public function register() {
		$user = Container::getModel('Usuario');
		$user->__set('name', $_POST['name']);
		$user->__set('email', $_POST['email']);
		$user->__set('password', md5($_POST['password']));
		if ($user->verifyRegister() and $user->recoveryRegister()) {
			$user->saveRegister();
			$this->render('cadastro');
		}else {
			$this->view->user = array(
				'name' => $_POST['name'],
				'email' => $_POST['email'],
				'password' => $_POST['password']
			);
			$this->view->error = true;
			$this->render('inscreverse');
		}
		
	}
}


?>