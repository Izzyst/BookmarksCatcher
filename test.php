<?php

/*	$link=mysqli_connect("sql302.epizy.com",  "epiz_19542191", "siostrakamdam24", "epiz_19542191_bookmarks")
	or die("blad polaczenia");*/
    require_once "connect.php";   
$link=@new mysqli($host, $db_user, $db_password, $db_name);

    if($link -> connect_errno!=0)
    {
        echo "Error: ".$link->connect_errno." ".$link->connect_error;
    } 
	$q=mysqli_query($link,"select * from uzytk");
	echo mysqli_error($link);
	while($tab = mysqli_fetch_assoc($q))
	{
		echo"<li> $tab[mail]";
	}
?>