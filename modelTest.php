
<?   
    session_start();
  /*  $link=mysqli_connect("sql302.epizy.com",  "epiz_19542191", "siostrakamdam24", "epiz_19542191_bookmarks")
	or die("blad polaczenia");  */
    $link=@new mysqli($host, $db_user, $db_password, $db_name);

    if($link -> connect_errno!=0)
    {
        echo "Error: ".$link->connect_errno." ".$link->connect_error;
    } 

    
        $handle = @fopen("bookmarks_08.12.2016.txt", "r");

        $homepage = (string)file_get_contents('bookmarks_08.12.2016.html');
        $homepage2 = explode("\n",$homepage);// dzieli plik na nowe linijki i wkłada je do tablicy
        $counter = 0;
        $counter2 =0; // przypisuje id folderu uwzglęgniając jego zamknięcie
        $amountDL=0;// określa stopień pokrewieństwa
        $parentID =0;
        $tabTmp = array();
        $FileName[0] = "Pasek zakladek";


        foreach($homepage2 as $value)
        {

            //wyciaganie nazwy folderu
            if(preg_match('/<DT><H3/', $value))
            {
                preg_match('#">(.*)</H3>#s', $value, $matches);
                $FileName[0] = $matches[1];// przypisanie nazwy folderu
            //echo $FileName[0];
            }
            //TWORZENIE FOLDERU
            if(preg_match('/<DL><p>/', $value))
            {          
                $tabFile[$amountDL]['idFolderu'] = $counter;// id folderu    

                    $tabFile[$amountDL]['idParent'] = end($tabTmp); // id rodzica folderu       
                 
                $tabFile[$amountDL]['nazwaFolderu'] = $FileName[0]; // nazwa folderu
                $FileName[1] = $amountDL;// przypisanie indeksu folderu
                $FileName[2] = $parentID; // przypisanie ID nadfolderu
               echo "otwarto folder: ".$FileName[0]." o id: ".end($tabTmp)."<br>";
                echo "parentID: ".$tabFile[$amountDL]['idParent']."<br>";  
                
                array_push($tabTmp, $tabFile[$amountDL]['idFolderu']);// do tablicy tymczasowej wkladam ostatnio otwarty folder z uwzglednieniem dodatkowego folderu przypisanego ręcznie
                
                
                $amountDL++;
                $counter++;

            }

            //ZAKOŃCZENIE FOLDERU
            if(preg_match('/<\/DL>/', $value)) // warunek zamknięcia folderu
            {
                /* if(count($tabTmp)>0)
                {
                    $parentID = end($tabTmp);// w przypadku gdyby zliczało również elementy podtablicy wipsać: count($tabTmp)/3

                    
                }*/
                
                // zanim otworze folder biere ostatniego rodzica i nadaje mu warotsc

                echo "zamknieto folder o id: ".end($tabTmp)."<br>";
                array_pop($tabTmp);// usuwa ostatni element tablicy
                $amountDL--;

                

               
                
                }
        }
    
    ?>
