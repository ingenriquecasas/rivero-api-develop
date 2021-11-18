<?php
	/**
	 * Clase conexion base de datos
	 */
class Database{
    private $_host = DB_HOST;
    private $_username = DB_USER;
    private $_password = DB_PASSWORD;
    private $_database = DB_DB;
    private $_conne;
 
    function connect() {
        $con = mysqli_connect($this->_host, $this->_username, $this->_password, $this->_database);
        if (!$con) {
            die('Could not connect to database!');
        } else {
            $this->_conne = $con;
        }
        return  $this->_conne;
    }

    function close() {
        mysqli_close($this->_conne);
    }
}

?>