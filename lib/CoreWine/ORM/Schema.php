<?php

namespace CoreWine\ORM;

use CoreWine\ORM\Field\Schema\Field as Field;

class Schema{

	/**
	 * Model
	 */
	public $__model;

	/**
	 * Name of table
	 */
	public $table;

	/**
	 * Primary key
	 */
	public $primary = 'id';

	/**
	 * Field of default sorting
	 */
	public $sortDefaultField = 'id';

	/**
	 * Direction of default sorting
	 */
	public $sortDefaultDirection = 'desc';

	/**
	 * Fields
	 */
	public $fields;

	/**
	 * Add a field
	 */
	public function field($class,$name){

		if(is_subclass_of($class,Field::class)){

			$field = new $class($name);
			$this -> fields[$name] = $field;
			return $field;
		}else{
			throw new \Exception('Error during creation of field');
		}
	}

	/**
	 * Construct
	 */
	public function __construct(){
	}

	/**
	 * Get field default for sorting
	 *
	 * @return Field
	 */
	public function getSortDefaultField(){
		return $this -> getField($this -> sortDefaultField);
	}

	/**
	 * Get field direction for sorting
	 *
	 * @return Field
	 */
	public function getSortDefaultDirection(){
		return $this -> sortDefaultDirection;
	}


	/**
	 * Set fields
	 */
	public function fields(){}

	/**
	 * Is set a field
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function isField($name){
		return isset($this -> fields[$name]);
	}

	/**
	 * Get fields
	 */
	public function getFields(){
		return $this -> fields;
	}

	/**
	 * Get field
	 *
	 * @param string $name
	 *
	 * @return Field
	 */
	public function getField($name){
		return $this -> hasField($name) ? $this -> fields[$name] : null;
	}

	/**
	 * Has fields
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasField($name){
		return isset($this -> fields[$name]);
	}

	/**
	 * Set table
	 */
	public function setTable($table){
		$this -> table = $table;
	}

	/**
	 * Get table
	 */
	public function getTable(){
		return $this -> table;
	}

	/**
	 * Get primary field
	 *
	 * @return ORM\Field\Schema\Field
	 */
	public function getPrimaryField(){
		return $this -> getField($this -> primary);
	}

	/**
	 * Get primary field column
	 *
	 * @return string
	 */
	public function getPrimaryColumn(){
		return $this -> getField($this -> primary) -> getColumn();
	}


}
?>