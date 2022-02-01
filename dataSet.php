<?php
error_log(print_r("Hello World",true),"3","/Users/mankuki_era/Documents/debug.log");
  // $dsn = 'mysql:host=localhost;dbname=attendance;charset=utf8';
  // $user = 'root';
  // $pass = 'root';

  // $pdo = new PDO($dsn, $user, $pass);

  // $sql = "INSERT INTO log (number, temperature) VALUES (:number, :temperature)";

  // $stmt = $dbh->prepare($sql);
  // $stmt->bindValue(':number', '18EC053');
  // $stmt->bindValue(':temperature', 36.8);
  // $stmt->execute();


//   //配列設定
//   $aryInsert = [];
//   // A
//   $aryInsert[] = ['number' => '19NC002', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC004', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC005', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '17NC038', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];

//   $aryInsert[] = ['number' => '19NC006', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC007', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC008', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];

//   $aryInsert[] = ['number' => '19NC035', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC043', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC050', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '18NC007', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];

//   $aryInsert[] = ['number' => '20NC036', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '18NC025', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '18NC043', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC061', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];

//   // B
//   $aryInsert[] = ['number' => '19NC010', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC011', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC012', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
// //   $aryInsert[] = ['number' => '18NC009', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];

//   $aryInsert[] = ['number' => '19NC027', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC014', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC015', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '18NC050', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];

//   $aryInsert[] = ['number' => '19NC016', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC018', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
// //   $aryInsert[] = ['number' => '19NC019', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC020', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];

//   $aryInsert[] = ['number' => '19NC021', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC024', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC025', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];

//   // C
// //   $aryInsert[] = ['number' => '19NC026', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
// //   $aryInsert[] = ['number' => '19NC027', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
// //   $aryInsert[] = ['number' => '19NC028', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
// //   $aryInsert[] = ['number' => '18NC017', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];

// //   $aryInsert[] = ['number' => '19EC701', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
// //   $aryInsert[] = ['number' => '19NC029', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
// //   $aryInsert[] = ['number' => '19NC031', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
// //   $aryInsert[] = ['number' => '19NC032', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];

// //   $aryInsert[] = ['number' => '19NC033', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
// //   $aryInsert[] = ['number' => '19NC037', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
// //   $aryInsert[] = ['number' => '19NC039', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
// //   $aryInsert[] = ['number' => '19NC040', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];

// //   $aryInsert[] = ['number' => '19NC041', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
// //   $aryInsert[] = ['number' => '19NC042', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
// //   $aryInsert[] = ['number' => '19NC044', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];

//   // D
//   $aryInsert[] = ['number' => '19NC045', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC047', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC048', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '18NC039', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];

// //   $aryInsert[] = ['number' => '19NC049', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC051', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC054', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC055', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];

//   $aryInsert[] = ['number' => '19NC057', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC058', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC060', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];

//   $aryInsert[] = ['number' => '19NC062', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC063', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];
//   $aryInsert[] = ['number' => '19NC064', 'temperature' => 36.6, 'created_at' => '2021-12-13 17:53:00'];

//   $aryColumn = array_keys($aryInsert[0]);

//   //SQL文作成処理
//   $sql = "INSERT INTO
//           log
//           (".implode(',', $aryColumn).")
//           VALUES";

//   $arySql1 = [];
//   //行の繰り返し
//   foreach($aryInsert as $key1 => $val1){
//       $arySql2 = [];
//       //列（カラム）の繰り返し
//       foreach($val1 as $key2 => $val2){
//           $arySql2[] = ':'.$key2.$key1;
//       }
//       $arySql1[] = '('.implode(',', $arySql2).')';
//   }

//   $sql .= implode(',', $arySql1);

//   //bind処理
//   $sth = $pdo -> prepare($sql);
//   foreach($aryInsert as $key1 => $val1){
//       foreach($val1 as $key2 => $val2){
//           $sth -> bindValue(':'.$key2.$key1, $val2);
//       }
//   }

//   //実行処理
//   $sth -> execute();


?>