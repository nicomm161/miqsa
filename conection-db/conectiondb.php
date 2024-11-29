<?php

$server = "localhost";
$user = "root";
$pass = "";
$bd = "clientesycitas";

$conex = new mysqli($server, $user, $pass, $bd);

try{

    if ($conex) {

        echo "";
    }


}catch(Exception $e){

    echo "Algo salió mal: " . $e->getMessage();

}

?>