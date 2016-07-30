<?php

namespace CoreWine\SourceManager;

use CoreWine\Http\Router;
use CoreWine\TemplateEngine\Engine;
use CoreWine\Exceptions as Exceptions;
use CoreWine\TemplateEngine\Response as ViewResponse;

class Controller{
	
	/**
	 * Middleware
	 *
	 * @var Array
	 */
	public $middleware = [];

	/**
	 * Router
	 */
	public function __routes(){}

	/**
	 * Check
	 */
	public function __check(){}
	
	/**
	 * Return a ViewResponse
	 *
	 * @param string $file
	 * @param array $data
	 *
	 * @return ViewResponse
	 */
	public function view($file,$data = []){
		Router::view($data);

		$response = new ViewResponse();
		$response -> setBody(Engine::html($file));
		return $response;
	}

	/**
	 * Set a route
	 *
	 * @param string $method
	 *
	 * @return Route
	 */
	public function route($method = null){
		if($method !== null){
			return Router::any() -> callback(function() use($method){
				if(!method_exists($this,$method)){
					throw new Exceptions\RouteException("No method $method; Check __routes() definition");
				}

				return call_user_func_array(array($this,$method), func_get_args());
			}) -> middleware($this -> middleware);
		}
	}

	// @todo get rid of the $this

	/**
	 * Returns a new instance of CoreWine\Http\Response\Response
	 * @return CoreWine\Http\Response\Response 				
	 */
	public function response() {
		return new \CoreWine\Http\Response\Response;
	}

	/**
	 * Returns a new instance of CoreWine\Http\Response\RedirectResponse
	 * @return CoreWine\Http\Response\RedirectResponse 				
	 */
	public function redirect() {
		return new \CoreWine\Http\Response\RedirectResponse;
	}


}
?>