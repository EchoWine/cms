<?php

class stdDataPost extends stdData{

	public function setValue($f,$v = null){
		if(is_closure($v))
			$v = $v($f);
		
		$this -> value = $v = isset($_POST[$f]) ? $_POST[$f] : $v;
	}
}
?>