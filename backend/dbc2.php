<?php
require_once('dbc.php');

$mode = $_SERVER["REQUEST_METHOD"];
$dbh = dbConnect();

if($mode == 'GET'){

  $grade = $_GET['grade'];

  $sql = "SELECT * FROM schedule WHERE grade = :grade";

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
  
}else if($mode == 'POST') {
  
  $request = json_decode(file_get_contents('php://input'));
  // error_log(print_r($request,true),"3","/Users/mankuki_era/Documents/debug.log");
  $func = $request->func;
  $grade = $request->grade;

  if($func == 'create'){
    $table_json = json_encode($request->schedule_json);
    $sql = "INSERT INTO schedule (grade, table_json) VALUES (:grade, :table_json)";
  }elseif($func == 'update'){
    $table_json = json_encode($request->schedule_json);
    $sql = "UPDATE schedule SET table_json = :table_json WHERE grade = :grade";
  }elseif($func == 'delete'){
    $sql = "DELETE FROM schedule WHERE grade = :grade";
  }

  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(':grade', $grade);
  if($table_json) $stmt->bindValue(':table_json', $table_json);

  $stmt->execute();

  // error_log(print_r($stmt->errorInfo(),true),"3","/Users/mankuki_era/Documents/debug.log");
  error_log(print_r(strlen($table_json),true),"3","/Users/mankuki_era/Documents/debug.log");

  // ALTER TABLE schedule AUTO_INCREMENT = 1;
}

?>