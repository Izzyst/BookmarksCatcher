<?php
// TO DO : zrobić przy rejestracji w przypadku błędnego wprowadzenie danych przekierowanie na strone rejestracji!!
// w przypadku gdy uzytk chce z palca wpisac nazwe podstrony i nie jest zalogowany, wyrzuca go do indexu
// wyjatki w javaScript i php; poziomy izolacji transakcji, udowodnić że dane się nie powielają, postci 1NP, 2PN, 3PN,zabezpieczenie logowanie na połączeniu nieszyfrowanym
// zrobic pasek do wyszukiwania konkretnego napisu
error_reporting(0);

    session_start();
    /*$link=mysqli_connect("sql302.epizy.com",  "epiz_19542191", "siostrakamdam24", "epiz_19542191_bookmarks")
	or die("blad polaczenia");*/
    $link=@new mysqli($host, $db_user, $db_password, $db_name);

    if($link -> connect_errno!=0)
    {
        echo "Error: ".$link->connect_errno." ".$link->connect_error;
    } 

    if(!isset($_COOKIE['zalogowany']))
    {
        header('Location:index.php');
       exit();
    }
    else{       
        $id = $_COOKIE['id_sesja'];
        $token = $_COOKIE['token'];

        $q = mysqli_fetch_assoc( mysqli_query($link, "SELECT count(id_sesja) ilosc  FROM sesja WHERE id = '$id' AND token = '$token'"));

        if(!$q['ilosc'])
        {
            header('Location:logout.php');
            exit();
        }
        else{
            $t = sha1(rand(-10000,10000) . microtime()) . sha1(crc32(microtime()) . $_SERVER['REMOTE_ADDR']);
            setcookie('token',$t, time()+3600);
            mysqli_query($link, "update sesja set token = '$t' where id = '$id'");
        }
            
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>error 404</title>
<link rel="stylesheet" type="text/css" href="style/styleMenu.css" />
    <link rel="stylesheet" type="text/css" href="style/styleSite.css" />
    </head>
<body>

    <div id="container">
    <!--========================================================kod odp. za menu-->
       
        <div id ="logoLine">

            <div id="logo">
                <img src="./img/logo_catcher.jpg" id = "imgLogo">          
            </div>
            

            <a href="logout.php"> log out </a> |
            <a href="model.php"> dodaj zakladki do bazy danych </a>
           
        </div>

        
<?
    header( "refresh:5;url=bookmarks_catcher.php" );
?>
         <!--przycisk rozwijania latajacego menu bara-->
        <div id="menuBarBtn" onclick = "toggleCP()">
            <div></div>
            <div></div>
            <div></div>
        </div>

        <!--==================================================== latający menu bar -->
        <div id="menuBar">

                    <b>Tags:</b>

        </div>
            <div>
            <center><h1><b> something went WRONG!!! :/ </b></h1></center>
            </div>
        </body>
        </html>