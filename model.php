<html>
<head>
</head>
<body>
<a target = "_blank" href = "https://support.google.com/chrome/answer/96816?">How to export (to html file) your bookmarks? </a>
    <form action="upload_file.php" method="post"
        enctype="multipart/form-data">
        <label for="file">Filename:</label>
        <input type="file" name="file" id="file"><br>
        <input type="submit" name="submit" value="Submit">
    </form>

</body>
</html>

<?php   
error_reporting(0);

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
            mysqli_query($link, "UPDATE sesja set token = '$t' where id = '$id'");
        }     

        $i = mysqli_fetch_assoc(mysqli_query($link, "SELECT id_uzytk_sesji from sesja where id = '$id'"));
        $id_uzytk = $i['id_uzytk_sesji'];
        //echo $id_uzytk;
    
       
    }
        //$handle = @fopen("bookmarks_08.12.2016.txt", "r");        
    ?>