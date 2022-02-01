<?php
require_once('dbc.php');

$mode = $_SERVER["REQUEST_METHOD"];
$dbh = dbConnect();

if($mode == 'GET'){

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