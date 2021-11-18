<?php
require 'flight/Flight.php';

Flight::route('/', function(){
    echo 'acceso denegado';
});

 Flight::route('/@pagina',function ($pagina){
  	Flight::render('funciones.php',array("servicio"=>$pagina));
  });
  
Flight::start();
