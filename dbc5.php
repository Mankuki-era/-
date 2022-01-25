<?php

ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );

$mode = $_SERVER["REQUEST_METHOD"];

if($mode == 'GET'){

  $dsn = 'mysql:host=localhost;dbname=attendance;charset=utf8';
  $user = 'root';
  $pass = 'root';

  $dbh = new PDO($dsn, $user, $pass);

  $sql = "SELECT * FROM sublog";

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

}else if($mode == 'POST') {
  
  $dsn = 'mysql:host=localhost;dbname=attendance;charset=utf8';
  $user = 'root';
  $pass = 'root';
  
  $dbh = new PDO($dsn, $user, $pass);

  $request = json_decode(file_get_contents('php://input'));
  $func = $request->func;

  $aryInsert = [];
  foreach($request->data as $data){
    $remarks = '';
    if($data->newRemarks){
      $remarks = $data->newRemarks;
    }
    $aryInsert[] = ['number' => $data->number, 'date' => $data->date, 'remarks' => $remarks, 'check01' => $data->newCheck01, 'check02' => $data->newCheck02, 'check03' => $data->newCheck03, 'check04' => $data->newCheck04, 'date01' => $data->newDate01, 'date02' => $data->newDate02, 'check05' => $data->newCheck05];
  };

  // error_log(print_r($aryInsert,true),"3","/Users/mankuki_era/Documents/debug.log");
  
  if($func == 'create'){
    $aryColumn = array_keys($aryInsert[0]);

    //SQL文作成処理
    $sql = "INSERT INTO sublog (".implode(',', $aryColumn).") VALUES";

    $arySql1 = [];
    foreach($aryInsert as $key1 => $val1){
      $arySql2 = [];
      foreach($val1 as $key2 => $val2){
        $arySql2[] = ':'.$key2.$key1;
      }
      $arySql1[] = '('.implode(',', $arySql2).')';
    }

    $sql .= implode(',', $arySql1);

    $stmt = $dbh->prepare($sql);
    foreach($aryInsert as $key1 => $val1){
      foreach($val1 as $key2 => $val2){
        $stmt->bindValue(':'.$key2.$key1, $val2);
      }
    }
    $stmt->execute();

    error_log(print_r($stmt->errorInfo(),true),"3","/Users/mankuki_era/Documents/debug.log");

  }elseif($func == 'update'){
  
    $sql = "UPDATE sublog SET ";

    $arySql = [];
    foreach($aryInsert[0] as $key => $val){
      if($key != 'number' && $key != 'date'){
        $arySql[] = $key.'=:'.$key;
      }
    }

    $sql.=implode(',', $arySql);
    $sql.=" WHERE number = :number AND date = :date";

    error_log(print_r($sql,true),"3","/Users/mankuki_era/Documents/debug.log");
 
    $stmt = $dbh->prepare($sql);
    
    foreach($aryInsert as $row) {
      $array = array();
      foreach($row as $key => $val){
        $array[":$key"] = $val;
      }
      $stmt->execute($array);
      error_log(print_r($stmt->errorInfo(),true),"3","/Users/mankuki_era/Documents/debug.log");
    }

  }

}

?>


