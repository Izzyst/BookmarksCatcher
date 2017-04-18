<?php

    session_start();
	echo mysqli_error($link);
    require_once "connect.php";
 /* $link = new mysqli("sql302.epizy.com",  "epiz_19542191", "siostrakamdam24", "epiz_19542191_bookmarks");*/

        $link=new mysqli($host, $db_user, $db_password, $db_name);
        if(!$link)
        {
           echo "brak połączenia z bazą!"; 
        }
        else echo "ok";

 if (isset($_POST['mail'])){
	try
    {
        $link=new mysqli($host, $db_user, $db_password, $db_name);
        if(!$link)
        {
            throw new Exception('Cannot to connect to database' . mysqli_error());
        }
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
    
    if($link -> connect_errno!=0)
    {
        echo "Error: ".$link->connect_errno." ".$link->connect_error;
    } 	
		//echo mysqli_error($link);
    if(isset($_POST['mail']))
    {

        $address=rand(0,1000000);
        $email = $_POST['mail'];
        $haslo = $_POST['password'];
        $haslo2 = $_POST['password2'];
        $allFine = true;
        
        $email= mysqli_real_escape_string($link,$email);
        $haslo = mysqli_real_escape_string($link, $haslo);
        $haslo2 = mysqli_real_escape_string($link, $haslo2);

        $emailB=filter_var($email, FILTER_SANITIZE_EMAIL);// wylapuje takie wartości jak np ł ó ą

        if((filter_var($emailB, FILTER_VALIDATE_EMAIL)==FALSE) || ($emailB!=$email))
        {
            $allFine = false;
            $_SESSION['err_mail']="ENTER CORRECT EMAIL!!!";
            header('Location:registration.php');
            
        }

          if(strlen($haslo)<8 || (strlen($haslo)>20))
        {
            $allFine=false;
            $_SESSION['err_haslo'] = "PASSWORD MUST BE 8 TO 20 CHARACTERS";
            header('Location:registration.php');
            
            
        }

        if($haslo != $haslo2 )
        {
            $allFine=false;
            $_SESSION['err_haslo'] = "GIVEN PASSWORDS ARE INCORRECT!!";
            header('Location:registration.php');
        }

        //   mysqli_report(MYSQLI_REPORT_STRICT);

        $haslo = $_POST['password'].$address;
        $haslo2 = $_POST['password2'].$address;
        $haslo = htmlentities($haslo, ENT_QUOTES);

        $haslo_hash =sha1($haslo);

        //  echo $haslo;

       
		if ($link->connect_errno!=0)
		{
			header("Location:error404.php");
		}
        else
        {

            $rezultat = $link->query("SELECT id_uzytk FROM uzytk WHERE mail='$email'");
            if (!$rezultat) throw new Exception($link->error);
            $ile_takich_maili = $rezultat->num_rows;
                 
            if($ile_takich_maili >0)
            {            
                $allFine=false;
                header('Location:registration.php');
                $_SESSION['err_mail'] = "This email already exist!!";
                    
            }
            if($allFine == true)
            {
                // echo "udana walidacja!";		 
                //   echo "insert into uzytk(mail, haslo, sol) values('{$email}', sha1('{$haslo}'), sha1('{$address}'))";

                
                echo $haslo;
                if(mysqli_query($link, "insert into uzytk(mail, haslo, sol) values('{$email}', '{$haslo_hash}','{$address}')"))
                {
                    $_SESSION['udanaRejestracja'] = true;
                    header('Location:welcome.php');              
                }
                else
				{
					header("Location:error404.php");
				}
            }

            $link->close();
        }
}
else{
    header("Location:error404.php");
}
       /* catch(Exception $e)
		{
			echo '<span style="color:red;">Server ERROR!!!</span>';
			echo '<br />Informacja developerska: '.$e;
		}

        }
*/
	}


?>
