<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $conn= new Conexion();
    $query=$conn->get_inventario_seminuevos();
    $response["error"] = false;
    $response["cantidad"] = "Autos cargados: " . count($query);
    $response["inventario"] =$query;
    print(json_encode($response));
}


?>