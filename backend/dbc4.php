<?php
require_once('dbc.php');

$mode = $_SERVER["REQUEST_METHOD"];
$dbh = dbConnect();

if($mode == 'GET'){

  $grade = $_GET['grade'];

  $sql = "SELECT * FROM log WHERE grade = :grade";

  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(':grade', $grade);
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


