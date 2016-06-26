<?php

namespace Item\Response;

use CoreWine\Request;

class ApiAddError extends Error{

	/** 
	 * Code
	 */
	const CODE = 'error';

	/**
	 * Message
	 */
	const MESSAGE = "An error was occurred";

	/**
	 * Construct
	 */
	public function __construct(){

		parent::__construct(self::CODE,self::MESSAGE);
		$this -> setRequest(Request::getCall());

	}
}

?>