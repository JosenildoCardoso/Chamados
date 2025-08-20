<?php
include("dbcon.php");
$login = $_GET['login'];
$qctr = mysqli_query($conn, "SELECT login FROM dados WHERE login = '$login'");
if (mysqli_num_rows($qctr)){
echo "1";	
}else{
echo "0";
}


?>