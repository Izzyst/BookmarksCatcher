<?php
// TO DO : 
//WYBIERANIE WSZYSTKICH DANYCH DLA WPISANEJ FRAZY
// WSADZENIE TYCH DANYCH DO BOXÓW
//WYŚWIETLANIE LOGO I CAŁĄ RESZTĘ SZATY GRAFICZNEJ
//ROZWIAZAC PROBLEM GUBIENIA DANYCH PRZY PRZEKIEROWANIU

    session_start();
    /*$link=mysqli_connect("sql302.epizy.com",  "epiz_19542191", "siostrakamdam24", "epiz_19542191_bookmarks")
	or die("blad polaczenia");*/
    
    require_once "connect.php";    
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
            
            if(isset($_POST['search']))
            {
                if(isset($_GET['go']))
                {
                    if(preg_match("/^[a-zA-Z]+/", $_POST('name')))
                    {
                        $name = $_POST['name'];

                        $sql = "SELECT * from zakladki where nazwa_zakl like '%".$name;
                        $result=$link->query($sql);

                        while($row=mysqli_fetch_array($result)){
                            $nazwa_zakladki =$row['nazwa_zakl'];
                            $ikona =$row['ikona'];
                            $ID=$row['ID'];
                            echo "<ul>\n"; 

                            // przerobić tak, by generowany się tylko ID zakladek a potem wszystko wrzucone do JSONa i wyświetlone
	                        echo "<li>" . "<a  href=\"search.php?id=$ID\">"   .$nazwa_zakladki . " " . $ikona .  "</a></li>\n"; 
	                        echo "</ul>"; 
                        }
                    }
                }
            }
            else{
                echo "<p> Pleace enter a search query</p>";
            }
    }
    ?>