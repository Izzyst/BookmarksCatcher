<?php
    /*session_start();
    if(!isset($_SESSION['udanaRejestracja']))
    {
        header('Location: bookmarks_catcher.php');
        exit();
    }
    else
    {
        unset($_SESSION['udanaRejestracja']);
    }
*/
    // strona ma za zadanie przekierowywać do strony logowanie :PASSWORD_BCRYPT

require_once "connect.php";
    $link=@new mysqli($host, $db_user, $db_password, $db_name);
foreach ($_COOKIE as $k=>$v) {
	$_COOKIE[$k] = mysqli_real_escape_string($link, $v);
}

if (! isset($_COOKIE['id'])){header("location:index.php");exit;}

$q = mysqli_fetch_assoc(mysqli_query($link, "select id_uzytk_sesji from sesja where 
 web = '$_SERVER[HTTP_USER_AGENT]' AND ip = '$_SERVER[REMOTE_ADDR]';"));

if (!empty($q['id_uzytk_sesji'])){
	//echo "Zalogowany użytkownik o ID: " . $q['id_uzytk_sesji'] ;
   header("location:bookmarks_catcher.php");

} else {
	header("location:index.php");
	}
?>
<!DOCTYPE html>
<html>
<body>


</body>
</html>



