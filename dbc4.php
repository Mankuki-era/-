<?php

ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );

$mode = $_SERVER["REQUEST_METHOD"];

if($mode == 'GET'){

  $dsn = 'mysql:host=localhost;dbname=attendance;charset=utf8';
  $user = 'root';
  $pass = 'root';

  $dbh = new PDO($dsn, $user, $pass);

  $sql = "SELECT * FROM log";

  $stmt = $dbh->prepare($sql);
  $stmt->execute();

  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $results = [
    'status' => '200',
    'mode' => $mode,
    'param' => $result,
    'message' => 'getアクセスしました。'
  ];

  echo json_encode($results);

}

?>


