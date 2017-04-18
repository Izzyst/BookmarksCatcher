
<?php
error_reporting(0);

    session_start();
   /* $link=mysqli_connect("sql302.epizy.com",  "epiz_19542191", "siostrakamdam24", "epiz_19542191_bookmarks")
	or die("blad polaczenia");*/
    require_once "connect.php";
      $link=@new mysqli($host, $db_user, $db_password, $db_name);

if($link -> connect_errno!=0)
{
     echo "Error: ".$link->connect_errno." ".$link->connect_error;
} 

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


      //  function generatejson()
      //  {
          header('content-type:application/json');
            $i = mysqli_fetch_assoc(mysqli_query($link, "SELECT id_uzytk_sesji from sesja where id = '$id'"));
                $id_uzytk = $i['id_uzytk_sesji'];
                $d= mysqli_query($link, "SELECT * from folder where id_uzytk='$id_uzytk'" );
                $folder = array();
                if($d->num_rows >0)
                {

                    $i=0;
                    while($row= $d->fetch_assoc())
                    {
                        $folder[$i]['id_folder'] = $row['id_folder'];
                        $folder[$i]['nazwa_folderu'] = $row["nazwa_folderu"];
                        $folder[$i]['numer'] = $row["numer"];
                        $folder[$i]['id_nadfolder'] = $row["id_nadfolder"];
                        $folder[$i]['id_folderu_w_zestawie_folderow'] = $row["id_folderu_w_zestawie_folderow"];
                        $folder[$i]['ikona_folderu'] = $row["ikona_folderu"];
                        $folder[$i]['rodzaj'] = $row["rodzaj"];
                    $i++;
                    }
                   $myJSON = json_encode($folder);
                    echo $myJSON;
                //  echo '<script type="text/javascript">';
                    //echo 'var data='.json_encode($folder).";\r\n";
                    
                }
   //     }

   // ob_start("generatejson");
//ob_end_flush();

        
    }




?>
