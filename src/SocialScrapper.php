<?php
namespace AndreInocenti\PhpSocialScrapper;


class SocialScrapper {

	private $router = null;

	private function getRouter(){
		if($this->router == null){
			$this->router = include __DIR__ . '/routes.config.php';
		}
		return $this->router;
	}

	private function runCallable($url, $controllerClass, $action, $args){
		$result = [
			'url' => $url,
			'controller' => basename($controllerClass),
			'action' => $action,
		];
		$controller = null;
		try {
			$controller = new $controllerClass($url);
		} catch (\Exception $e) {
			$result['error'] = $e->getMessage();
			if($controller) $controller->client->close();
			return $result;
		}
		try {
			$controller->onStart();
			$result['data'] = $controller->onAction($action, $args);
			$controller->onEnd($result);
		} catch(\Exception $e){
			$result['error'] = $controller->onError($e);
			$result['file'] = $e->getFile();
			$result['line'] = $e->getLine();
		}
		$controller->client->close();
		$controller->client->quit();
		return $result;
	}

	function request($url){
		$route = $this->getRouter()->match($url);
		if(!$route) throw new \Exception('Request $url: "'.$url.'" not supported');
		$args = $route['matches'];
		$callable = $route['value'];
		$controllerClass = $callable[0];
		$action = $callable[1];
		return $this->runCallable($url, $controllerClass, $action, $args);
	}


}

