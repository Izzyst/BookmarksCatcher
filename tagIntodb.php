<?php
session_start();
/*$link= new mysqli("sql302.epizy.com",  "epiz_19542191", "siostrakamdam24", "epiz_19542191_bookmarks")
	or die("blad polaczenia");*/
    require_once "connect.php";   
    $link=@new mysqli($host, $db_user, $db_password, $db_name);

    if($link -> connect_errno!=0)
    {
        echo "Error: ".$link->connect_errno." ".$link->connect_error;
    } 

if(isset($_POST['tagName']))
{
    $selected = $_POST['tagName'];
    $id_tag=array();
   for($i=0; $i<count($selected); $i++)
    {
        echo "You have selected :".$selected[$i]."<br>";  //  Displaying Selected Value
        $j = mysqli_fetch_assoc(mysqli_query($link, "SELECT id_tagi from tagi where nazwa_tagu='$selected[$i]'"));
       array_push($id_tag, $j['id_tagi']);

    }

    for($k=0; $k<count($id_tag); $k++)
    {
        try{
            if($link->query("INSERT INTO tag_zakladki(id_tagi, id_zakladki) VALUES ('{$id_tag[0]}', '{$_POST['idZakl']}');"))
            {
                // echo "ok<br>";
            }
            else
            {
                 throw new Exception('Unable to insert to db');
            }
        }
        catch(Exception $e){ echo $e->getMessage(); }
    }
}
header("Location:bookmarks_catcher.php");
?>