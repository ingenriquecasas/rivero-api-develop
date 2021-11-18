<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: false');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: text/html; charset=utf-8');
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');



require_once("commun/_config.php");
include("commun/conexion.php");
include("commun/consultas.php");
//if($_POST["Autorizacion"]==API_KEY){
                    $conn= new Conexion();
                    $query=$conn->get_inventario_nuevos();
                   
                    print(json_encode($query));
                

//}


?>
