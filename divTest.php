
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <link rel="stylesheet" type="text/css" href="style/styleMenu.css" />
    <link rel="stylesheet" type="text/css" href="style/styleSite.css" />

<head>
<body>


    <p class="lol" id="box2" onclick='clicked();'>
        testing
    </p>

    <script>

function clicked(){
   var newPage = document.getElementById("box2");

    idPage = document.createElement('div');
    idPage.className ="showContentFolder";
    newPage.appendChild(idPage);  
    
}
</script>
</body>
</html>
