<?php

include 'dbconf.php';

class DB {

  private $connection;

  function __construct() {
    $host = DBCONF::HOSTNAME;
    $db   = DBCONF::DATABASE;
    $user = DBCONF::USERNAME;
    $pass = DBCONF::PASSWORD;
	$enco = DBCONF::ENCODING;

    $this->connection = new mysqli($host, $user, $pass, $db);
	$this->execute("SET NAMES {$enco}");
  }

  function query($sql) {
    $return = array();
    $res = $this->connection->query($sql);
    if ($res) {
      while ($row = $res->fetch_assoc()) {
        $return[] = $row;
      }
    }

    return $return;
  }
  
  function execute($sql) {
	return $this->connection->query($sql);
  }
}

?>