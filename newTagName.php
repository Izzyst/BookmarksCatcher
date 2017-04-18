<?php

    session_start();
	
  /*$link = new mysqli("sql302.epizy.com",  "epiz_19542191", "siostrakamdam24", "epiz_19542191_bookmarks");
      */
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
            
        $p = mysqli_fetch_assoc( mysqli_query($link, "SELECT id_uzytk_sesji  FROM sesja WHERE id = '$id'"));
    
        $id_uzytk=$p['id_uzytk_sesji'];
        
        $allFine = true;      
        $tag= mysqli_real_escape_string($link,$tag);
        if(isset($_POST['newTag']))
        {
            $tag = $_POST['newTag'];
            //                    if($link->query("INSERT INTO folder(nazwa_folderu, id_uzytk, id_nadfolder) VALUES ('{$FileName[$counter][0]}', '{$id_uzytk}', '{$tabFile[$amountDL]['idParent']}');"))

            $query = "INSERT INTO tagi(nazwa_tagu, id_uzytkT) VALUES ('{$tag}', '{$id_uzytk}')";
            if(mysqli_query($link, $query))
            {
               // echo"ok";
            }
            else{
                echo "nope";
            }
        }
        else{
            alert("NOT FOUND!");// DODATKOWA STRONA error page + not found 404 + after 5s + przekierowanie do str głównej
            header('Location:errorPage.php');   
            exit();        
        }

      }
    
      ?>  