<?php
require_once('../migrate.php');
ini_set('display_errors', "On");
ini_set('display_errors', 1);

$table_name = 'second_user';

$data = [
  'id' => 'BIGINT(12) AUTO_INCREMENT PRIMARY KEY',
  'name' => 'VARCHAR(8)',
  'number' => 'VARCHAR(8)',
  'created_at' => 'TIMESTAMP'
];

migrate($table_name, $data);