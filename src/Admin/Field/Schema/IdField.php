<?php

namespace Admin\Field\Schema;

class IdField extends StringField{
	
	public $add = false;

	/**
	 * Set DB schema
	 */
	public function alter($table){
		$table -> id($this -> name);
	}


}

?>