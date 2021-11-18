<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: false');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: text/html; charset=utf-8');
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');


require_once("commun/_config.php");
        
$headers = array();
foreach ($_SERVER as $key => $value) {
    if (strpos($key, 'HTTP_') === 0) {
        $headers[str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
    }
}
if (isset($headers['Autorizacion'])) {
    $token = $headers['Autorizacion'];
 
    if (!($token == API_KEY)) {
    
    $response["error"] = true;
    $response["message"] = "Acceso denegado. Token inválido";
    print(json_encode(["status"=>401, $response]));
    
    } else {
        include("commun/conexion.php");
        include("commun/consultas.php");
        
        switch( $servicio){
            case 'seminuevos':
                include("get_seminuevos.php");
            break;
            default:
                ?>
                <script>location.href="/api";</script>
                <?php
            break;
        }
    }
} else {
    $response["error"] = true;
    $response["message"] = "Falta token de autorización";
    print(json_encode(["status"=>400, $response]));
}




?>