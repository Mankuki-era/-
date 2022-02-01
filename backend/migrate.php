<?php
require_once('dbc.php');

$dbh = dbConnect();

function migrate($table_name, $data){
  $array = [];
  foreach($data as $key => $val){
    $array[] = $key.' '.$val;
  }
  $data_string = implode(',', $array);
  $sql = "CREATE TABLE $table_name ($data_string)";
  
  // error_log(print_r($sql,true),"3","/Users/mankuki_era/Documents/debug.log");
  // $stmt = $dbh->prepare($sql);
  // $stmt->execute();
}
