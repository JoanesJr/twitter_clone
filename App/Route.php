<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['home'] = array(
			'route' => '/',
			'controller' => 'indexController',
			'action' => 'index'
		);

		$routes['inscreverse'] = array(
			'route' => '/inscreverse',
			'controller' => 'indexController',
			'action' => 'inscreverse'
		);

		$routes['registrar'] = array(
			'route' => '/registrar',
			'controller' => 'indexController',
			'action' => 'register'
		);

		$routes['autenticar'] = array(
			'route' => '/autenticar',
			'controller' => 'AuthController',
			'action' => 'auth'
		);

		$routes['timeline'] = array(
			'route' => '/timeline',
			'controller' => 'AppController',
			'action' => 'timeline'
		);

		$routes['sair'] = array(
			'route' => '/sair',
			'controller' => 'AppController',
			'action' => 'logout'
		);

		$routes['tweet'] = array(
			'route' => '/tweet',
			'controller' => 'AppController',
			'action' => 'tweet'
		);

		$routes['quemSeguir'] = array(
			'route' => '/quemseguir',
			'controller' => 'AppController',
			'action' => 'quemSeguir'
		);

		$routes['acao'] = array(
			'route' => '/acao',
			'controller' => 'AppController',
			'action' => 'action'
		);

		$routes['removertweet'] = array(
			'route' => '/removertweet',
			'controller' => 'AppController',
			'action' => 'deleteTweet'
		);

		$routes['perfil'] = array(
			'route' => '/perfil',
			'controller' => 'PerfilController',
			'action' => 'index'
		);

		$routes['alterarperfil'] = array(
			'route' => '/alterarperfil',
			'controller' => 'PerfilController',
			'action' => 'alterarPerfil'
		);

		$this->setRoutes($routes);
	}

}

?>