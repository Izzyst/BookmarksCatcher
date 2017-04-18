<?php
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
                mysqli_query($link, "UPDATE sesja set token = '$t' where id = '$id'");
            }     

            $i = mysqli_fetch_assoc(mysqli_query($link, "SELECT id_uzytk_sesji from sesja where id = '$id'"));
            $id_uzytk = $i['id_uzytk_sesji'];
            
             if ($_FILES["file"]["error"] > 0) 
            {
                echo "Error: " . $_FILES["file"]["error"] . "<br>";
            } 
            else 
            {
                echo "Upload: " . $_FILES["file"]["name"] . "<br>";
                echo "Type: " . $_FILES["file"]["type"] . "<br>";
                echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
                echo "Stored in: " . $_FILES["file"]["tmp_name"];
            }

            /*if (!$mysqli->query("DROP PROCEDURE IF EXISTS pTestFold") ||
    !$mysqli->query("CREATE PROCEDURE pTestFold(in nazwa_fold varchar(100))
BEGIN
select id_folder from folder where nazwa_fold = nazwa_folderu; END;")) {
    echo "Stored procedure creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
    
    if (!$mysqli->query("CALL pTestFold(1)")) {
    echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
    */
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $handle = @fopen($_FILES["file"][0], "r");

            $homepage = (string)file_get_contents($_FILES["file"]["tmp_name"]);
            $homepage2 = explode("\n",$homepage);// dzieli plik na nowe linijki i wkłada je do tablicy
            $counter = 0;
            $counter2 =0; // przypisuje id folderu uwzglęgniając jego zamknięcie
            $amountDL=0;// określa stopień pokrewieństwa
            $parentID =0;
            $tabTmp = array();
            $tabTmp2 = array();
            $tabTmpParent = array();
            array_push($tabTmpParent,-1);
            $bookmarksCounter=0;
            $bookmarksNameCounter=0;
            $nazwaFold = array();
            $count = 0;
            $FileName[0][0] = "Pasek zakladek";

            $iconLink = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAB/klEQVQ4ja2Tu2qVURCFv7X/SxJJMBaCgZgIigQhD5BSsbDxPYKiz5BnUGNeQKOk1dLW2xtY2ATMpTBihJxwzr/3LIv/xESxERwYhtnMZc+aWQAYkqEa27TWW53V8Vs6G8v/EAFsL127q5RWEj6XpKmACdtNkhJA2CGpSzAM+zjQwBHvFz99fqXtG1cfTKh61EoYUF8TJYHB+LTd2Bcwshm6PKwjtNrJHjg6jzsCMBhKdQNtY5eC+vy+uB2N1IS1Wmd7roQx1BaSjW3a60t5tPNFcXBQVzMzNoAtJGRHYCzmUg6mO6MclsPOSPlowIV79zX//GW0N2+NusFxlOGIMC7FzmH1OUynEm5ymGLIYRXjEnYZjdLU8nK78GyzvbT5InvhSpdHHYVfsZRwU+fw6ToAY7nYNC0AZXs7D96+U/5+mAKh8hus1CXcAQ02Sf10kZKGu7vd/vpT7z9Zr7q9vaaamYaqguIeTgmg08fLi98SzLrfz8kuoaqiHP5IaXJSaaK1yxg2ehAlFPC9juK9SprNONskATZQOml6poSDGGV8MmN/B9FYTdh7dYnYQOlxK7XYSONIJShxerDjwe3++51N59gQwJuL83faipVin09m0qIFNaCaCJRUsLKJocQAOOoKH25/3Xn9r9z5uxjS1piia2f0TzqfUHrrDJ1/AqtRUJ+QQfrIAAAAAElFTkSuQmCC";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            foreach($homepage2 as $value)
            {

                //wyciaganie nazwy folderu
                if(preg_match('/<DT><H3/', $value))
                {
                    preg_match('#">(.*)</H3>#s', $value, $matches);

                    $FileName[$counter][0] = $matches[1];// przypisanie nazwy folderu
                //echo $FileName[0];
                }
                //TWORZENIE FOLDERU
                if(preg_match('/<DL><p>/', $value))
                {          
                    $tabFile[$amountDL]['idFolderu'] = $counter;// id folderu    
                    $tabFile[$amountDL]['idParent'] = end($tabTmpParent);//end($tabTmp); // id rodzica folderu          
                    $tabFile[$amountDL]['nazwaFolderu'] = $FileName[$counter][0]; // nazwa folderu
                    $FileName[$counter][1] = $amountDL;// przypisanie indeksu folderu

                    array_push($tabTmp, $tabFile[$amountDL]['idFolderu']);// do tablicy tymczasowej wkladam ostatnio otwarty folder z uwzglednieniem dodatkowego folderu przypisanego ręcznie
                    array_push($tabTmp2, $tabFile[$amountDL]['nazwaFolderu']);//tablica pomocnicza potrzebna do przechowania nazw folderów dla zakladek
                   
                    if($link->query("INSERT INTO folder(nazwa_folderu, id_uzytk, id_nadfolder) VALUES ('{$FileName[$counter][0]}', '{$id_uzytk}', '{$tabFile[$amountDL]['idParent']}');"))
                    {
                         echo "ok<br>";
                    }
                    else
                    {
                        echo "nope<br><br>";
                    }
                    $qf = mysqli_fetch_assoc($link->query("SELECT id_folder from folder where nazwa_folderu = '{$FileName[$counter][0]}' and id_uzytk ='{$id_uzytk}'"));
                         $idd[$counter]= $qf['id_folder'];
                        echo "<br>nr parentsa: ".$idd[$counter]."<br>";
                         array_push($tabTmpParent, $idd[$counter]);

                    // w tym miejscu wprowadzic nazwe folderu
                    //pobrać jego id 
                    // przypisac to pobrane id do idParent(ale stworzyć na wszelki jeszcze jedną tablice $tabTmp)
                    // na końcu zamienić inserty na updaty
                    $amountDL++;
                    $count = $counter;
                    $counter++;
                    $counter2=$counter;
                    $x = count($tabTmp);

                }
                
                // wyciąganie linków
                if(preg_match('#<A HREF="(.*)" ADD_DATE#s', $value, $m))
                {
                    
                    $bookmark[0] = $m[1];
                    $fileTMP[$bookmarksCounter]=end($tabTmp2);
                   $bookmarksTMP[$bookmarksCounter] = $bookmark[0];
                    $bookmarksCounter++;

                    $bookmark[1]= end($tabTmp);// przypisanie id folderu wliczajac dopisany folder poczatkowy "pasek zakladek"
                if($link->query("INSERT INTO zakladki(link, id_uzytk2) VALUES ('{$bookmark[0]}', '{$id_uzytk}');"))
                    {
                        
                        echo "linki zakładek w bazie<br><br>";
                    }
                    else
                    {
                        echo "nope<br><br>";
                    }
               }

                // wyciąganie nazwy linku
                if(preg_match('#">(.*)</A>#s', $value, $mname))
                {
                    
                    $bookmark[2] = $mname[1]; // przypisanie nazwy linku
                    
                   // $bookmarksNameCounter++;
                    //echo $bookmark[2]."<br>";
                   if($link->query("UPDATE zakladki SET nazwa_zakl='$bookmark[2]' where link ='{$bookmark[0]}';"))
                    {
                        echo "nazwy zakładek w bazie<br><br>";
                    }
                    else
                    {
                        echo "nope<br><br>";
                    }

                }
                    
                // wyciąganie ikony dla konkretnego linku w folderze
                if(preg_match('#ICON="(.*)">#s', $value, $micon))
                {
                    $bookmark[3] = $micon[1];
                if($link->query("UPDATE zakladki SET ikona='$bookmark[3]' where link ='{$bookmark[0]}' ;"))
                    {
                    echo "ikony zostały wprowadzone do bazy<br><br>";
                    }
                    else
                    {
                        echo "nope<br><br>";
                    }

                }
                    
                $id = $_COOKIE['id_sesja'];
                //ZAKOŃCZENIE FOLDERU
                if(preg_match('/<\/DL>/', $value)) // warunek zamknięcia folderu
                {
                    array_pop($tabTmp);// usuwa ostatni element tablicy
                    array_pop($tabTmp2);
                  array_pop($tabTmpParent);
                    $amountDL--;               
                }
            
            }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $tabTmp = array();
            array_push($tabTmp,-1);
            $ikona_folderu = "http://megaicons.net/static/img/icons_sizes/8/178/128/folders-folder-icon.png";

            for($i=0; $i<count($FileName); $i++)
            { 
                $FileName[$i][2]=end($tabTmp);
                echo "<br>";
               if($link->query( "UPDATE folder set  numer='{$FileName[$i][1]}', ikona_folderu='{$ikona_folderu}', id_folderu_w_zestawie_folderow='{$i}' where id_uzytk ='{$id_uzytk}' AND nazwa_folderu = '{$FileName[$i][0]}';"))
                {
                   //$id[$i]=$link->insert_id;
                    echo "foldery zostaly wstawione do bazy";
                   // $res = mysqli_fetch_assoc($link->query("SELECT id_folder from folder where nazwa_folderu='$FileName[$i][0]' AND id_uzytk = '$id_uzytk'"));
                   // $idFolderu[$bookmarksCounter] =$res['id_folder'];
                }
                else{
                    echo "nope";
                }

                if($i<count($FileName)-1)
                if($FileName[$i+1][1]>$FileName[$i][1]){// sprawdzenie czy jest przejscie z jednego do drugiego poziomu
                // echo "<br>";
                    //echo $FileName[$i+1][1].' > '.$FileName[$i][1];
                array_push($tabTmp,$i);
                }
                elseif ($FileName[$i+1][1]<$FileName[$i][1]) {// spr spadku poziomu
                    array_pop($tabTmp);
                }
            }

            for($i=0; $i<count($fileTMP); $i++)
            {
                   // echo $fileTMP[$i]."<br>";                  
                    $qx = mysqli_fetch_assoc($link->query("SELECT id_folder from folder where nazwa_folderu = '$fileTMP[$i]' and id_uzytk ='$id_uzytk'"));
                    $idTmp = $qx['id_folder'];
                    echo "id folderu dla danej zakladki: ".$idTmp." nazwa zakladki: ".$bookmarksTMP[$i]."<br>";
                 //   echo "update zakladki SET id_folderu='$idTmp' where link = '{$bookmarksTMP[$i]}' AND id_uzytk = '{$id_uzytk}'";
             if($link->query("UPDATE zakladki SET id_folderu='$idTmp' where link = '{$bookmarksTMP[$i]}' AND id_uzytk2 = '{$id_uzytk}';"))
                    {
                        echo "giit :D<br>";
                    }
                    else{
                        echo "nopeeee<br>";
                    }
            }
            //fclose($handle);
            header("Location:bookmarks_catcher.php");
    }
    
?>