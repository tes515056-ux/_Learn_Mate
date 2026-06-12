<?php
$servername="localhost";
$username="root";
$password="";
$database="learnmate";


$conn=mysqli_connect($servername,$username,$password,$database);
if(!$conn)
{
    die("Error to connect :".mysqli_connect_error());
}
?>