<?php

  include_once("conn.php");

  $method = $_SERVER["REQUEST_METHOD"];

  // Resgate dos dados, montagem do pedido
  if($method === "GET") {

    $bordasQuery = $conn->query("SELECT * FROM bordas;");

    $bordas = $bordasQuery->fetchAll();

    $massasQuery = $conn->query("SELECT * FROM massas;");

    $massas = $massasQuery->fetchAll();

    $saboresQuery = $conn->query("SELECT * FROM sabores;");

    $sabores = $saboresQuery->fetchAll();
  
  // Criação do pedido
  } else if($method === "POST") {

    $data = $_POST;

    $borda = $data["borda"];
    $massa = $data["massa"];
    $sabores = $data["sabores"];

    // validação de sabores máximos
    if(count($sabores) > 3) {

      $_SESSION["msg"] = "Selecione no máximo 3 sabores!";
      $_SESSION["status"] = "warning";

    } else {

      // salvando borda e massa na pizza
      $stmt = $conn->prepare("INSERT INTO pizzas (borda_id, massa_id) VALUES (:borda, :massa)");

      // filtrando inputs
      $stmt->bindParam(":borda", $borda, PDO::PARAM_INT);
      $stmt->bindParam(":massa", $massa, PDO::PARAM_INT);

      $stmt->execute();

      // resgatando último id da última pizza
      $pizzaId = $conn->lastInsertId();

      $stmt = $conn->prepare("INSERT INTO pizza_sabor (pizza_id, sabor_id) VALUES (:pizza, :sabor)");

      // repetição até terminar de salvar todos os sabores
      foreach($sabores as $sabor) {

        // filtrando os inputs
        $stmt->bindParam(":pizza", $pizzaId, PDO::PARAM_INT);
        $stmt->bindParam(":sabor", $sabor, PDO::PARAM_INT);

        $stmt->execute();

      }

      // criar o pedido da pizza
      $stmt = $conn->prepare("INSERT INTO pedidos (pizza_id, status_id) VALUES (:pizza, :status)");

      // status -> sempre inicia com 1, que é em produção
      $statusId = 1;

      // filtrar inputs
      $stmt->bindParam(":pizza", $pizzaId);
      $stmt->bindParam(":status", $statusId);

      $stmt->execute();

      // Exibir mensagem de sucesso
      $_SESSION["msg"] = "Pedido realizado com sucesso";
      $_SESSION["status"] = "success";

    }

    // Retorna para página inicial
    header("Location: ..");

  }

?>