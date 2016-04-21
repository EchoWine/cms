<?php

namespace Auth\Repository;

use CoreWine\DB as DB;
use CoreWine\Cfg;

class AuthRepository{

	/**
	 * Name table session
	 */
	const TABLE_SESSION = 'session';

	/**
	 * Name table user
	 */
	const TABLE_USER = 'user';

	/**
	 * Alter schema
	 */
	public static function alterSchema(){
		AuthRepository::alterSchemaSession();
		AuthRepository::alterSchemaUser();
	}

	/**
	 * Schema table Session
	 */
	public static function alterSchemaSession(){

		DB::schema(AuthRepository::TABLE_SESSION,function($table){
			$table -> bigint('user_id');
			$table -> string('sid',128) -> primary();
			$table -> timestamp('expire');
		});
	}


	/**
	 * Schema table user
	 */
	public static function alterSchemaUser(){

		DB::schema(AuthRepository::TABLE_USER,function($table){
			$table -> id();
			$table -> string('password',128);
			$table -> string('username');
			$table -> string('email');
		});

	}

	/**
	 * Get query builder of table user 
	 *
	 * @return CoreWine\DB\QueryBuilder
	 */
	public static function user(){
		return DB::table(AuthRepository::TABLE_USER);
	}

	/**
	 * Get query builder of table session
	 *
	 * @return CoreWine\DB\QueryBuilder
	 */
	public static function session(){
		return DB::table(AuthRepository::TABLE_SESSION);
	}

	/**
	 * Get query builder of table session joined with user
	 *
	 * @return CoreWine\DB\QueryBuilder
	 */
	public static function userSession(){
		return DB::table(AuthRepository::TABLE_SESSION)
		-> rightJoin(AuthRepository::TABLE_USER,'user_id','id');
	}


	/**
	 * Delete session expired
	 */
	public static function removeSessionExpired(){
		return AuthRepository::session() 
		-> where('expire','<',time())
		-> delete();
	}

	/**
	 * Get user by SID
	 *
	 * @param string $sid
	 * @param 
	 */
	public static function getUserBySID($sid){
		return AuthRepository::userSession()
		-> where('sid',$sid) 
		-> get();

	}


	/**
	 * Delete session of user using sid
	 *
	 * @param string $sid
	 */
	public static function deleteSessionBySID($sid){
		
		return AuthRepository::session()
		-> where('sid',$sid) 
		-> delete();
	}

	/**
	 * Get new SID that isn't already used
	 *
	 * @return string sid
	 */
	public static function generateSID(){

		do{
			$sid = md5(microtime());
			$q = AuthRepository::session()
			-> where('sid',$sid)
			-> count();
		}while($q == 1);

		return $sid;
	}

	/**
	 * Get a user using a username/email 
	 * 
	 *
	 */
	public static function getUsersByRaw($usernameOrEmail,$password){
		
		# Building query
		$q = AuthRepository::user() -> where('password',$password);
		
		if(Cfg::get('Auth.login_user'))
			$q = $q -> orWhere('username',$usernameOrEmail);

		if(Cfg::get('Auth.login_mail'))
			$q = $q -> orWhere('email',$usernameOrEmail);

		# Execute query
		$q = $q -> lists();

		return $q;


	}

}	

?>