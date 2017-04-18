<?php

    session_start();
   // session_unset();

//if (isset($_POST['mail'])) {
         setcookie('zalogowany', false, time() - 1); // empty value and old timestamp
         //unset($_COOKIE['zalogowany']);
    //setcookie('zalogowany', false, time() - 1, '/'); // empty value and old timestamp
header('Location:index.php');

//if (isset($_GET['logout'])) {
//setcookie("login",'', time()-1); unset($_COOKIE['login']);
//setcookie("id_sesja",'', time()-1); unset($_COOKIE['id_sesja']);


    


?>