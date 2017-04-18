<?php

    session_start();
   /* $link=mysqli_connect("sql302.epizy.com",  "epiz_19542191", "siostrakamdam24", "epiz_19542191_bookmarks")
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

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <link rel="stylesheet" type="text/css" href="style/styleMenu.css" />
    <link rel="stylesheet" type="text/css" href="style/styleSite.css" />
    <!--<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">-->
    <link rel="stylesheet" type="text/css" href="style/font-awesome.min.css" >
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript" src="http://ciasteczka.eu/cookiesEU-latest.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <title>bookmarks catcher</title>




</head>
<body>
<div id="selectTag">
    <form action="tagIntodb.php" method="post">
            <div id="tagnames"><br>Select tag: <br><br></div>
            <input type="submit" value = "ok" onclick = "turnOffTag()" id="tagTurnOffBtn">
       </form>
        </div>
    <div id="container">
    <!--========================================================kod odp. za menu-->
       
        <div id ="logoLine">

            <div id="logo">
                <img src="./img/logo_catcher.jpg" id = "imgLogo">          
            </div>
            <div id="menu">
            <ul>
            <li><a href="logout.php"> log out  </a> </li>
            <li><a href="model.php">  Add bookmarks to database  </a></li>
            <li><a href="dropUser.php"> delete account </a></li>
            </ul>
          </div>
        </div>
        <p>
        <form action="search.php" method="get">   
                         
                <div class="search-container">
                    <input type="text" class="searchField" placeholder="Search.." value name="search">
                </div>
            </form>
            
         <!--przycisk rozwijania latajacego menu bara-->
        <div id="menuBarBtn" onclick = "toggleCP()">
            <div></div>
            <div></div>
            <div></div>
        </div>

        <!--==================================================== latający menu bar -->
        <div id="menuBar">
                <b>Tags:</b>
 <?
            $tagsnames= array();
            $p = mysqli_fetch_assoc( mysqli_query($link, "SELECT id_uzytk_sesji  FROM sesja WHERE id = '$id'"));
            $id_uzytk=$p['id_uzytk_sesji'];
            //echo $id_uzytk;
            $query = "SELECT nazwa_tagu from tagi where id_uzytkT = '$id_uzytk'";
            $result = mysqli_query($link, $query);
            $count=0;
            while($row = mysqli_fetch_array($result))
            {
                array_push($tagsnames, $row['nazwa_tagu']);
                echo '<a target = "_blank" href = "tagContent.php?tag='.end($tagsnames).'">';
                echo "    ".$tagsnames[$count];
                echo '</a>';
                echo '<br>';
                $count++;
            }
        ?>

             <br>Add a new tag:<br>

        <form action="newTagName.php" method="post">
           <br> <input type="text" name="newTag" ><br><br>
            <input type="submit" value = "ok"  id="newTagSub">
       </form>

        </div>

        <!--=================================================== kod odp. za zawartość strony-->
        <div id = "pageContent" >

<script>
// wysuwanie się okna cp po naciśnieciu na guzik

$('p').click(function(){
    alert(this.id);
});

var tagVisible = false;
function showDivTag()
{
        tagVisible = true;
        $('#selectTag').css({'display':'table'});
        var x = this.id;
        var res = x.split("=");
       // jak pyknie czasu: szare menu pod logo z przyciskami
       var name= "tagName[]";
      $.getJSON('dataTag.php', function(dataT) {
            for(var i=0; i< dataT.length; i++)
            {
                var radioHtml = '<input type="checkbox" name="' + name +'"'+ ' value="'+ dataT[i]['nazwa_tagu'] +'"';
                    radioHtml += '/>';
                   radioHtml += dataT[i]['nazwa_tagu'];
                    radioHtml += '<br>';
                    document.getElementById('tagnames').innerHTML += radioHtml;//'<br>Some new content!'; TU !! dorobić radio buttony!!!    

            }
        });
       var idZakl =      '<input  name="idZakl" type="hidden" '+ ' value="'+res[1] +'"';
                   idZakl += '/>';
        document.getElementById('tagnames').innerHTML += idZakl;
}

function turnOffTag()
{
      if(tagVisible == true)
      {        
         $('#selectTag').css({'display':'none'});
          tagVisible=false;
      }

}
// wysuwanie się okna cp po naciśnieciu na guzik
$(function() {
  var menuVisible = false;
  $('#menuBarBtn').click(function() {

    if (menuVisible== true) {
      $('#menuBar').css({'display':'none'});
      menuVisible = false;
      //return;
    }else{
$('#menuBar').css({'display':'block'});
    menuVisible = true;
    }   
  });

});
//=================================================================================================================================
jQuery(document).ready(function(){
	jQuery.fn.cookiesEU();
});


        var zakl = new Array();
        <?php for($i=0; $i<count($zakl); $i++)
        {
            echo "zakl[".$i."] = ".$zakl[$i].";";
        }?>

        // wysuwanie się okna cp po naciśnieciu na guzik
            function toggleCP(){
                var cp = document.getElementById("menuBar");
                cp.style.height = window.innerHeight - 60 + "px";
                if(cp.style.left=="0px"){
                    cp.style.left = "-260px";
                }
                else{
                    cp.style.left="0px";
                }
            }
  



<?
        if(isset($_GET['idFolderu']))
        {
            echo "var x = ".$_GET['idFolderu'].";";
        }
        else{
            alert("NOT FOUND!");// DODATKOWA STRONA error page + not found 404 + after 5s + przekierowanie do str głównej
            header('Location:errorPage.php');           
        }           
            //echo "alert("+$_GET['idFolderu']+");";
            //echo "var x=".$_GET['idFolderu'].";";

        ?>
        
         function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for(var i = 0; i <ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "notFound";
        }


        var idWybranegoFolderu = x;
           var idNadFolderu = getCookie('idFolderu');
           
           //alert(idNadFolderu);
            $.getJSON('data.php', function(data) {
                for(var i=0; i< data.length; i++)
                    {                    
                        if(data[i]["id_nadfolder"] == idWybranegoFolderu)
                        {
                                var cos = document.getElementById("pageContent");
                                idBox = document.createElement('div');
                                idImg = document.createElement('div');
                                idA = document.createElement('a');
                                idSrc = document.createElement('img');
                                idDesc = document.createElement('div');

                                idBox.className="responsive";
                                idBox.id ="box";
                                var idWybranegoFolderu2=data[i]['id_folder'];
                                document.cookie="idFolderu="+idWybranegoFolderu2;
                                idImg.className="img";
                                idA.target ="_blank";
                                idA.href = "folderContent.php?idFolderu="+idWybranegoFolderu2;
                                idSrc.src = data[i]["ikona_folderu"];
                                idSrc.width="80";
                                idSrc.height="80";
                                idDesc.className ="desc";
                                var res = data[i]["nazwa_folderu"];
                                if(data[i]["nazwa_folderu"].length >=40)
                                {
                                    res = data[i]["nazwa_folderu"].substring(0,39);
                                    var dots = "...";
                                    res= res.concat(dots);
                                    idDesc.innerText =res;
                                }
                                else{
                                    idDesc.innerText =res;
                                }
                               // idDesc.innerText =data[i]["nazwa_folderu"];
                                idA.appendChild(idSrc);
                                idImg.appendChild(idA);
                                //idImg.appendChild(idSrc);
                                idImg.appendChild(idDesc);
                                idBox.appendChild(idImg);
                                cos.appendChild(idBox);    
                        }

            
                    }

            $.getJSON('data2.php', function(data2) {
                    //var idWybranegoFolderu = 6;
                for(var i=0; i< data2.length; i++)
                {

                if(data2[i]["id_folderu"] == idWybranegoFolderu)
                    {
                        var cos = document.getElementById("pageContent");
                        idBox = document.createElement('div');
                        idBox.className="responsive";
                        idBox.id ="box";

                        idImg = document.createElement('div');
                        idImg.className="img";

                        idA = document.createElement('a');
                        idA.target ="_blank";
                        idA.href = data2[i]["link"];

                        idSrc = document.createElement('img');
                        idSrc.src = data2[i]["ikona"];
                        idSrc.width="80";
                        idSrc.height="80";

                        idDesc = document.createElement('div');
                        idDesc.className ="desc";

                        idTag = document.createElement('p');
                        idTag.className = "tag";
                        idTag.id="idZakl="+data2[i]['id_zakladki'];
                        idTag.onclick =showDivTag;

                        idLabel = document.createElement('span');
                        idLabel.className = "glyphicon glyphicon-tag";
                        
                        var res2 = data2[i]["nazwa_zakl"];
                        if(data2[i]["nazwa_zakl"].length >=50)
                        {
                            res2 = data2[i]["nazwa_zakl"].substring(0,49);
                            var dots = "...";
                            res2= res2.concat(dots);
                            idDesc.innerText =res2;
                        }
                        else{
                            idDesc.innerText =res2;
                        }
                        idA.appendChild(idSrc);
                        idTag.appendChild(idLabel);
                        idImg.appendChild(idTag);

                        idImg.appendChild(idA);
                        idImg.appendChild(idDesc);
                        idBox.appendChild(idImg);
                        cos.appendChild(idBox);
                        }
                }
                        
                });
                
            });


</script>

</body>

</html>