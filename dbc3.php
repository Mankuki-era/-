<?php

ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );

$mode = $_SERVER["REQUEST_METHOD"];

if($mode == 'GET'){

  $dsn = 'mysql:host=localhost;dbname=attendance;charset=utf8';
  $user = 'root';
  $pass = 'root';

  $dbh = new PDO($dsn, $user, $pass);

  $time = $_GET['time'];

  $sql = "SELECT * FROM log WHERE created_at >= :time ORDER BY created_at DESC";

  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(':time', $time);
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