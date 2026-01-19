<?php 

$servername="mysql";
$username="root";
$rootpass="rootpass"; //si se esta en clase cambiar el rootpass a ($rootpass="AlumnoIFP";)
$database="login_app"; //se le tiene que llamar a la base de datos users1

$conn = mysqli_connect($servername,$username,$rootpass,$database);

if(!$conn){
    die("Conexión fallida");
}

?>
