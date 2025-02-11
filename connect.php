<?php
$servername="localhost";
$username="root";
$password="";
$database="serenity_styles";
$conn=mysqli_connect($servername,$username,$password,$database);
if(!$conn){
    die("connection failed".mysqli_connect_error());
}
else{
    // echo "connection established successfully";
    session_start();
}

?>