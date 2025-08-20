<?php
include('jwt.php');
if (!isset($_COOKIE['token']) || MyJWT::valida($_COOKIE['token'], "Fut8xa12h@Dnstuff0609Yft5fbh")==false) {
    header("Location: login.php");
    exit;
}else{

$login = MyJWT::decode($_COOKIE['token'], "Fut8xa12h@Dnstuff0609Yft5fbh")["login"];
}
?>
