 <?php

   /* $link=mysqli_connect("sql302.epizy.com",  "epiz_19542191", "siostrakamdam24", "epiz_19542191_bookmarks")
	or die("blad polaczenia");*/
   require_once "connect.php";
    $link=@new mysqli($host, $db_user, $db_password, $db_name);

    if($link -> connect_errno!=0)
    {
        echo "Error: ".$link->connect_errno." ".$link->connect_error;
    } 

    session_start();// dzieki temu mozliwosc skorzystanie ze zmiennych globalnych $_SESSION
   if((isset($_COOKIE['zalogowany'])) && ($_COOKIE['zalogowany']==true))
    {
        header('Location:logout.php');
        exit(); // żeby nie wykonywały sie niepotrzebne ponizsze komendy
    }  
    //echo mysqli_error($link);

      if(isset($_POST['mail']))
      {    
        $haslo = $_POST['password'];
        $haslo = htmlentities($haslo, ENT_QUOTES);
        $email = $_POST['mail'];
        $email= mysqli_real_escape_string($link,$email);
        //$haslo = mysqli_real_escape_string($link, $haslo);
       // echo "sha1(concat('{$_POST['password']}', sol))";

        $q = mysqli_fetch_assoc( mysqli_query($link, "SELECT count(*) cnt, id_uzytk, haslo, sol
            from uzytk where mail='{$_POST['mail']}'"));//and haslo = sha1(concat('{$_POST['password']}', sol));"));

            if ($q['cnt']) 
            {
                unset($_SESSION['blad']);
                $haslo = htmlentities($haslo, ENT_QUOTES);
                $x = $haslo;//.$q['sol'];
                if(strcmp(sha1($x),$q['haslo']))
                {
                        echo "ok";
                        $t = sha1(rand(-10000,10000). microtime()) . sha1(crc32(microtime()) . $_SERVER['REMOTE_ADDR']);
                        setcookie('token',$t, time()+3600);

                        $id_sesja = sha1(rand(-100,100));
                        setcookie('id_sesja', $id_sesja, time()+3600);

                        mysqli_query($link, "delete from sesja where id_uzytk_sesji = '$q[id_uzytk]';"); 	
                        mysqli_query($link, "
                        insert into sesja ( id_uzytk_sesji, id, ip, web) values 
                        ( '$q[id_uzytk]','$id_sesja','$_SERVER[REMOTE_ADDR]','$_SERVER[HTTP_USER_AGENT]');");
                        
                        if (! mysqli_errno($link))
                        {
                            echo "zalogowano pomyślnie!";
                            setcookie("zalogowany", true, time()+3600);
                            
                            mysqli_query($link, "update sesja set token = '$t' where id_uzytk_sesji = $q[id_uzytk];");
                            header("location:welcome.php");
                        } 
                        else 
                        {
                            $_SESSION['blad']='<span style = "color:white">Login or Password is Invalid!!!</span>'.$_POST['password'];
                            header('Location:index.php');
                            //echo "wprowadzone haslo:".$_POST['password'];
                        }
                }
                else{
                    header('Location:index.php');
                }

            } 
            else 
            {
                /// $x = sha1($_POST['password']);
                //   $h = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $_SESSION['blad']='<span style = "color:white"> Login or Password is Invalid!!!!!!</span>'.$h;
               header('Location:index.php');
            } 
        }
        else
        {
            header('Location:index.php');
      }




?>
