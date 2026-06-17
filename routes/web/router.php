<?php
$controllers=[
    "admin"=>"admin",
    "auteur"=>"auteur",
    "lecteur"=>"lecteur",
    "auth"=>"auth"

];

 $controller=$_REQUEST["controller"]??"lecteur";
 
 if (array_key_exists($controller, $controllers)) {
     $path=ROOT."Controllers/".$controllers[$controller]."Controller.php";
     }
     else{
         echo "controller introuvable";
         exit();
}
         
 require_once($path);