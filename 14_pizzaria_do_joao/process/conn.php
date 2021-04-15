<?php

  session_start();

  $user = "root";
  $pass = "";
  $db = "pizzaria";
  $host = "localhost";

  try {

    $conn = new PDO("mysql:host={$host};dbname={$db}", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

  } catch (PDOException $e) {

    print "Erro: " . $e->getMessage() . "<br/>";
    die();

  }

?>