<?php
class db {
	public $database;

	function __construct() {
		try {
			$this->database = new medoo ( array(
					'database_type' => DB_TYPE,
					'database_name' => DB_NAME,
					'server' => DB_HOST,
					'username' => DB_USER,
					'password' => DB_PASSWORD,
					'charset' => DB_CHARSET
			) );
		} catch ( Exception $e ) {
			if (DEBUG_APP) {
				echo 'Caught exception: ', $e->getMessage (), '<br/>';
			}
			die ( ERR_MSG );
		}
	}
}