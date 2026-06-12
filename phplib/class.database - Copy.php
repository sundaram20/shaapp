<?php

class DbConnect {

    private $host;
    private $user;
    private $password;
    private $database;
    private $port;
    private $persistent;
    private $conn = null;
    private $result = false;
    private $error_reporting;

    // Constructor
    public function __construct(
        $dbhost,
        $dbuser,
        $dbpass,
        $dbname,
        $dbport = 3306,
        $error_reporting = true,
        $persistent = false
    ) {
        $this->host = $dbhost;
        $this->user = $dbuser;
        $this->password = $dbpass;
        $this->database = $dbname;
        $this->port = $dbport;
        $this->error_reporting = $error_reporting;
        $this->persistent = $persistent;
    }

    // Open connection
    public function open() {

        if ($this->persistent) {
            $this->conn = mysqli_connect(
                "p:" . $this->host,
                $this->user,
                $this->password,
                $this->database,
                $this->port
            );
        } else {
            $this->conn = mysqli_connect(
                $this->host,
                $this->user,
                $this->password,
                $this->database,
                $this->port
            );
        }

        if (!$this->conn) {
            if ($this->error_reporting) {
                die("MySQL Connection Failed: " . mysqli_connect_error());
            }
            return false;
        }

        return true;
    }

    // Close connection
    public function close() {
        if ($this->conn) {
            return mysqli_close($this->conn);
        }
        return false;
    }

    // Run query
    public function query($sql) {
        $this->result = mysqli_query($this->conn, $sql);
        return ($this->result !== false);
    }
    function query2($sql) {

		$this->result = @mysqli_query($this->conn, $sql); 

		return($this->result != false);         

	} 
    
    // Fetch as array
    public function fetch_assoc($rss) {
        return mysqli_fetch_assoc($rss);
    }
   
    // Fetch as object
    public function fetch_object($rss) {
        return mysqli_fetch_object($rss);
    }
    public function fetch_object2($rss) {
        return mysqli_fetch_object($rss);
    }
    public function fetch_obj2() {
        return mysqli_fetch_object($this->result);
    }

    public function fetch_array() {
        return mysqli_fetch_array($this->result);
    }
    public function fetch_array2() {
        return mysqli_fetch_array($this->result);
    }
    public function fetch_assoc2($rss) {
        return mysqli_fetch_assoc($rss);
    }
   
    // Number of rows
    public function num_rows($rss) {
        return mysqli_num_rows($rss);
    }
    public function num_rows2($rss) {
        return mysqli_num_rows($rss);
    }
    // Affected rows
    public function affected_rows() {
        return mysqli_affected_rows($this->conn);
    }

    // Insert ID
    public function insert_id() {
        return mysqli_insert_id($this->conn);
    }

    // Error
    public function error() {
        return $this->error_reporting ? mysqli_error($this->conn) : '';
    }
}

?>