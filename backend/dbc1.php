<?php
require_once('dbc.php');

$mode = $_SERVER["REQUEST_METHOD"];
$dbh = dbConnect();

if($mode == 'GET'){

  $grade = $_GET['grade'];

  if($_GET['mode'] == '1'){
    // 学籍番号順
    $sql = "SELECT * FROM user WHERE grade = :grade ORDER BY number";
    
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':grade', $grade);
    $stmt->execute();
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  }elseif($_GET['mode'] == '2'){
    // 班順
    $sql = "SELECT DISTINCT team FROM user WHERE grade = :grade ORDER BY team";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':grade', $grade);
    $stmt->execute();
    $result1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $result = [];
    foreach($result1 as $key => $val){
      $sql = "SELECT * FROM user WHERE team = :team ORDER BY number";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':team', $val['team']);
      $stmt->execute();
      $result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $data = [
        'team' => $val['team'],
        'user' => $result2
      ];
      $result[] = $data;
    }
  }

  $results = [
    'status' => '200',
    'mode' => $mode,
    'param' => $result,
    'message' => 'getアクセスしました。'
  ];
  // error_log(print_r($results,true),"3","/Users/mankuki_era/Documents/debug.log");
  echo json_encode($results);

}else if($mode == 'POST') {

  $request = json_decode(file_get_contents('php://input'));
  $grade = $request->grade;
  $func = $request->func;
  
  if($func == 'create'){
    $users = $request->users;
    $sql = "INSERT INTO user (number, name, team, grade) VALUES";
    $arySql1 = [];
    foreach($users as $key1 => $val1){
      $arySql2 = [];
      $arySql2[] = ':number'.$key1;
      $arySql2[] = ':name'.$key1;
      $arySql2[] = ':team'.$key1;
      $arySql1[] = '('.implode(',', $arySql2).',:grade'.')';
    }
    $sql .= implode(',', $arySql1);
  }elseif($func == 'delete'){
    $sql = "DELETE FROM user WHERE grade = :grade";
  }

  $stmt = $dbh->prepare($sql);
  if($users){
    foreach($users as $key1 => $val1){
      $stmt->bindValue(':number'.$key1, $val1->number);
      $stmt->bindValue(':name'.$key1, $val1->name);
      $stmt->bindValue(':team'.$key1, $val1->team);
    }
  }
  $stmt->bindValue(':grade', $grade);
  $stmt->execute();

  // ALTER TABLE user AUTO_INCREMENT = 1;
}

?>


