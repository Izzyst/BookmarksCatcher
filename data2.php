<?php
    session_start();
   /* $link=mysqli_connect("sql302.epizy.com",  "epiz_19542191", "siostrakamdam24", "epiz_19542191_bookmarks")
	or die("blad polaczenia");*/
    require_once "connect.php";
      $link=@new mysqli($host, $db_user, $db_password, $db_name);

    if($link -> connect_errno!=0)
    {
        echo "Error: ".$link->connect_errno." ".$link->connect_error;
    } 

error_reporting(E_ERROR | E_PARSE);
    header('content-type:application/json');

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



    $i = mysqli_fetch_assoc(mysqli_query($link, "SELECT id_uzytk_sesji from sesja where id = '$id'"));
    $id_uzytk = $i['id_uzytk_sesji'];
    $d= mysqli_query($link, "SELECT * from folder where id_uzytk='$id_uzytk'" );


    $zakladka = array();
   $d2= mysqli_query($link, "SELECT * from zakladki where id_uzytk2='$id_uzytk'" );
    if($d2->num_rows >0)
    {

        $i=0;
        while($row= $d2->fetch_assoc())
        {
           // $row["ikona_folderu"]
           $zakladka[$i]['id_zakladki'] = $row["id_zakladki"];
            $zakladka[$i]['link'] = $row["link"];
            $zakladka[$i]['nazwa_zakl'] = $row["nazwa_zakl"];
            $zakladka[$i]['ikona'] = $row["ikona"];
            $zakladka[$i]['id_folderu'] = $row["id_folderu"];
            
           $i++;
        }

    echo json_encode($zakladka);
        
    }


    }


?>


