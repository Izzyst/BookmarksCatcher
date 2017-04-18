<?php
    session_start();
    require_once "connect.php";
    //@- operator kontroli błędów
   $link=@new mysqli($host, $db_user, $db_password, $db_name);

    if($link -> connect_errno!=0)
    {
        echo "Error: ".$link->connect_errno." ".$link->connect_error;
    } 

    if(!isset($_COOKIE['zalogowany']))
    {
      //  header('Location:index.php');
      // exit();
    }
    else{       
            $id = $_COOKIE['id_sesja'];
            $token = $_COOKIE['token'];

            $q = mysqli_fetch_assoc( mysqli_query($link, "SELECT count(id_sesja) ilosc  FROM sesja WHERE id = '$id' AND token = '$token'"));

            /*if(!$q['ilosc'])
            {
                header('Location:logout.php');
                exit();
            }
            else{*/
                $t = sha1(rand(-10000,10000) . microtime()) . sha1(crc32(microtime()) . $_SERVER['REMOTE_ADDR']);
                setcookie('token',$t, time()+3600);
                mysqli_query($link, "UPDATE sesja set token = '$t' where id = '$id'");
            //}     

            $i = mysqli_fetch_assoc(mysqli_query($link, "SELECT id_uzytk_sesji from sesja where id = '$id'"));
            $id_uzytk = $i['id_uzytk_sesji'];
            echo "id uzytk:".$id_uzytk."<br>";
            if(mysqli_query($link, "DELETE from uzytk where id_uzytk = '$id_uzytk'"))
            {
                echo "User deleted!!!";
                header('Location:logout.php');
            }
            else{
                echo"sth went wrong :/";
            }
    }
            ?>