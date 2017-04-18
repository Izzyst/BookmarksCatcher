
<?php
// TO DO : 
//WYBIERANIE WSZYSTKICH DANYCH DLA WPISANEJ FRAZY
// WSADZENIE TYCH DANYCH DO BOXÓW
//WYŚWIETLANIE LOGO I CAŁĄ RESZTĘ SZATY GRAFICZNEJ
//ROZWIAZAC PROBLEM GUBIENIA DANYCH PRZY PRZEKIEROWANIU
require_once "connect.php";
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



header('content-type:application/json');
$lookFor = $_COOKIE['lookFor'];

                //$lookFor = $_GET['search'];
               // setcookie('lookFor',$lookFor, time()+3600);
                $x = mysqli_query($link, "SELECT *  from folder where nazwa_folderu like '%{$lookFor}%'");
                //echo "SELECT *  from folder where nazwa_folderu like '%{$lookFor}%'";

                if($x->num_rows>0)
                {
                    $i=0;
                    $folder = array();
                    while($row=$x->fetch_assoc())
                    {
                        $folder[$i]['id_folder'] = $row["id_folder"];
                        $folder[$i]['nazwa_folderu'] = $row["nazwa_folderu"];
                        $folder[$i]['numer'] = $row["numer"];
                        $folder[$i]['id_nadfolder'] = $row["id_nadfolder"];
                        $folder[$i]['id_folderu_w_zestawie_folderow'] = $row["id_folderu_w_zestawie_folderow"];
                        $folder[$i]['ikona_folderu'] = $row["ikona_folderu"];
                        $folder[$i]['rodzaj'] = $row["rodzaj"];
                        $i++;
                    }
                 echo json_encode($folder); 
                }
              
//header('Refresh: 3; url=search.php');
//header("Location:search.php");

               /* if($x['nazwa_folderu'])
                {
                    echo $x['nazwa_folderu'];
                    
                }
                else{
                    echo "nope";
                }

                if($y['nazwa_zakl'])
                {
                    echo $y['nazwa_zakl'];
                }
                else{
                    echo "nope";*/
            ?>
