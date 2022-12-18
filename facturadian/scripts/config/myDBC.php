<?php
ini_set('display_errors', 0);
session_start();

class Dolibarr {
        // our mysqli object instance
        public $mysqli = null;
        // Class constructor override
        public function __construct($DB_NAME) {

			include_once "dbconfig.php";
			$this->mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, $DB_NAME);
			if ($this->mysqli->connect_errno) {
					echo "Error MySQLi: ("&nbsp. $this->mysqli->connect_errno.") " . $this->mysqli->connect_error;
					exit();
			}
			$this->mysqli->set_charset("utf8");
        }
        // Class deconstructor override
        public function __destruct() {
                $this->CloseDB();
        }
        // runs a sql query
    public function runQuery($qry) {
        $result = $this->mysqli->query($qry);
        return $result;
    }
        // Close database connection
    public function CloseDB() {
        $this->mysqli->close();
    }
        // Escape the string get ready to insert or update
    public function clearText($text) {
        $text = trim($text);
        return $this->mysqli->real_escape_string($text);
    }
        // Devuelve un arreglo listo
        public function leerdatos($cadena){
                $result = $this->mysqli->query($cadena);
                return $result->fetch_array(MYSQLI_ASSOC);
        }
        // Sirve para las grillas y tambien sirve para count
        public function leerdatosarray($cadena){
                $result = $this->mysqli->query($cadena);
                return $result;
        }
        // actualiza y graba datos
        public function grabardatos($cadena){
                $result = $this->mysqli->query($cadena);
                return $result;
        }
}

?>

