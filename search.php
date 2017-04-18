
  <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">


    <link rel="stylesheet" type="text/css" href="style/styleMenu.css" />
    <link rel="stylesheet" type="text/css" href="style/styleSite.css" />
    <!--<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">-->
    <link rel="stylesheet" type="text/css" href="style/font-awesome.min.css" >
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <title>search</title>


</head>
<body>
 <div id="container">
    <!--========================================================kod odp. za menu-->
       
        <div id ="logoLine">

            <div id="logo">
                <img src="./img/logo_catcher.jpg" id = "imgLogo">          
            </div>
            

            <a href="logout.php"> log out </a> |
            <a href="model.php"> dodaj zakladki do bazy danych </a>
           
        </div>
<p>
         <form action="search.php" method="get" id = "searchform" name="searchForm">   
                         
                <div class="search-container">
                    <input type="text" class="searchField" placeholder="Szukaj.." value name="search">
                </div>
            </form>
            

         <!--przycisk rozwijania latajacego menu bara
        <div id="menuBarBtn" onclick = "toggleCP()">
            <div></div>
            <div></div>
            <div></div>
        </div>-->

        <!--==================================================== latający menu bar 
        <div id="menuBar">

               

        </div>

-->
        <!--=================================================== kod odp. za zawartość strony-->
        <div id = "pageContent" >
<?php
// TO DO : 
//WYBIERANIE WSZYSTKICH DANYCH DLA WPISANEJ FRAZY
// WSADZENIE TYCH DANYCH DO BOXÓW
//WYŚWIETLANIE LOGO I CAŁĄ RESZTĘ SZATY GRAFICZNEJ


    session_start();
    require_once "connect.php";
 /* $link = new mysqli("sql302.epizy.com",  "epiz_19542191", "siostrakamdam24", "epiz_19542191_bookmarks");*/

        $link=new mysqli($host, $db_user, $db_password, $db_name);

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
        if(isset($_GET['search']))
        {
            $lookFor = $_GET['search'];
            setcookie('lookFor',$lookFor, time()+3600);
        }  
    }

?>

<script>

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

var lookFor = getCookie('lookFor');

$.getJSON('data.php', function(dataS) {
    for(var i=0; i< dataS.length; i++)
    {
        if(dataS[i]['nazwa_folderu'].includes(lookFor))
        {
            var cos = document.getElementById("pageContent");
            idBox = document.createElement('div');
            idImg = document.createElement('div');
            idA = document.createElement('a');
            idSrc = document.createElement('img');
            idDesc = document.createElement('div');

            idBox.className="responsive";
            idBox.id ="box";
            var idWybranegoFolderu2=dataS[i]['id_folder'];

            
            idImg.className="img";
            idA.target ="_blank";
            idA.href = "folderContent.php?idFolderu="+idWybranegoFolderu2;
            idSrc.src = dataS[i]["ikona_folderu"];
            idSrc.width="80";
            idSrc.height="80";
            idDesc.className ="desc";

            var res = dataS[i]["nazwa_folderu"];
            if(dataS[i]["nazwa_folderu"].length >=40)
            {
                res = dataS[i]["nazwa_folderu"].substring(0,39);
                var dots = "...";
                res= res.concat(dots);
                idDesc.innerText =res;
            }
            else{
                idDesc.innerText =res;
            }

            idA.appendChild(idSrc);
            idImg.appendChild(idA);
            //idImg.appendChild(idSrc);
            idImg.appendChild(idDesc);
            idBox.appendChild(idImg);
            cos.appendChild(idBox);    
    
        }
    
  }


    $.getJSON('data2.php', function(dataS2) {
    for(var i=0; i< dataS2.length; i++)
    {
        if(dataS2[i]['nazwa_zakl'].includes(lookFor))
        {
            var cos = document.getElementById("pageContent");
            idBox = document.createElement('div');
            idImg = document.createElement('div');
            idA = document.createElement('a');
            idSrc = document.createElement('img');
            idDesc = document.createElement('div');

            idBox.className="responsive";
            idBox.id ="box";
            idImg.className="img";
            idA.target ="_blank";
            idA.href = dataS2[i]["link"];
            idSrc.src = dataS2[i]["ikona"];
            idSrc.width="80";
            idSrc.height="80";
            idDesc.className ="desc";

            var res2 = dataS2[i]["nazwa_zakl"];
                if(dataS2[i]["nazwa_zakl"].length >=50)
                {
                    res2 = dataS2[i]["nazwa_zakl"].substring(0,49);
                    var dots = "...";
                    res2= res2.concat(dots);
                    idDesc.innerText =res2;
                }
                else{
                     idDesc.innerText =res2;
                }
            idA.appendChild(idSrc);
            idImg.appendChild(idA);
            //idImg.appendChild(idSrc);
            idImg.appendChild(idDesc);
            idBox.appendChild(idImg);
            cos.appendChild(idBox);
         }
        
    }   
    });
    
});
</script>
</div>
</body>
</html>