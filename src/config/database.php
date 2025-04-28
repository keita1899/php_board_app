<?php
function getPDO() {
  $host = getenv('DB_HOST') ?: 'db';
  $dbname = getenv('DB_NAME') ?: 'board_app';
  $user = getenv('DB_USER') ?: 'root';
  $pass = getenv('DB_PASS') ?: 'root';
  $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
  try {
      $pdo = new PDO($dsn, $user, $pass, [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]);
      return $pdo;
  } catch (PDOException $e) {
      exit('DB接続エラー: ' . $e->getMessage());
  }
}